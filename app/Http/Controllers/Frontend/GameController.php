<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Provider;
use App\Models\Result;
use App\Http\Controllers\Api\SeamlesWsController;

class GameController extends Controller
{

    public function slot(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        $pageTitle = 'Slot';
        $provider = Provider::where('type', 'slot')->where('provider_code', 1)->where('status', 1)->get();
        return view('frontend.games.games', compact('provider', 'pageTitle'));
    }

    public function casino(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        $pageTitle = 'Casino';
        $provider = Provider::where('type', 'casino')->where('provider_code', 2)->where('status', 1)->get();
        return view('frontend.games.games', compact('provider', 'pageTitle'));
    }

    public function sportsbook(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        $pageTitle = 'Sportsbook';
        $provider = Provider::where('type', 'sportsbook')->where('provider_code', 3)->where('status', 1)->get();
        return view('frontend.games.games', compact('provider', 'pageTitle'));
    }

    public function arcade(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        $pageTitle = 'Arcade';
        $provider = Provider::where('type', 'arcade')->where('provider_code', 8)->where('status', 1)->get();
        return view('frontend.games.games', compact('provider', 'pageTitle'));
    }

    public function other(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        $pageTitle = 'Arcade';
        $provider = Provider::where('type', 'other')->where('provider_code', 9)->where('status', 1)->get();
        return view('frontend.games.games', compact('provider', 'pageTitle'));
    }


    public function game_list_click($id, $slug)
    {
        $provider = Provider::where('id', $id)->where('slug', $slug)->first();
        if ($provider->GameType == 11) {
            return response()->json([
                'success' => true,
                'msg' => 'User Credential',
                'credential' => [
                    'username' => getCredentialUsername($provider->slug),
                    'password' => getCredentialPassword($provider->slug),
                ],
                'gameinfo' => [
                    'img' => $provider->banner,
                    'short_name' => $provider->slug,
                    'name' => $provider->provider,
                    'download_link' => $provider->download_link,
                ]
            ]);
        } else if ($provider->GameCode != null) {
            $url = url('gameIframe?gameType=' . $provider->GameType . '&providerCode=' . $provider->ProviderCode . '&gameCode=' . $provider->GameCode . '&provider_id=' . $provider->provider_id);
            return response()->json([
                'success' => true,
                'url' => $url,
                'msg' => 'Success',
            ]);
        } else {
            $url = url('show_game_list/' . $id . '/' . $slug);
            return response()->json([
                'success' => true,
                'url' => $url,
                'msg' => 'Game List',
            ]);
        }
    }

    public function show_game_list($id, $slug)
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        $provider = Provider::where('id', $id)->where('slug', $slug)->first();
        $games = DB::table('game_lists')->where('Provider', $provider->provider)->where('GameType', 1)->where('provider_id', $provider->provider_id)->orderBy('sequence', 'ASC')->get();
        return view('frontend.games.list', compact('provider', 'games'));
    }

    public function launch_game(Request $request)
    {
        if (auth()->user()->status_game == 0) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Your account has been locked from accessing game. Please contact customer support for more information.',
            ]);
        }
        $extplayer = auth()->user()->extplayer;

        if ($request->provider_id != 2) {
            $api = DB::table('api_providers')->where('agent_id', general()->agent_id)->where('provider','=','NexusGGR')->first();

            if ($request->gameType == 1) {
                $params = [
                    'method' => 'game_launch',
                    'agent_code' => $api->apikey,
                    'agent_token' => $api->secretkey,
                    'user_code' => $extplayer,
                    'provider_code' => $request->providerCode,
                    'game_code' => $request->gameCode,
                    'lang' => 'en'
                ];
            } else {
                $params = [
                    'method' => 'game_launch',
                    'agent_code' => $api->apikey,
                    'agent_token' => $api->secretkey,
                    'user_code' => $extplayer,
                    'provider_code' => $request->providerCode,
                    'game_code' => '',
                    'lang' => 'en'
                ];
            }

            $jsonData = json_encode($params);

            $headerArray = ['Content-Type: application/json'];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $api->url,
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

            $result = json_decode($response);

            if ($result->status == 1) {
                return response()->json([
                    'status' => 'success',
                    'url' => $result->launch_url,
                    'msg' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('public.maintenance'),
                ]);
            }
        } else {
            $user_code = auth()->user()->extplayer;
            $game_code = $request->gameCode;
            $balance = auth()->user()->balance;

            $seamlesWsController = new SeamlesWsController();
            $result = $seamlesWsController->huidu_launch_game($user_code, $game_code, $balance);

            if ($result->code == 0) {
                return response()->json([
                    'status' => 'success',
                    'url' => $result->payload->game_launch_url,
                    'msg' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'msg' => $result->msg,
                ]);
            }
        }
    }

    public function launch_games(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        if (auth()->user()->status_game == 0) {
            return back()->with('error', 'Your account has been locked from accessing game. Please contact customer support for more information.');
        }

        $extplayer = auth()->user()->extplayer;

        if ($request->provider_id != 2) {
            $api = DB::table('api_providers')->where('agent_id', general()->agent_id)->where('provider','=','NexusGGR')->first();

            if ($request->gameType == 1) {
                $params = [
                    'method' => 'game_launch',
                    'agent_code' => $api->apikey,
                    'agent_token' => $api->secretkey,
                    'user_code' => $extplayer,
                    'provider_code' => $request->providerCode,
                    'game_code' => $request->gameCode,
                    'lang' => 'en'
                ];
            } else {
                $params = [
                    'method' => 'game_launch',
                    'agent_code' => $api->apikey,
                    'agent_token' => $api->secretkey,
                    'user_code' => $extplayer,
                    'provider_code' => $request->providerCode,
                    'game_code' => '',
                    'lang' => 'en'
                ];
            }

            $jsonData = json_encode($params);

            $headerArray = ['Content-Type: application/json'];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $api->url,
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

            $result = json_decode($response);

            if ($result->status == 1) {
                return redirect($result->launch_url);
            } else {
                return back()->with('error', __('public.maintenance'));
            }
        } else {
            $user_code = auth()->user()->extplayer;
            $game_code = $request->gameCode;
            $balance = auth()->user()->balance;

            $seamlesWsController = new SeamlesWsController();
            $result = $seamlesWsController->huidu_launch_game($user_code, $game_code, $balance);

            if ($result->code == 0) {
                return redirect($result->payload->game_launch_url);
            } else {
                return redirect()->back()->with('error', $result->msg);
            }
        }
    }
}
