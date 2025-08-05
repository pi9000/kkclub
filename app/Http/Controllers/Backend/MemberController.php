<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bank;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaction;
use App\Models\Refferal;
use Illuminate\Support\Facades\DB;
use App\Models\GameHistory;
use App\Http\Controllers\VerificationController;
use Carbon\Carbon;
use App\Http\Controllers\Api\SeamlesWsController;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{

    public function account_listing(Request $request)
    {
        $req = json_decode(file_get_contents("php://input"), false);

        if (!$req || !isset($req->agent_id)) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $query = User::where('agent_id', $req->agent_id);

        switch ($req->form_select) {
            case 'user_name':
                if (!empty($req->user_name)) {
                    $query->where('username', 'like', '%' . $req->user_name . '%');
                }
                break;
            case 'player_remark':
                if (!empty($req->player_remark)) {
                    $query->where('remark', 'like', '%' . $req->player_remark . '%');
                }
                break;
            case 'user_mobile':
                if (!empty($req->user_mobile)) {
                    $query->where('no_hp', 'like', '%' . $req->user_mobile . '%');
                }
                break;
            case 'user_account_number':
                if (!empty($req->user_account_number)) {
                    $query->where('nomor_rekening', 'like', '%' . $req->user_account_number . '%');
                }
                break;
            case 'user_bank_name':
                if (!empty($req->user_bank_name)) {
                    $query->where('nama_bank', 'like', '%' . $req->user_bank_name . '%');
                }
                break;
            case 'Member Listing':
                if (!empty($req->created_at)) {
                    $range = explode(' - ', $req->created_at);
                    if (count($range) === 2) {
                        try {
                            $start = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', trim($range[0]))->startOfSecond();
                            $end = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', trim($range[1]))->endOfSecond();
                            $query->whereBetween('created_at', [$start, $end]);
                        } catch (\Exception $e) {
                            // format salah
                        }
                    }
                }
                break;
        }

        if (isset($req->status)) {
            switch ((int)$req->status) {
                case 1:
                    $query->where('status', 1);
                    break;
                case 2:
                    $query->where('status', 0);
                    break;
                case 3:
                    $query->where('game_status', 1);
                    break;
                case 4:
                    $query->where('game_status', 0);
                    break;
                case 5:
                    $query->where('balance', '>', 0);
                    break;
                case 6:
                    $query->where('balance', '<=', 0);
                    break;
                case 7:
                    $query->where('deposit', 1);
                    break;
                case 8:
                    $query->where('deposit', 0);
                    break;
            }
        }

        $sortColumn = $request->sort_column === '#' ? 'id' : ($request->sort_column ?? 'id');
        $sortOrder = $request->sorting ?? 'desc';
        $query->orderBy($sortColumn, $sortOrder);

        $perPage = $request->rows ?? 50;
        $page = $request->page ?? 1;

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $results->items(),
            'total' => $results->total(),
            'page' => $results->currentPage(),
        ]);
    }

    public function new_member(Request $request)
    {
        $check = User::where('username', $request->username)->first();
        if ($check) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username already exists'
            ]);
        }

        if ($request->verified == 0) {
            $otp = new VerificationController();
            $username = preg_replace("/[^a-zA-Z0-9]+/", "", $request->username);
            $user = User::create([
                'agent_id' => $request->agent_id,
                'extplayer' => strtoupper($request->agent_id . random_string(5)),
                'username' => strtolower($username),
                'password' => Hash::make($request->password),
                'nama_lengkap' => '-',
                'no_hp' => $request->phone,
                'level' => 'user',
                'balance' => 0,
                'refferal' => $request->ref_id,
                'status' => 1,
                'status_game' => 1,
                'nama_bank' => '-',
                'nomor_rekening' => '-',
                'nama_pemilik' => '-',
                'pinId' => $otp->sendsms('60' . $request->phone),
                'pinReload' => 0,
                'remark' => $request->remark,
                'verified' => $request->verified,
            ]);
        } else {
            $username = preg_replace("/[^a-zA-Z0-9]+/", "", $request->username);
            $user = User::create([
                'agent_id' => $request->agent_id,
                'extplayer' => strtoupper($request->agent_id . random_string(5)),
                'username' => strtolower($username),
                'password' => Hash::make($request->password),
                'nama_lengkap' => '-',
                'no_hp' => $request->phone,
                'level' => 'user',
                'balance' => 0,
                'refferal' => $request->ref_id,
                'status' => 1,
                'status_game' => 1,
                'nama_bank' => '-',
                'nomor_rekening' => '-',
                'nama_pemilik' => '-',
                'pinId' => 0,
                'pinReload' => 0,
                'remark' => $request->remark,
                'verified' => $request->verified,
            ]);
        }

        $reffs = new Refferal();
        $reffs->user_id = $user->id;
        $reffs->reff_code = getReff();
        $reffs->upline = $request->ref_id;
        $reffs->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User ' . $user->username . ' Successfully created',
            'extplayer' => $user->extplayer,
        ]);
    }

    public function balance()
    {
        $user = User::all();
        return view('backend.member.balance', compact('user'));
    }

    public function member_details($extplayer, Request $request)
    {
        $user = User::where('extplayer', $extplayer)->first();
        if (empty($user)) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid Member',
            ]);
        }
        $reffs = Refferal::where('user_id', $user->id)->first();
        $reff = Refferal::where('upline', $reffs->reff_code)->with('User')->get();
        $reffc = Refferal::where('upline', $reffs->reff_code)->count();
        $transaction = Transaction::where('id_user', $user->id)->get();
        $ball = DB::table('trans_balls')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $banks = DB::table('bank_lists')->get();
        $game_history = GameHistory::where('extplayer', $extplayer)->get();

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'user' => $user,
            'reffs' => $reffs,
            'reff' => $reff,
            'reff_count' => $reffc,
            'transaction' => $transaction,
            'banks' => $banks,
            'ball' => $ball,
            'game_history' => $game_history,
            'game_history_win' => $game_history->sum('win_amount'),
            'game_history_bet' => $game_history->sum('bet_amount'),
        ]);
    }

    public function member_details_balance($extplayer, Request $request)
    {
        $user = User::where('extplayer', $extplayer)->first();
        if (empty($user)) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid Member',
            ]);
        }

        $transaction = Transaction::where('id_user', $user->id)->where('transaksi', 'Top Up')->where('status', 'Sukses')->sum('total');
        $withdraw = Transaction::where('id_user', $user->id)->where('transaksi', 'Withdraw')->where('status', 'Sukses')->sum('total');

        $month = date('Y-m');
        $bet_m = GameHistory::where('extplayer', $extplayer)
            ->where('created_at', 'like', $month . '%')
            ->sum('bet_amount');

        $win_m = GameHistory::where('extplayer', $extplayer)
            ->where('created_at', 'like', $month . '%')
            ->sum('win_amount');
        $profit = $win_m - $bet_m;
        $transaction_m = Transaction::where('id_user', $user->id)->where('transaksi', 'Top Up')->where('status', 'Sukses')->where('created_at', 'like', $month . '%')->sum('total');
        $withdraw_m = Transaction::where('id_user', $user->id)->where('transaksi', 'Withdraw')->where('status', 'Sukses')->where('created_at', 'like', $month . '%')->sum('total');
        $profit_m = $profit += $transaction_m - $withdraw_m;

        $bet_a = GameHistory::where('extplayer', $extplayer)
            ->sum('bet_amount');

        $win_a = GameHistory::where('extplayer', $extplayer)
            ->sum('win_amount');
        $profit_a1 = $bet_a - $win_a;
        $profit_a = $profit_a1 += $transaction - $withdraw;

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'user_balance' => 'MYR ' . number_format($user->balance, 2),
            'deposit' => 'MYR ' . number_format($transaction, 2),
            'withdraw' => 'MYR ' . number_format($withdraw, 2),
            'month_profit' => 'MYR ' . number_format($profit_m, 2),
            'all_profit' => 'MYR ' . number_format($profit_a, 2),
        ]);
    }

    public function transaction_history($id, Request $request)
    {
        $user = User::where('extplayer', $id)->first();
        if (empty($user)) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid Member',
            ]);
        }
        [$startDate, $endDate] = explode(' - ', $request->daterange);
        $transactions = Transaction::where('id_user', $user->id)
            ->where('status', '!=', 'Pending')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        $sum_depo = Transaction::where('id_user', $user->id)
            ->where('status', 'Sukses')
            ->where('transaksi', 'Top Up')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('total');
        $sum_wd = Transaction::where('id_user', $user->id)
            ->where('status', 'Sukses')
            ->where('transaksi', 'Withdraw')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('total');

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'user' => $user,
            'data' => $transactions,
            'sum_wd' => $sum_wd,
            'sum_depo' => $sum_depo,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function balanceup(Request $request)
    {
        $user = User::where('extplayer', $request->extplayer)->first();

        if ($request->action == 1) {
            $user->balance = $user->balance + $request->amount;
        } else {
            if ($user->balance < $request->amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient Balance'
                ]);
            }

            $user->balance = $user->balance - $request->amount;
        }

        DB::table('trans_balls')->insert([
            'user_id' => $user->id,
            'username' => $user->username,
            'amount' => $request->amount,
            'type' => $request->action,
            'operator' => auth()->guard('admin')->user()->id,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        $user->save();

        return back()->with('success', 'Balance ' . $user->username . ' Successfully update');
    }

    public function update_account_listing(Request $request)
    {
        $user = User::where('extplayer', $request->player_id)->first();
        $user->nama_lengkap = $request->firstname;
        $user->nama_pemilik = $request->firstname;
        $user->no_hp = $request->mobile;
        $user->status_game = $request->game_status;
        $user->status = $request->status;
        $user->save();

        return response()->json([
            's' => 'success',
            'm' => 'User ' . $user->username . ' successfully updated',
            't' => 'Update success'
        ]);
    }

    public function account_password_edit(Request $request)
    {
        $user = User::find($request->id);
        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid User'
            ]);
        }
        $user->password = Hash::make($request->player_password);
        $user->save();

        return response()->json([
            's' => 'success',
            'm' => 'Password for user ' . $user->username . ' successfully updated',
            't' => 'Update success'
        ]);
    }

    public function update_provider(Request $request)
    {
        $user = User::where('extplayer', $request->player_id)->first();
        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid User'
            ]);
        }
        $user->mega888_id = $request->mega888_id;
        $user->mega888_password = $request->mega888_password;
        $user->s918kiss_id = $request->s918kiss_id;
        $user->s918kiss_password = $request->s918kiss_password;
        $user->pussy888_id = $request->pussy888_id;
        $user->pussy888_password = $request->pussy888_password;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Game account ' . $user->username . ' successfully updated',
        ]);
    }

    public function update_bank(Request $request)
    {
        $user = User::where('extplayer', $request->player_id)->first();
        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid User'
            ]);
        }
        $user->nama_bank = $request->bankname;
        $user->nomor_rekening = $request->accno;
        $user->nama_pemilik = $request->accname;
        $user->nama_lengkap = $request->accname;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Bank ' . $user->username . ' successfully updated',
        ]);
    }


    public function update_data(Request $request)
    {
        $user = User::where('extplayer', $request->player_id)->first();
        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid User'
            ]);
        }
        $find_mobile = User::where('no_hp', $request->mobile)->where('agent_id', $request->agent_id)->first();
        if (!empty($find_mobile)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobile already exists'
            ]);
        }
        $user->no_hp = $request->mobile;
        $user->nama_pemilik = $request->account_name;
        $user->remark = $request->remark;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data ' . $user->username . ' successfully updated',
        ]);
    }

    public function update_data_remark(Request $request)
    {
        $user = User::where('extplayer', $request->player_id)->first();
        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid User'
            ]);
        }
        $user->remark = $request->remark;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data ' . $user->username . ' successfully updated',
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
