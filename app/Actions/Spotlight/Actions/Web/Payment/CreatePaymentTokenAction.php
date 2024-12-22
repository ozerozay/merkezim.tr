<?php

namespace App\Actions\Spotlight\Actions\Web\Payment;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Exceptions\AppException;
use App\Managers\PayTRPaymentManager;
use App\Models\Payment;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePaymentTokenAction
{
    use AsAction;

    public function handle($info)
    {
        try {
            \DB::beginTransaction();
            $client = User::where('id', $info['client_id'])->first();

            $keys = PayTRPaymentManager::getKeys($client->branch_id);

            $merchant_id = $keys['merchant_id'];
            $merchant_key = $keys['merchant_key'];
            $merchant_salt = $keys['merchant_salt'];

            $user_basket = htmlentities(json_encode([
                [$info['type'], $info['total'], 1],
            ]));

            $payment = Payment::create([
                'unique_id' => CreateUniqueID::run('payment'),
                'data' => collect($info)->forget('cardNumber')->forget('cardName')->forget('expiryMonth')->forget('expiryYear')->forget('cvv'),
                'type' => $info['type'],
            ]);

            $merchant_oid = (string) $payment->unique_id;
            $test_mode = 0;
            $non_3d = 0;

            $user_ip = request()->ip();

            $email = 'ozerozay@gmail.com';

            $payment_amount = $info['total'];
            $currency = 'TL';

            $installment_count = $info['selectedInstallment'] == 'pesin' ? 0 : $info['selectedInstallment'];

            $payment_type = 'card';
            $hash_str = $merchant_id.$user_ip.(string) $merchant_oid.$email.$payment_amount.$payment_type.$installment_count.$currency.$test_mode.$non_3d;
            $token = base64_encode(hash_hmac('sha256', $hash_str.$merchant_salt, $merchant_key, true));

            $resource_array = [
                'ip' => $user_ip,
                'merchant_oid' => $payment->unique_id,
                'payment_amount' => number_format((float) $info['total'], 2),
                'user_name' => auth()->user()->name,
                'user_phone' => auth()->user()->phone,
                'user_basket' => $user_basket,
                'post_url' => 'https://www.paytr.com/odeme',
                'installment_count' => $installment_count,
                'cc_owner' => $info['cardName'],
                'card_number' => $info['cardNumber'],
                'cvv' => $info['cvv'],
                'expiry_month' => $info['expiryMonth'],
                'expiry_year' => $info['expiryYear'],
                //'payable_type' => $payment_type,
                'merchant_id' => $keys['merchant_id'],
                'user_ip' => $user_ip,
                'email' => auth()->user()->email ?? 'ozerozay@gmail.com',
                'currency' => 'TL',
                'test_mode' => 0,
                'non_3d' => 0,
                'merchant_ok_url' => 'https://marge.merkezim.tr.test/paysuccess?id='.$payment->unique_id,
                'merchant_fail_url' => 'https://marge.merkezim.tr.test/payerror?id='.$payment->unique_id,
                'debug_on' => 1,
                'client_lang' => 'tr',
                'paytr_token' => $token,
                'non3d_test_failed' => 0,
                'no_installment' => 0,
                'max_installment' => 0,
                'card_type' => $installment_count > 0 ? $info['bin']['brand'] : null,
                'lang' => 'tr',
                'payment_type' => 'card',
            ];

            $post_array = json_decode(collect($resource_array)->toJson(), true);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.paytr.com/odeme');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            //XXX: DİKKAT: lokal makinanızda "SSL certificate problem: unable to get local issuer certificate" uyarısı alırsanız eğer
            //aşağıdaki kodu açıp deneyebilirsiniz. ANCAK, güvenlik nedeniyle sunucunuzda (gerçek ortamınızda) bu kodun kapalı kalması çok önemlidir!
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $result = @curl_exec($ch);

            if (curl_errno($ch)) {
                \DB::rollBack();

                throw new AppException('Lütfen tekrar deneyin.');
            }
            curl_close($ch);
            if (str_contains($result, 'failed')) {
                \DB::rollBack();

                throw new AppException($result);
            }

            return $result;
            /*dump($token);
            dump($payment);*/
        } catch (\Throwable $e) {
            \DB::rollBack();
            dump($e);

            return null;
        }

    }
}
