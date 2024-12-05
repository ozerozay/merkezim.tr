<?php

namespace App\Http\Controllers;

use App\Models\SMS;

class SmsController extends Controller
{
    public function verimorSend($messages, $customID, $source)
    {
        $campaign = [
            'username' => env('VERIMOR_USER_NAME'), // https://oim.verimor.com.tr/sms_settings/edit adresinden öğrenebilirsiniz.
            'password' => env('VERIMOR_USER_PASSWORD'), // https://oim.verimor.com.tr/sms_settings/edit adresinden belirlemeniz gerekir.
            'source_addr' => $source,
            'custom_id' => $customID,
            'messages' => $messages,
        ];

        $ch = curl_init('https://sms.verimor.com.tr/v2/send.json');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($campaign),
            //CURLOPT_VERIFYPEER => 0,
            // CURLOPT_CAINFO => "/tmp/rapidssl.crt",
            // CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $http_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            return $http_response;
        }

        if ($http_code == 0) {
            echo "HTTPS bağlantısı kurulamadı. Servis adresinin doğru olduğunu, php'nin network bağlantısı kurabildiğini ve sertifikalarınızın güncel olduğunu teyit edin.\n";
            echo "$http_code $http_response\n";
            echo curl_error($ch)."\n";
            print_r(error_get_last());

            return false;
        }

        if ($http_code != 200) {
            echo "$http_code $http_response\n";
            echo curl_error($ch)."\n";
            print_r(error_get_last());

            return false;
        }

        return $http_response;
    }

    public function send($number, $message, $user_id, $source): bool
    {
        try {
            \DB::beginTransaction();

            $messages[] = [
                'msg' => $message,
                'dest' => "90$number", //"905056277636"
            ];

            $sms_message = SMS::create([
                'user_id' => $user_id,
                'country' => '90',
                'phone' => $number,
                'message' => $message,
            ]);

            $campaign_id = $this->verimorSend($messages, $sms_message->id, $source);

            if ($campaign_id) {
                $sms_message->campaign_id = $campaign_id;
                $sms_message->save();

                \DB::commit();

                return true;
            }

            \DB::rollBack();

            return false;
        } catch (\Throwable $e) {
            \DB::rollBack();

            return false;
        }
    }
}
