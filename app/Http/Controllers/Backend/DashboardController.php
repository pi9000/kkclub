<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function _get_me_count(Request $request)
    {
        $req = json_decode(file_get_contents("php://input"), false);
        $count = User::where('agent_id', $req->agent_id)->count();
        return response()->json([
            'code' => 1,
            'count' => $count,
        ]);
    }

    public function _get_latest(Request $request)
    {
        $req = json_decode(file_get_contents("php://input"), false);
        $count_depo = Transaction::where('agent_id', $req->agent_id)->where('transaksi', 'Top Up')->where('status', 'Pending')->count();
        $count_wd = Transaction::where('agent_id', $req->agent_id)->where('transaksi', 'Withdraw')->where('status', 'Pending')->count();
        return response()->json([
            'code' => 1,
            'instant_depo' => $count_depo,
            'instant_wd' => $count_wd,
        ]);
    }

    public function get_member_total_balance(Request $request)
    {
        $req = json_decode(file_get_contents("php://input"), false);
        $total_balance = User::where('agent_id', $req->agent_id)->sum('balance');
        return response()->json([
            'code' => 1,
            'total_balance' => $total_balance,
        ]);
    }

    public function reports(Request $request)
    {
        if ($request->ajax()) {
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $trans = DB::table('reports')->whereBetween('created_at', [$request->from_date, $request->to_date]);
            } else {
                $trans = DB::table('reports')->orderby('created_at', 'desc');
            }

            return DataTables::of($trans)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    $cbtrn = $row->created_at;
                    return $cbtrn;
                })
                ->addColumn('members', function ($row) {
                    $total_dp = number_format($row->members);
                    return $total_dp;
                })
                ->addColumn('deposit', function ($row) {
                    $nominal_depo = number_format($row->deposit);
                    return $nominal_depo;
                })
                ->addColumn('withdraw', function ($row) {
                    $total_wd = number_format($row->withdraw);
                    return $total_wd;
                })
                ->addColumn('deposit_success', function ($row) {
                    $nominal_wd = 'MYR ' . number_format($row->deposit_success);
                    return $nominal_wd;
                })
                ->addColumn('withdraw_success', function ($row) {
                    $nominal_bonus = 'MYR ' . number_format($row->withdraw_success);
                    return $nominal_bonus;
                })
                ->addColumn('bonus', function ($row) {
                    $nominal_bonus = 'MYR ' . number_format($row->bonus);
                    return $nominal_bonus;
                })
                ->addColumn('profit', function ($row) {
                    $total_profit = 'MYR ' . number_format($row->profit);
                    return $total_profit;
                })
                ->addColumn('created_at', function ($row) {
                    $total_profit = $row->updated_at;
                    return $total_profit;
                })
                ->rawColumns(['created_at', 'members', 'deposit', 'withdraw', 'deposit_success', 'withdraw_success', 'bonus', 'profit', 'created_at'])
                ->make(true);
        }

        return view('backend.reports');
    }

    public function balance()
    {
        $api = DB::table('api_actives')->find(1);
        $apis = DB::table('api_providers')->find($api->provider_id);

        if ($api->provider_id == 1) {
            $endpoint = $apis->url;
            $postArray = [
                'method' => $apis->apikey,
                'agent_code' => $apis->apikey,
                'agent_token' => $apis->secretkey
            ];
        } else {
            $endpoint = $apis->url;
            $postArray = [
                'method' => 'money_info',
                'agent_code' => $apis->apikey,
                'agent_token' => $apis->secretkey
            ];
        }

        $jsonData = json_encode($postArray);

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
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);

        $balance = number_format($result->agent->balance, 2);
        return response()->json(['balance' => $balance]);
    }

    public function test_gateway()
    {

        $endpoint = "https://api.doitwallet.asia/merchant/reqfpxStatus";

        $merchant = "60-00000657-31340117";
        $apikey = "2D6CCE8B-8886-44C3-B5C5-6C1B253B7EC6";
        $secretkey = "52789B42FCAD45CC8DFA6945C04757AC";
        $amount = "35.00";
        $serialno = "AAAB9625s4";

        $token = md5($serialno . $apikey . $secretkey);

        $postArray = [
            'merchantCode' => $merchant,
            'serialNo' => $serialno,
            'token' => $token
        ];

        $jsonData = json_encode($postArray);

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

    public function cron()
    {
        $check = DB::table('reports')->whereDate('created_at', now())->first();
        $members = User::whereDate('created_at', now())->count();
        $deposit = User::where('deposit', 1)->whereDate('created_at', now())->count();
        $no_deposit = User::where('deposit', 0)->whereDate('created_at', now())->count();
        $withdraw = User::where('withdraw', 1)->whereDate('created_at', now())->count();
        $deposit_success = Transaction::where('transaksi', 'Top Up')->where('status', 'Sukses')->whereDate('created_at', now())->sum('total');
        $depo_reject = Transaction::where('transaksi', 'Top Up')->where('status', 'Ditolak')->whereDate('created_at', now())->count();
        $withdraw_success = Transaction::where('transaksi', 'Withdraw')->where('status', 'Sukses')->whereDate('created_at', now())->sum('total');
        $wd_reject = Transaction::where('transaksi', 'Withdraw')->where('status', 'Ditolak')->whereDate('created_at', now())->sum('total');
        $bonus = Transaction::where('transaksi', 'Top Up')->where('status', 'Sukses')->whereDate('created_at', now())->sum('bonus_amount');
        $profit = $deposit_success - $withdraw_success;

        if (!$check) {
            DB::table('reports')->insert([
                'members' => $members,
                'deposit' => $deposit,
                'no_deposit' => $no_deposit,
                'withdraw' => $withdraw,
                'deposit_success' => $deposit_success,
                'withdraw_success' => $withdraw_success,
                'bonus' => $bonus,
                'depo_reject' => $depo_reject,
                'wd_reject' => $wd_reject,
                'profit' => $profit,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        } else {
            DB::table('reports')->whereDate('created_at', now())->update([
                'members' => $members,
                'deposit' => $deposit,
                'no_deposit' => $no_deposit,
                'withdraw' => $withdraw,
                'deposit_success' => $deposit_success,
                'withdraw_success' => $withdraw_success,
                'bonus' => $bonus,
                'depo_reject' => $depo_reject,
                'wd_reject' => $wd_reject,
                'profit' => $profit,
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }

        return response()->json(['status' => 'Success']);
    }
}
