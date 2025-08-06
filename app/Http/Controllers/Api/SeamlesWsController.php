<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GameHistory;
use Illuminate\Support\Facades\DB;

class SeamlesWsController extends Controller
{
    public function getBalance2()
    {
        return response()->json([
            'status' => true,
            'msg' => 'true',
            'balance' => number_format(auth()->user()->balance, 2),
            'tt_amount' => number_format(auth()->user()->balance, 2),
        ]);
    }

    public function call_players($agent_id)
    {
        $api = DB::table('api_providers')->where('agent_id', $agent_id)->where('provider','=','NexusGGR')->first();
        $params = [
            'method' => 'call_players',
            'agent_code' => $api->apikey,
            'agent_token' => $api->secretkey,
        ];

        $result = json_decode($this->curl_postc($params,$api->url));
        return $result;
    }

    public function call_list($agent_id,$provider_code, $game_code)
    {
        $api = DB::table('api_providers')->where('agent_id', $agent_id)->where('provider','=','NexusGGR')->first();
        $params = [
            'method' => 'call_list',
            'agent_code' => $api->apikey,
            'agent_token' => $api->secretkey,
            'provider_code' => $provider_code,
            'game_code' => $game_code
        ];
        $result = $this->curl_postc($params, $api->url);
        return $result;
    }

    public function call_apply($agent_id,$provider_code, $game_code, $user_code, $call_rtp, $call_type)
    {
        $api = DB::table('api_providers')->where('agent_id', $agent_id)->where('provider','=','NexusGGR')->first();
        $params = [
            'method' => 'call_apply',
            'agent_code' => $api->apikey,
            'agent_token' => $api->secretkey,
            'provider_code' => $provider_code,
            'game_code' => $game_code,
            'user_code' => $user_code,
            'call_rtp' => $call_rtp,
            'call_type' => $call_type
        ];
        $result = json_decode($this->curl_postc($params,$api->url));
        return $result;
    }

    public function call_cancel($id,$agent_id)
    {
        $api = DB::table('api_providers')->where('agent_id', $agent_id)->where('provider','=','NexusGGR')->first();
        $params = [
            'method' => 'call_cancel',
            'agent_code' => $api->apikey,
            'agent_token' => $api->secretkey,
            'call_id' => $id
        ];
        $result = json_decode($this->curl_postc($params,$api->url));
        return $result;
    }

    public function call_history($agent_id)
    {
        $api = DB::table('api_providers')->where('agent_id', general()->agent_id)->where('provider','=','NexusGGR')->first();
        $params = [
            'method' => 'call_history',
            'agent_code' => $api->apikey,
            'agent_token' => $api->secretkey,
            'offset' => 0,
            'limit' => 1000
        ];
        $result = json_decode($this->curl_postc($params,$api->url));
        return $result;
    }

    public function control_rtp($agent_id,$provider_code, $user_code, $rtp)
    {
        $api = DB::table('api_providers')->where('agent_id', $$agent_id)->where('provider','=','NexusGGR')->first();
        $params = [
            'method' => 'control_rtp',
            'agent_code' => $api->apikey,
            'agent_token' => $api->secretkey,
            'provider_code' => $provider_code,
            'user_code' => $user_code,
            'rtp' => $rtp
        ];
        $result = json_decode($this->curl_postc($params,$api->url));
        return $result;
    }

    public function testapi(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://gateway.bet4wins.org/api/ip',
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

        $result = json_decode($response);

        $results = str_replace("Your IP address is ", "", $result->message);
        $ip = rtrim(str_replace("'", "", $results), '.');

        return response()->json([
            'status' => '1',
            'ip' => $ip
        ]);
    }

    function curl_postc($endpoint,$url)
    {
        $jsonData = json_encode($endpoint, JSON_NUMERIC_CHECK);

        $headerArray = ['Content-Type: application/json'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => $headerArray,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public function create_pay($amount, $serialno)
    {
        $token = md5($serialno . general()->gateway_apikey . general()->gateway_secretkey . $amount);

        $postArray = [
            'merchantCode' => general()->gateway_merchant,
            'serialNo' => $serialno,
            'currency' => 'MYR',
            'amount' => $amount,
            'returnUrl' => url('/transaksi'),
            'notifyUrl' => url('transaksi/gateway/callback'),
            'token' => $token
        ];

        $jsonData = json_encode($postArray);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => general()->gateway_endpoint . 'merchant/reqfpx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
        return $result;
    }

    public function check_pay($serialno)
    {
        $token = md5($serialno . general()->gateway_apikey . general()->gateway_secretkey);

        $postArray = [
            'merchantCode' => general()->gateway_merchant,
            'serialNo' => $serialno,
            'token' => $token
        ];

        $jsonData = json_encode($postArray);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => general()->gateway_endpoint . 'merchant/reqfpxStatus',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
        return $result;
    }

    public function user_list(Request $request)
    {
        $user = User::orderBy('created_at', 'desc')->get(['username', 'extplayer']);
        return response()->json([
            'status' => 1,
            'data' => $user
        ]);
    }

    public function luckywheel(Request $request)
    {
        $user = User::where('username', $request->username)->where('agent_id', general()->agent_id)->first();
        if (empty($user)) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid username'
            ]);
        }

        $user->balance = $user->balance + $request->amount;

        DB::table('trans_balls')->insert([
            'user_id' => $user->id,
            'username' => $user->username,
            'amount' => $request->amount,
            'type' => 3,
            'operator' => 'LuckyWheel',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        $user->save();

        return response()->json([
            'status' => 1,
            'message' => 'Success'
        ]);
    }

    public function huidu_launch_game($user_code, $game_code, $balance)
    {
        $api = DB::table('api_providers')->where('agent_id', general()->agent_id)->where('provider','!=','NexusGGR')->first();
        $hash = md5($api->apikey . $api->secretkey);
        $params = [
            "secureLogin" => $api->apikey,
            "externalPlayerId" => $user_code,
            "game_code" => $game_code,
            "balance" => $balance,
            "home_url" => url('/'),
            "hash" => $hash,
        ];
        $result = json_decode($this->curl_post_huidu($params));
        return $result;
    }

    public function huidu_callback(Request $request)
    {
        $decrypted = json_decode(file_get_contents("php://input"), false);

        $member_account = $decrypted->member_account;

        $user = User::where('extplayer', $member_account)->first();

        if (empty($user)) {
            return response()->json(['code' => 1, 'msg' => 'User not found', 'payload' => ''], 200);
        }

        $bofere_balance = $user->balance;
        $balance = $user->balance - $decrypted->bet_amount + $decrypted->win_amount;

        $user->balance = $balance;
        $user->save();

        $getGame = DB::table('game_lists')->where('GameCode', $decrypted->game_uid)->where('provider_id', 2)->first();

        $game_history = new GameHistory();
        $game_history->api_id = 2;
        $game_history->agent_id = $user->agent_id;
        $game_history->username = $user->username;
        $game_history->extplayer = $user->extplayer;
        $game_history->trx_id = $decrypted->trx_id;
        $game_history->provider = $getGame ? $getGame->Provider : 'Unknown Provider';
        $game_history->game_name = $getGame ? $getGame->GameName : 'Unknown Game';
        $game_history->game_type = $getGame ? $getGame->Category : 'Unknown Game';
        $game_history->game_icon = $getGame ? $getGame->Game_image : 'Unknown Icon';
        $game_history->game_code = $decrypted->game_uid;
        $game_history->bet_amount = $decrypted->bet_amount;
        $game_history->win_amount = $decrypted->win_amount;
        $game_history->before_balance = $bofere_balance;
        $game_history->after_balance = $balance;
        $game_history->note = 'debit_credit';
        $game_history->save();

        $timestamp = current_millis_timestamp();

        return response()->json([
            'code' => 0,
            'msg' => '',
            'bofere_balance' => $user->balance,
            'credit_amount' => $balance,
            'timestamp' => $timestamp,
        ], 200);
    }

    function curl_post_huidu($payloads)
    {
        $api = DB::table('api_providers')->find(2);
        $jsonData = json_encode($payloads);

        $headerArray = ['Content-Type: application/json'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api->url . '/api/huidu/launch_game',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => $headerArray,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public function gold_api(Request $request)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $methode = $data['method'] ?? '';

        switch ($methode) {
            case 'transaction':
                return $this->gameCallback($data);
                break;
            case 'user_balance':
                return $this->getUserBalance($data);
                break;
            default:
                return response()->json(['status' => 0, 'msg' => 'INTERNAL_ERROR']);
        }
    }

    function getUserBalance($req)
    {
        $user_code = $req["user_code"];
        $user = User::where('extplayer', $user_code)->first();
        if (empty($user)) {
            return response()->json([
                "status" => 0,
                "msg" => "INVALID_USER"
            ]);
        }

        if ($user->balance <= 0) {
            return response()->json([
                "status" => 0,
                "msg" => "INSUFFICIENT_USER_FUNDS",
                "user_balance" => 0
            ]);
        }

        return response()->json([
            "status" => 1,
            "user_balance" => $user->balance
        ]);
    }

    function gameCallback($req)
    {
        $user_code = $req["user_code"];
        $game_type = $req["game_type"];

        $user = User::where('extplayer', $user_code)->first();
        if (empty($user)) {
            return response()->json([
                "status" => 0,
                "msg" => "INVALID_USER"
            ]);
        }

        $balance = $user->balance;

        if ($game_type == "slot") {
            $txn_id = $req["slot"]["txn_id"];
            $txn_type = $req["slot"]["txn_type"];
            $bet = $req["slot"]["bet_money"];
            $win = $req["slot"]["win_money"];
            $provider_code = $req["slot"]["provider_code"];
            $game_code = $req["slot"]["game_code"];
            $type = $req["slot"]["type"];
        } else {
            $txn_id = $req["live"]["txn_id"];
            $txn_type = $req["live"]["txn_type"];
            $bet = $req["live"]["bet_money"];
            $win = $req["live"]["win_money"];
            $provider_code = $req["live"]["provider_code"];
            $game_code = $req["live"]["game_code"];
            $type = $req["live"]["type"];
        }

        $result_balance = $balance - $bet + $win;

        $user->balance = $result_balance;
        $user->save();

        $getGame = DB::table('game_lists')->where('GameCode', $game_code)->where('ProviderCode', $provider_code)->where('provider_id', 1)->first();

        $game_history = new GameHistory();
        $game_history->api_id = 1;
        $game_history->agent_id = $user->agent_id;
        $game_history->username = $user->username;
        $game_history->extplayer = $user->extplayer;
        $game_history->trx_id = $txn_id;
        $game_history->provider = $getGame ? $getGame->Provider : 'Unknown Provider';
        $game_history->game_name = $getGame ? $getGame->GameName : 'Unknown Game';
        $game_history->game_type = $game_type ? $game_type : 'Unknown Game';
        $game_history->game_icon = $getGame ? $getGame->Game_image : 'Unknown Icon';
        $game_history->game_code = $game_code;
        $game_history->bet_amount = $bet;
        $game_history->win_amount = $win;
        $game_history->before_balance = $balance;
        $game_history->after_balance = $result_balance;
        $game_history->note = $txn_type;
        $game_history->save();

        return response()->json([
            "status" => 1,
            "user_balance" => $result_balance
        ]);
    }

    public function huidu_api(Request $request)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $methode = $data['method'] ?? '';

        switch ($methode) {
            case 'transaction':
                return $this->gameCallback_huidu($data);
                break;
            case 'user_balance':
                return $this->getUserBalance_huidu($data);
                break;
            default:
                return response()->json(['status' => 0, 'msg' => 'INTERNAL_ERROR']);
        }
    }

    function getUserBalance_huidu($req)
    {
        $user_code = $req["user_code"];
        $user = User::where('extplayer', $user_code)->first();
        if (empty($user)) {
            return response()->json([
                "status" => 0,
                "msg" => "INVALID_USER"
            ]);
        }

        if ($user->balance <= 0) {
            return response()->json([
                "status" => 0,
                "msg" => "INSUFFICIENT_USER_FUNDS",
                "user_balance" => 0
            ]);
        }

        return response()->json([
            "status" => 1,
            "user_balance" => $user->balance
        ]);
    }

    function gameCallback_huidu($req)
    {
        $user_code = $req["user_code"];

        $user = User::where('extplayer', $user_code)->first();
        if (empty($user)) {
            return response()->json([
                "status" => 0,
                "msg" => "INVALID_USER"
            ]);
        }

        $balance = $user->balance;

        $txn_id = $req["trx_id"];
        $bet = $req["bet"];
        $win = $req["win"];
        $game_code = $req["game_code"];

        if ($balance < $bet) {
            return response()->json([
                "status" => 0,
                "msg" => "INSUFFICIENT_USER_FUNDS"
            ]);
        }

        $result_balance = $balance - $bet + $win;

        $user->balance = $result_balance;
        $user->save();

        $getGame = DB::table('game_lists')->where('GameCode', $game_code)->where('provider_id', 2)->first();

        $game_history = new GameHistory();
        $game_history->api_id = 1;
        $game_history->agent_id = $user->agent_id;
        $game_history->username = $user->username;
        $game_history->extplayer = $user->extplayer;
        $game_history->trx_id = $txn_id;
        $game_history->provider = $getGame ? $getGame->Provider : 'Unknown Provider';
        $game_history->game_name = $getGame ? $getGame->GameName : 'Unknown Game';
        $game_history->game_type = $getGame ? $getGame->Category : 'Unknown Game';
        $game_history->game_icon = $getGame ? $getGame->Game_image : 'Unknown Icon';
        $game_history->game_code = $game_code;
        $game_history->bet_amount = $bet;
        $game_history->win_amount = $win;
        $game_history->before_balance = $balance;
        $game_history->after_balance = $result_balance;
        $game_history->note = 'debit_credit';
        $game_history->save();

        return response()->json([
            "status" => 1,
            "user_balance" => $result_balance
        ]);
    }
}
