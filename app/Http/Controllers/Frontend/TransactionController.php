<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bonus;
use App\Models\Bank;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\SeamlesWsController;
use App\Events\DepositCreated;
use App\Events\WithdrawCreated;
use Carbon\Carbon;
use App\Models\GameHistory;

class TransactionController extends Controller
{
    public function transaction()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        $bank = DB::table('tb_bank')->where('agent_id', auth()->user()->agent_id)->where('level', 'admin')->get();
        $bank_first = DB::table('tb_bank')->where('agent_id', auth()->user()->agent_id)->where('level', 'admin')->first();
        $bu = DB::table('tb_bank')->where('agent_id', auth()->user()->agent_id)->where('id_user', auth()->user()->id)->first();
        $bonus = DB::table('tb_bonus')->where('agent_id', auth()->user()->agent_id)->where('status', 'active')->where('type', 2)->get();

        return view('frontend.transaction.deposit', compact('bank', 'bu', 'bonus', 'bank_first'));
    }

    public function transaction_withdraw()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }

        if (auth()->user()->nama_bank == '-') {
            return redirect()->route('bank_account')->with('error', 'Please Submit your bank account first');
        }

        return view('frontend.transaction.withdraw');
    }

    public function history()
    {
        $history = DB::table('tb_transaksi')->where('agent_id', auth()->user()->agent_id)->where('id_user', auth()->user()->id)->where('transaksi', 'Top Up')->orderBy('id', 'desc')->get()->map(function ($item) {
            $statusText = match ($item->status) {
                'Pending' => 'Pending',
                'Ditolak' => 'Failed',
                'Sukses' => 'Success',
                default => 'Unknown',
            };
            return [
                'amount' => number_format($item->total, 2),
                'status' => $statusText,
                'class' => strtolower($statusText),
                'datetime' => Carbon::parse($item->created_at)->format('d-m-Y H:i'),
            ];
        });

        return view('frontend.transaction.history', compact('history'));
    }

    public function history_withdraw()
    {
        $history = DB::table('tb_transaksi')->where('agent_id', auth()->user()->agent_id)->where('id_user', auth()->user()->id)->where('transaksi', 'Withdraw')->orderBy('id', 'desc')->get()->map(function ($item) {
            $statusText = match ($item->status) {
                'Pending' => 'Pending',
                'Ditolak' => 'Failed',
                'Sukses' => 'Success',
                default => 'Unknown',
            };
            return [
                'amount' => number_format($item->total, 2),
                'status' => $statusText,
                'class' => strtolower($statusText),
                'datetime' => Carbon::parse($item->created_at)->format('d-m-Y H:i'),
            ];
        });

        return view('frontend.transaction.history_withdraw', compact('history'));
    }

    public function history_game_log()
    {
        $history = GameHistory::where('agent_id', auth()->user()->agent_id)->where('extplayer', auth()->user()->extplayer)->orderBy('created_at', 'desc')->get()->map(function ($item) {
            return [
                'game' => $item->provider . '[' . $item->game_name . ']',
                'bet_amount' => number_format($item->win_amount, 2),
                'win_amount' => number_format($item->bet_amount, 2),
                'datetime' => Carbon::parse($item->created_at)->format('d-m-Y H:i'),
            ];
        });

        return view('frontend.transaction.history_game_log', compact('history'));
    }

    public function posttrx(Request $request)
    {
        $check = DB::table('tb_transaksi')->where('agent_id', auth()->user()->agent_id)->where('id_user', auth()->user()->id)->where('transaksi', 'Top Up')->where('status', 'Pending')->first();
        if (!empty($check)) {
            return redirect()->back()->with('error', __('public.pend_trans'));
        } elseif ($request->nominal < general()->min_depo) {
            return redirect()->back()->with('error', __('public.min_dp') . number_format(general()->min_depo, 2));
        } else {
            $metode = DB::table('tb_bank')->where('agent_id', auth()->user()->agent_id)->where('id', $request->metode)->first();
            $trans = new Transaction();

            if ($request->hasFile('gambar')) {
                $url = $request->file('gambar')->storePublicly(
                    'ImageFile',
                    's3',
                    'public'
                );
                $trans->gambar = config('filesystems.disks.s3.url') . $url;
            }

            $bonus = DB::table('tb_bonus')->where('agent_id', auth()->user()->agent_id)->where('id', $request->bonus)->first();

            if ($request->bonus != 'tanpabonus' && $request->nominal < $bonus->minimal_deposit) {
                return redirect()->back()->with('error', __('public.min_dp') . number_format($bonus->minimal_deposit, 2));
            }

            if ($request->bonus != 'tanpabonus') {
                $bonust = $request->nominal * $bonus->bonus / 100;

                if ($bonust > $bonus->max) {
                    $bonus_amount = $bonus->max;
                } else {
                    $bonus_amount = $bonust;
                }
            }

            $trans->trx_id = getTrx();
            $trans->transaksi = 'Top Up';
            $trans->total = $request->nominal;
            $trans->agent_id = auth()->user()->agent_id;
            $trans->dari_bank = $request->dari_bank;
            $trans->metode = $metode->nama_bank;
            $trans->bonus = $request->bonus;
            if ($request->bonus == 'tanpabonus') {
                $trans->bonus_amount = 0;
            } elseif ($request->bonus != 'tanpabonus') {
                $trans->bonus_amount = $bonus_amount;
            }

            $trans->keterangan = $request->keterangan;
            $trans->status = 'Pending';
            $trans->id_user = auth()->user()->id;
            $trans->username = auth()->user()->username;
            $trans->save();

            $user = User::find(auth()->user()->id);
            if (auth()->user()->deposit == 0) {
                $user->first_deposit_date = now();
            }
            $user->last_deposit_date = now();
            $user->deposit = 1;
            $user->save();

            $pusher = [
                'trans_id' => $trans->id,
                'username' => $user->username,
                'type' => 'Deposit',
                'amount' => number_format($request->nominal, 2)
            ];

            event(new DepositCreated($pusher));

            return redirect()->back()->with('success', __('public.depo_success'));
        }
    }

    public function posttrx_pg(Request $request)
    {
        $check = DB::table('tb_transaksi')->where('agent_id', auth()->user()->agent_id)->where('id_user', auth()->user()->id)->where('transaksi', 'Top Up')->where('status', 'Pending')->first();
        if (!empty($check)) {
            return redirect()->back()->with('error', __('public.pend_trans'));
        } elseif ($request->nominal < general()->min_depo) {
            return redirect()->back()->with('error', __('public.min_dp') . number_format(general()->min_depo, 2));
        } else {
            $end = new SeamlesWsController();
            $bonus = DB::table('tb_bonus')->where('agent_id', auth()->user()->agent_id)->where('id', $request->bonus)->first();

            if ($request->bonus != 'tanpabonus' && $request->nominal < $bonus->minimal_deposit) {
                return redirect()->back()->with('error', __('public.min_dp') . number_format($bonus->minimal_deposit, 2));
            }

            if ($request->bonus != 'tanpabonus') {
                $bonust = $request->nominal * $bonus->bonus / 100;

                if ($bonust > $bonus->max) {
                    $bonus_amount = $bonus->max;
                } else {
                    $bonus_amount = $bonust;
                }
            }

            $trx_id = getTrx();
            $data = $end->create_pay($request->nominal, $trx_id);
            $trans = new Transaction();
            $trans->trx_id = $trx_id;
            $trans->agent_id = auth()->user()->agent_id;
            $trans->transaksi = 'Top Up';
            $trans->total = $request->nominal;
            $trans->dari_bank = $request->dari_bank;
            $trans->metode = "Payment Gateway";
            $trans->bonus = $request->bonus;
            if ($request->bonus == 'tanpabonus') {
                $trans->bonus_amount = 0;
            } elseif ($request->bonus != 'tanpabonus') {
                $trans->bonus_amount = $bonus_amount;
            }
            $trans->keterangan = $data->data;
            $trans->status = 'Pending';
            $trans->id_user = auth()->user()->id;
            $trans->username = auth()->user()->username;
            $trans->save();

            $user = User::find(auth()->user()->id);
            if (auth()->user()->deposit == 0) {
                $user->first_deposit_date = now();
            }
            $user->last_deposit_date = now();
            $user->deposit = 1;
            $user->save();

            $pusher = [
                'trans_id' => $trans->id,
                'username' => $user->username,
                'type' => 'Deposit',
                'amount' => number_format($request->nominal, 2)
            ];

            event(new DepositCreated($pusher));

            if ($data->success == "true") {
                return redirect($data->data);
            } else {
                return redirect()->back()->with('error', 'Your deposit request error.');
            }
        }
    }

    public function posttrx_reload(Request $request)
    {
        $check = DB::table('tb_transaksi')->where('agent_id', auth()->user()->agent_id)->where('id_user', auth()->user()->id)->where('transaksi', 'Top Up')->where('status', 'Pending')->first();
        if (!empty($check)) {
            return redirect()->back()->with('error', __('public.pend_trans'));
        } elseif ($request->nominal < general()->min_depo) {
            return redirect()->back()->with('error', __('public.min_dp') . number_format(general()->min_depo, 2));
        } else {
            $trans = new Transaction();
            $trans->trx_id = getTrx();
            $trans->transaksi = 'Top Up';
            $trans->total = $request->nominal;
            $trans->agent_id = auth()->user()->agent_id;
            $trans->dari_bank = $request->dari_bank;
            $trans->metode = "Reload PIN " . $request->bank;
            $trans->bonus = $request->bonus;
            if ($request->bonus == 'tanpabonus') {
                $trans->bonus_amount = 0;
            } elseif ($request->bonus != 'tanpabonus') {
                $bonuses = DB::table('tb_bonus')->where('id', $request->bonus)->first();
                $trans->bonus_amount = 0;
                $trans->bonus_title = $bonuses->judul;
            }
            $trans->keterangan = $request->pin;
            $trans->status = 'Pending';
            $trans->id_user = auth()->user()->id;
            $trans->username = auth()->user()->username;
            $trans->save();

            $user = User::find(auth()->user()->id);
            $user = User::find(auth()->user()->id);
            if (auth()->user()->deposit == 0) {
                $user->first_deposit_date = now();
            }
            $user->last_deposit_date = now();
            $user->deposit = 1;
            $user->save();

            $pusher = [
                'trans_id' => $trans->id,
                'username' => $user->username,
                'type' => 'Deposit',
                'amount' => number_format($request->nominal, 2)
            ];

            event(new DepositCreated($pusher));

            return redirect()->back()->with('success', __('public.depo_success'));
        }
    }

    public function withdraw(Request $request)
    {

        $deposit = DB::table('tb_transaksi')->where('agent_id', auth()->user()->agent_id)->where('id_user', auth()->user()->id)->where('transaksi', 'Top Up')->where('status', 'Sukses')->orderBy('created_at', 'desc')->first();
        if ($deposit->bonus != 'tanpabonus') {
            $bonus = DB::table('tb_bonus')->where('id', $deposit->bonus)->first();
            $bonusx = $deposit->total * $bonus->bonus / 100;
            $bonust = $deposit->total + $bonusx;
            $to = $bonust * $bonus->turnover;
            if ($request->jumlah < $to) {
                return redirect()->back()->with('error', __('public.fail_wd') . number_format($to, 2));
            }
        }
        $check = DB::table('tb_transaksi')->where('id_user', auth()->user()->id)->where('transaksi', 'Withdraw')->where('status', 'Pending')->first();
        if (!empty($check)) {
            return redirect()->back()->with('error', __('public.pend_trans'));
        } elseif ($request->jumlah < general()->min_wd) {
            return redirect()->back()->with('error', __('public.min_wd') . number_format(general()->min_wd, 2));
        } elseif ($request->jumlah > auth()->user()->balance) {
            return redirect()->back()->with('error', __('public.insufficient_bal'));
        } else {
            $trans = new Transaction();
            $trans->agent_id = auth()->user()->agent_id;
            $trans->trx_id = getTrx();
            $trans->transaksi = 'Withdraw';
            $trans->total = $request->jumlah;
            $trans->dari_bank = $request->bank;
            $trans->keterangan = $request->keterangan;
            $trans->status = 'Pending';
            $trans->id_user = auth()->user()->id;
            $trans->username = auth()->user()->username;
            $trans->save();

            $pusher = [
                'trans_id' => $trans->id,
                'username' => auth()->user()->username,
                'type' => 'Withdraw',
                'amount' => number_format($request->jumlah, 2)
            ];

            event(new DepositCreated($pusher));

            return redirect()->back()->with('success', __('public.wd_success'));
        }
    }

    public function callback(Request $request)
    {
        $transaction = Transaction::where('trx_id', $request->SerialNo)->first();

        if (empty($transaction)) {
            return "fail";
        }
        $bonus = Bonus::find($transaction->bonus);
        $user = User::find($transaction->id_user);

        $transaction->transaction_by = "Sistem";

        if ($request->Status == 1) {

            if (!empty($bonus)) {
                $bonust =  $transaction->total * $bonus->bonus / 100;
                if ($bonust > $bonus->max) {
                    $totals =  $bonus->max;
                } else {
                    $totals = $transaction->total + $bonust;
                }
            } else {
                $totals = $transaction->total;
            }

            $user->balance = $user->balance + $totals;
            $user->save();
            $transaction->status = 'Sukses';
        } elseif ($request->Status == 2) {
            $transaction->status = 'Ditolak';
        }

        $transaction->save();

        return "success";
    }
}
