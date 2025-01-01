<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class SMS extends Model
{
    public $table = 'sms';

    protected $fillable = ['mesaj', 'tel', 'cesit', 'api', 'kullanici', 'tarih', 'created_at', 'updated_at'];


    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }

    protected $connection = 'best';

    public static function Kontrol($id)
    {
        $adres = "http://sms.verimor.com.tr/v2/status?custom_id={$id}&username=908505328184&password=ds2lo08sa!00";
        $sonuc = Http::get($adres);

        $sms = SMS::find($id);

        if ($sonuc->successful()) {
            $sonuc_json = json_decode($sonuc->body(), true);
            $sms->api = $sonuc->body();
            $sms->durum = $sonuc_json[0]['status'];
            $sms->save();
            return $sonuc->body();
        }
    }

    public static function Gonder($tel, $mesaj, $cesit, $kullanici = 0, $baslik = 'MARGE')
    {
        $sms = SMS::create([
            'mesaj' => $mesaj,
            'tel' => $tel,
            'cesit' => $cesit,
            'kullanici' => $kullanici
        ]);
        //dd($sms);

        return SMS::Verimor($tel, $mesaj, $sms->id, $baslik);
    }

    public static function Verimor($phones, $message, $custom, $header = 'MARGE')
    {
        $sms_msg = array(
            "username" => "908505328184",
            "password" => "ds2lo08sa!00",
            "source_addr" => $header,
            "custom_id" => $custom,
            "messages" => array(
                array(
                    "msg" => $message,
                    "dest" => $phones
                )
            )
        );

        $ch = curl_init('http://sms.verimor.com.tr/v2/send.json');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POSTFIELDS => json_encode($sms_msg),
        ));
        $http_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //dd($http_response);
        if ($http_code != 200) {
            return false;
        }

        return true;
    }
}
