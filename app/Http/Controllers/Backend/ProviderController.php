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
    public function index(Request $request)
    {
        $apiss = ApiProvider::orderBy('status', 'DESC')->get();
        $api = ApiActive::first();

        return view('backend.api', compact('apiss', 'api'));
    }

    public function call(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->call_players();
        $api = ApiActive::first();

        return view('backend.call', compact('playing'));
    }

    public function call_players(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->call_players();

        return $playing;
    }

    public function call_list(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->call_list($request->providerCode, $request->gameCode);

        return $playing;
    }

    public function call_apply(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->call_apply($request->providerCode, $request->gameCode, $request->userCode, $request->callRtp, $request->callType);

        return $playing;
    }

    public function rtp(Request $request)
    {
        $api = new SeamlesWsController();
        $playing = $api->control_rtp($request->providerCode, $request->userCode, $request->rtp);

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
        return view('backend.provider.list', compact('provider'));
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
            return back()->with('success', 'Provider updated successfully.');
        }

        return back()->with('error', 'Provider not found.');
    }

    public function gamelists($id, Request $request)
    {
        $provider = Provider::find($id);
        if (!$provider) {
            return back()->with('error', 'Provider not found');
        }


        if ($request->ajax()) {
            $user = GameList::where('Provider', $provider->provider)->where('provider_id', $provider->provider_id)->orderBy('sequence', 'ASC');
            return DataTables::of($user)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    $cbtrn = $row->created_at;
                    return $cbtrn;
                })
                ->addColumn('provider_api', function ($row) {
                    $cbtrn = api_providers($row->provider_id);
                    return $cbtrn;
                })
                ->addColumn('Game_image', function ($row) {
                    $statusbtn = '<img src="' . $row->Game_image . '" class="img-fluid" style="max-width: 100px; max-height: 100px;">';
                    return $statusbtn;
                })
                ->addColumn('action', function ($row) {
                    $action = '<button type="button" class="btn btn-xs btn-primary" onclick="handleCallModal(`' . $row->id . '`,`' . $row->Provider . '`,`' . $row->GameName . '`,`' . $row->GameCode . '`,`' . $row->Game_image . '`,`' . $row->sequence . '`)">
                                            <span class="mdi mdi-pencil">
                                        </button>';
                    return $action;
                })
                ->rawColumns(['created_at', 'provider_api', 'Game_image', 'action'])
                ->make(true);
        }
        return view('backend.provider.game');
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

        $games->Game_image = $request->Game_image;
        $games->sequence = $request->sequence;
        $games->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Games ' . $games->GameName . ' Successfully updated'
        ]);
    }
}
