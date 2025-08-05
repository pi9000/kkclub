<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Pasaran;
use App\Models\Taruhan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaruhanController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->accesskey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Key'
            ]);
        } elseif ($request->accesskey != env('APP_SECKEY')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Key'
            ]);
        }

        $transaction = Taruhan::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'sucesss',
            'taruhan' => $transaction
        ]);
    }

    public function create(Request $request)
    {
        if (!$request->accesskey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Key'
            ]);
        } elseif ($request->accesskey != env('APP_SECKEY')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Key'
            ]);
        }

        $pasaran = new Pasaran();
        $pasaran->title = $request->title;
        $pasaran->date = $request->date;
        $pasaran->periode = $request->periode;
        $pasaran->result = $request->result;
        $pasaran->save();

        $result = Result::where('title',$request->title)->first();
        $result->keluaran = $request->result;
        $result->tanggal = $request->date;
        $result->periode = $request->periode;
        $result->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Result Sucesssfully created'
        ]);
    }

    public function approve($id,Request $request)
    {
        if (!$request->accesskey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Key'
            ]);
        } elseif ($request->accesskey != env('APP_SECKEY')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Key'
            ]);
        }

        $taruhan = Taruhan::find($id);
        $taruhan->status = 2;
        $taruhan->save();

        $user = User::find($taruhan->user_id);
        $api = DB::table('api_providers')->first();
        $this->curl_postc("{$api->url}Transfer?apikey={$api->apikey}&signature={$api->secretkey}&username={$user->extplayer}&amount={$taruhan->win}");

        $user->balance = $user->balance + $taruhan->win;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Taruhan Sucesssfully approved'
        ]);
    }

    public function reject($id,Request $request)
    {
        if (!$request->accesskey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Key'
            ]);
        } elseif ($request->accesskey != env('APP_SECKEY')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Key'
            ]);
        }

        $taruhan = Taruhan::find($id);
        $taruhan->status = 3;
        $taruhan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Taruhan Sucesssfully rejected'
        ]);
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
