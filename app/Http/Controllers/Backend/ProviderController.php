<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiActive;
use App\Models\ApiProvider;
use App\Models\Provider;
use App\Models\GameList;
use App\Http\Controllers\Api\SeamlesWsController;
use Yajra\DataTables\Facades\DataTables;

class ProviderController extends Controller
{

    public function call_players(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->call_players($request->agent_id);

        return $playing;
    }

    public function call_list(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->call_list($request->agent_id, $request->providerCode, $request->gameCode);

        return $playing;
    }

    public function call_apply(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->call_apply($request->agent_id, $request->providerCode, $request->gameCode, $request->userCode, $request->callRtp, $request->callType);

        return $playing;
    }

    public function call_rtp(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->control_rtp($request->agent_id, $request->providerCode, $request->userCode, $request->rtp);

        return $playing;
    }

    public function edit($id, Request $request)
    {
        $apiss = ApiProvider::find($id);
        $apiss->apikey = $request->apikey;
        $apiss->secretkey = $request->secretkey;
        $apiss->agentcode = $request->agentcode;
        $apiss->token = $request->agentcode;
        $apiss->url = $request->endpoint;
        $apiss->save();

        return back()->with('success', 'API Successfully update');
    }

    public function use($id, Request $request)
    {
        $apiss = ApiProvider::find($id);
        if (!$apiss) {
            return back()->with('error', 'API not found');
        }
        $api = ApiActive::first();
        $api->provider_id = $apiss->id;
        $api->title = $apiss->provider;
        $api->save();

        $apiss->status = 1;
        $apiss->save();

        $apiss2 = ApiProvider::find($id);
        $apiss2->status = 0;
        $apiss2->save();

        return back()->with('success', 'API Successfully Used');
    }

    public function provider_list(Request $request)
    {
        $provider = Provider::orderBy('status', 'DESC')->get();
        return response()->json([
            'status' => 'success',
            'data' => $provider
        ]);
    }

    public function delete_provider($id, Request $request)
    {
        $provider = Provider::find($id);
        $provider->delete();

        return back()->with('success', 'Provider Successfully deleted');
    }

    public function update_provider(Request $request)
    {
        $provider = Provider::find($request->id);
        if ($provider) {
            $provider->icon = $request->icon;
            $provider->banner = $request->banner;
            $provider->status = $request->status;
            $provider->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Provider updated successfully.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Provider not found'
        ], 200);
    }

    public function gamelists($id, Request $request)
    {
        $provider = Provider::find($id);
        if (!$provider) {
            return response()->json([
                'status' => 'error',
                'message' => 'Provider not found'
            ], 200);
        }
        $data = GameList::where('Provider', $provider->provider)->where('provider_id', $provider->provider_id)->orderBy('sequence', 'ASC')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function update_games(Request $request)
    {
        $games = GameList::find($request->id);
        if (empty($games)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Games not found'
            ]);
        }

        $games->Game_image = $request->game_image;
        $games->sequence = $request->sequence;
        $games->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Games ' . $games->GameName . ' Successfully updated'
        ]);
    }
}
