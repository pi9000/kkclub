<?php

namespace App\Trait;

use App\Models\User;
use Illuminate\Support\Facades\DB;

trait Integration
{
    public function deposit($extplayer,$amount)
    {
        $api = DB::table('api_providers')->first();
        $endpoint ="{$api->url}Transfer?apikey={$api->apikey}&signature={$api->secretkey}&username={$extplayer}&amount={$amount}";
        return $this->curl_postc($endpoint);
    }

    public function withdraw($extplayer,$amount)
    {
        $api = DB::table('api_providers')->first();
        $endpoint ="{$api->url}Withdraw?apikey={$api->apikey}&signature={$api->secretkey}&username={$extplayer}&amount={$amount}";
        return $this->curl_postc($endpoint);
    }

    function curl_postc($endpoint)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
