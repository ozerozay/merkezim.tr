<?php

namespace App\Managers;

use App\Models\ApiKey;
use App\Models\TaksitOran;
use Illuminate\Support\Facades\Http;
use Request;

class PayTRPaymentManager
{
    private $paytrUrl = 'https://www.paytr.com/odeme/api/get-token';

    private $merchantId;

    private $secretKey;  // This is required to sign your request.

    public function __construct()
    {
        $this->merchantId = env('PAYTR_MERCHANT_ID');
        $this->secretKey = env('PAYTR_SECRET_KEY');
    }

    /**
     * Get PayTR token for a new payment.
     *
     * @return array
     */
    public function getToken(array $paymentData)
    {
        $data = [
            'merchant_id' => $this->merchantId,
            'user_ip' => request()->ip(),
            'merchant_oid' => $paymentData['merchant_oid'],
            'email' => $paymentData['email'],
            'payment_amount' => $paymentData['payment_amount'] * 100,
            'currency' => $paymentData['currency'] ?? 'TRY',
            'user_basket' => json_encode($paymentData['user_basket']),
            'no_installment' => $paymentData['no_installment'],
            'max_installment' => $paymentData['max_installment'],
        ];

        // Send POST request to PayTR API
        $response = Http::asForm()->post($this->paytrUrl, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new \Exception('Error getting PayTR token');
        }
    }

    public function bin($cc, $branch)
    {
        try {

            $merchant_id = ApiKey::where('branch_id', $branch)->first()->merchant_id;
            $merchant_key = decrypt(ApiKey::where('branch_id', $branch)->first()->merchant_key);
            $merchant_salt = decrypt(ApiKey::where('branch_id', $branch)->first()->merchant_salt);

            $validator = \Validator::make(['cc' => $cc], ['cc' => 'required']);

            $validated = $validator->validated();

            $hash_str = $validated['cc'].$merchant_id.$merchant_salt;
            $paytr_token = base64_encode(hash_hmac('sha256', $hash_str, $merchant_key, true));
            $post_vals = [
                'merchant_id' => $merchant_id,
                'bin_number' => $validated['cc'],
                'paytr_token' => $paytr_token,
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.paytr.com/odeme/api/bin-detail');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $result = @curl_exec($ch);

            if (curl_errno($ch)) {
                dump($ch);
            }

            curl_close($ch);

            $result = json_decode($result, 1);

            return $result;

            /*if ($result['status'] == 'success') {
                $to = TaksitOran::where('name', $result['brand'])->first();
                $result['oran'] = $to;

                return $this->res(true, '', new BinResource($result));
            } else {
                return $this->error('Hata');
            }*/
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function getKeys($branch): array
    {
        return [
            'merchant_id' => ApiKey::where('branch_id', $branch)->first()->merchant_id,
            'merchant_key' => decrypt(ApiKey::where('branch_id', $branch)->first()->merchant_key),
            'merchant_salt' => decrypt(ApiKey::where('branch_id', $branch)->first()->merchant_salt),
        ];
    }

    public function getTaksitOran($branch): void
    {
        $merchant_id = ApiKey::where('branch_id', $branch)->first()->merchant_id;
        $merchant_key = decrypt(ApiKey::where('branch_id', $branch)->first()->merchant_key);
        $merchant_salt = decrypt(ApiKey::where('branch_id', $branch)->first()->merchant_salt);
        $request_id = time();

        $paytr_token = base64_encode(hash_hmac('sha256', $merchant_id.$request_id.$merchant_salt, $merchant_key, true));

        $post_vals = [
            'merchant_id' => $merchant_id,
            'request_id' => $request_id,
            'paytr_token' => $paytr_token,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.paytr.com/odeme/taksit-oranlari');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);

        //XXX: DİKKAT: lokal makinanızda "SSL certificate problem: unable to get local issuer certificate" uyarısı alırsanız eğer
        //aşağıdaki kodu açıp deneyebilirsiniz. ANCAK, güvenlik nedeniyle sunucunuzda (gerçek ortamınızda) bu kodun kapalı kalması çok önemlidir!
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = @curl_exec($ch);

        if (curl_errno($ch)) {
            echo curl_error($ch);
            curl_close($ch);
            exit;
        }

        curl_close($ch);
        $result = json_decode($result, 1);

        if ($result['status'] == 'success') {

            TaksitOran::firstOrCreate([
                'branch_id' => $branch,
                'data' => $result['oranlar'],
            ]);
            /*
            foreach ($result['oranlar'] as $key => $oran) {
                dump($oran);
                $to = TaksitOran::where('name', $key)->first();
                if ($to) {
                    $to->update($oran);
                } else {
                    TaksitOran::create([
                        'name' => $key,
                        ...$oran,
                    ]);
                }
                if ($to) {
                    $to->update($oran);
                }
            }*/
        } else { //Örn. $result -> array('status'=>'error', "err_msg" => "Zorunlu alan degeri gecersiz veya gonderilmedi: "
            echo $result['err_msg'];
        }
    }
}
