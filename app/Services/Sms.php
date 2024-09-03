<?php

namespace App\Services;

class Sms
{
    private static string $apiUrl = "https://api.netgsm.com.tr/sms/send/get";
    const username = '***';
    const password = '**-**';
    const title = '850*******';

    private static string $number, $message;

    public static function send($number, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('usercode' => self::username,
                'password' => self::password,
                'gsmno' => $number,
                'message' => $message,
                'msgheader' => self::title,
                'filter' => '0',
                'startdate' => '',
                'stopdate' => ''),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

    }

}
