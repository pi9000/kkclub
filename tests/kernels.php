<?php

function sendotps($number)
{
    $params = [
        'applicationId' => '164351467444170831FE3FA61F66AAFF',
        'messageId' => 'B4706A117994128A339D9C2F143C49FA',
        'from' => "Retrify",
        'to' => $number
    ];

    $jsonData = json_encode($params);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://38rnxm.api.infobip.com/2fa/2/pin',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Authorization: App 7fef49a13a4fcff2399a012eff755c71-a552c1b3-5455-464f-b2b7-ac9c4ce19493',
            'Content-Type: application/json',
            'Accept: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($response);

    if (isset($result->pinId)) {
        return $result->pinId;
    }
}

function otpResend($pinId)
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://38rnxm.api.infobip.com/2fa/2/pin/'.$pinId.'/resend',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: App 7fef49a13a4fcff2399a012eff755c71-a552c1b3-5455-464f-b2b7-ac9c4ce19493',
            'Content-Type: application/json',
            'Accept: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($response);

    if (isset($result->pinId)) {
        return $result->pinId;
    }
}

function verifyotps($pinId,$pin)
{
    $params = [
        'pin' => $pin,
    ];

    $jsonData = json_encode($params);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://38rnxm.api.infobip.com/2fa/2/pin/'.$pinId.'/verify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Authorization: App 7fef49a13a4fcff2399a012eff755c71-a552c1b3-5455-464f-b2b7-ac9c4ce19493',
            'Content-Type: application/json',
            'Accept: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($response);

    if (isset($result->verified)) {
        if ($result->verified == true) {
            return 1;
        } else {
            return 2;
        }
    }
}
