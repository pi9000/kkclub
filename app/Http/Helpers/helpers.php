<?php

use App\Lib\ClientInfo;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use App\Models\Result;
use App\Models\Transaction;
use App\Models\Bonus;
use App\Models\ApiActive;
use App\Models\ApiProvider;

function bonusecheck($id, $user)
{
    $bonus = Bonus::find($id);
    $transaction = Transaction::where('transaksi', 'Top Up')->where('agent_id',config('agent_id'))->where('id_user', $user)->where('bonus', $bonus->id)->where('status', 'Sukses')->first();

    if (!empty($transaction)) {
        $result  = 'disabled';
    } else {
        $result  = '';
    }
    return $result;
}

function popupHome()
{
    $popup = DB::table('tb_popup')->where('agent_id',config('agent_id'))->where('status', 'active')->first();
    return $popup;
}

function togels()
{
    $data = Result::all();
    return $data;
}

function api_providers($id)
{
    $data = ApiProvider::find($id);
    if (empty($data)) {
        $data = 'Unknown';
    }
    return $data->provider ?? 'Unknown';
}

function api_active()
{
    $data = ApiActive::first();
    return $data;
}

function general()
{
    $general = DB::table('tb_web')->where('agent_id',config('agent_id'))->first();
    return $general;
}

function getCredentialUsername($slug)
{
    if ($slug == 'mega888') {
        return auth()->user()->mega888_id;
    } else if ($slug == 's918kiss') {
        return auth()->user()->s918kiss_id;
    } else if ($slug == 'pussy888') {
        return auth()->user()->pussy888_id;
    }
}

function getCredentialPassword($slug)
{
    if ($slug == 'mega888') {
        return auth()->user()->mega888_password;
    } else if ($slug == 's918kiss') {
        return auth()->user()->s918kiss_password;
    } else if ($slug == 'pussy888') {
        return auth()->user()->pussy888_password;
    }
}

function providersList()
{
    $provider = Provider::where('status', 1)->get();
    return $provider;
}

function navbar($type)
{
    $provider = Provider::where('type', $type)->where('status', 1)->get();
    return $provider;
}

function floating()
{
    $floating = DB::table('floatings')->get();
    return $floating;
}

function flag()
{
    $floating = DB::table('tb_api')->get();
    return $floating;
}

function getNotif()
{
    $today = date('Y-m-d');

    $depos = Transaction::where('transaksi', 'Top Up')->whereDate('created_at', $today)->where('status', 'Pending')->limit(1)->get();

    foreach ($depos as $depo)

        if (!empty($depo)) {
            $notif = '<div class="alert alert-info alert-dismissible text-dark" role="alert">
            Deposit request from ' . $depo->username . ' amount MYR ' . number_format($depo->total, 2) . '<br>
            <a href="' . route('admin.deposits.pending') . '">Click here</a> to confirm
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close Navigation"></button>
           </div><audio controls autoplay hidden="true"><source src="https://bit.ly/4idA2Vt" type="audio/mp3">
           </audio>';
            return $notif;
        }
}


function getNotifwd()
{
    $today = date('Y-m-d');
    $wds = Transaction::where('transaksi', 'Withdraw')->whereDate('created_at', $today)->where('status', 'Pending')->limit(1)->get();

    foreach ($wds as $wd)

        if (!empty($wd)) {
            $notif = '<div class="mt-5 alert alert-danger alert-dismissible text-dark" role="alert">
            Withdraw request from ' . $wd->username . ' amount MYR ' . number_format($wd->total, 2) . '<br>
            <a href="' . route('admin.withdrawal.pending') . '">Click here</a> to confirm
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close Navigation"></button>
        </div><audio controls autoplay hidden="true"><source src="https://bit.ly/4idA2Vt" type="audio/mp3">
           </audio>';
            return $notif;
        }
}


function flags($code)
{
    $floating = DB::table('tb_api')->where('code', $code)->first();
    return $floating;
}

function generateRandomRTP()
{
    $minRTP = 87;
    $maxRTP = 97;
    $randomRTP = rand($minRTP, $maxRTP);

    return $randomRTP;
}

function random_string($length)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function contact()
{
    $general = DB::table('tb_contact')->find(1);
    return $general;
}

function getReff($length = 5)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return 'GCR' . $randomString;
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function getDay()
{
    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $dayIndex = date('w');
    return $days[$dayIndex];
}

// Fungsi untuk menghasilkan nomor lotto secara acak
function generateLottoNumber()
{
    return rand(1000, 9999);
}

function lang($text, $source, $target)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://translate-plus.p.rapidapi.com/translate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'text' => $text,
            'source' => $source,
            'target' => $target
        ]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "x-rapidapi-host: translate-plus.p.rapidapi.com",
            "x-rapidapi-key: ec9330a7b8msh01b0d51e1544a1bp1a19c9jsn31a63d951860"
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
    $result = json_decode($response);
    return $result->translations;
}

function detect($text)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://translate-plus.p.rapidapi.com/language_detect",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'text' => $text
        ]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "x-rapidapi-host: translate-plus.p.rapidapi.com",
            "x-rapidapi-key: ec9330a7b8msh01b0d51e1544a1bp1a19c9jsn31a63d951860"
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
    $result = json_decode($response);
    return $result->language_detection->language;
}

function getError()
{
    return '<div class="forbidden-page">
        <div class="forbidden__holder">
            <div class="img-forbiden">
                <lottie-player src="https://lottie/1f2b3951-24c2-447d-8d28-3ea4e519eda2/raNjh4CJWn.json"
                    background="transparent" speed="1" loop autoplay></lottie-player>
            </div>
            <div class="forbidden-title--md">Website Access Restricted</div>
            <div class="forbidden-title-sm text-danger">Licensed required</div>
            <div class="content">
                <div class="tab-content">
                    <div class="tab-pane active" id="langeg">
                        <div class="forbidden-title"><strong> Dear Valued Customer </strong></div>
                        <div class="forbidden-title-sm">The license you are using is not registered in our system. Please contact our Developer</a> for more information.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

function maskPhone($number)
{
    return substr($number, 0, 4) . '****' . substr($number, -2);
}

function copyrg()
{
    return '<!-- Footer -->

                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , Edited <span class="text-danger"><i class="tf-icons mdi mdi-heart"></i></span>
                                    By
                                    <a href="https://t.me/revify88" target="_blank">Revify</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->';
}


function aes256_encrypt_payload(array $payload, string $secretKey): string
{
    $cipher = 'aes-256-ecb';
    $key = str_pad(substr($secretKey, 0, 32), 32, "\0");
    $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $blockSize = 16;
    $pad = $blockSize - (strlen($jsonPayload) % $blockSize);
    $paddedPayload = $jsonPayload . str_repeat(chr($pad), $pad);
    $encrypted = openssl_encrypt($paddedPayload, $cipher, $key, OPENSSL_RAW_DATA);

    return base64_encode($encrypted);
}
function aes256_decrypt_payload(string $base64Encrypted, string $secretKey): object|string|false
{
    $cipher = 'aes-256-ecb';
    $key = str_pad(substr($secretKey, 0, 32), 32, "\0");

    $ciphertext = base64_decode($base64Encrypted);
    if ($ciphertext === false) {
        return false;
    }

    $decrypted = openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA);
    if ($decrypted === false) {
        return false;
    }

    return $decrypted;
}





function current_millis_timestamp(): string
{
    $timestamp = (string) round(Carbon\Carbon::now()->getTimestampMs());
    return $timestamp;
}

function routeUrl($url)
{
    if (auth()->check()) {
        return $url;
    } else {
        return route('login');
    }
}
