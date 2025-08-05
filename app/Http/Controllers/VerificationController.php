<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    private $apikey;
    private $secretkey;
    private $url;


    function __construct() {
        $this->apikey = env('SMS_APIKEY');
        $this->secretkey = env('SMS_SECRETKEY');
        $this->url = env('SMS_URL');
    }


    function sendsms($phone) {

        $code = substr(str_shuffle("0123456789"), 0, 4);
        $text = 'STARCAFE: Your verification code is: '. $code;

        $params = $this->url .'gw/bulk360/v3_0/send.php?'. http_build_query([
            'user' => urlencode($this->apikey),
            'pass' => urlencode($this->secretkey),
            'from' => '66688',
            'to' => $phone,
            'text' => $text
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $sentResult = curl_exec($ch);
        if ($sentResult == FALSE) {
            return 'Curl failed for sending sms to bulk360.. '.curl_error($ch);
        }
        curl_close($ch);

        return $code;
    }

}
