<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bonus;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\SeamlesWsController;
use Yajra\DataTables\Facades\DataTables;

class DepositController extends Controller
{

    public function transaction_history(Request $request)
    {
        [$startDate, $endDate] = explode(' - ', $request->daterange);

        $query = Transaction::query()
            ->where('status', '!=', 'Pending')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($request->filled('recordType') && $request->recordType != 0) {
            $query->where('transaksi', $request->recordType);
        }

        if ($request->filled('status') && $request->status != 0) {
            $query->where('status', $request->status);
        }

        $transactions = $query->where('agent_id', $request->agent_id)->with('user')->get();

        return response()->json([
            'status' => 'success',
            'data' => $transactions,
            'sum_debit' => $transactions->where('transaksi','Withdraw')->where('status','Sukses')->sum('total'),
            'sum_credit' => $transactions->where('transaksi','Top Up')->where('status','Sukses')->sum('total'),
        ]);
    }
    public function index(Request $request)
    {
        $transaction = Transaction::with('user')->where('agent_id', $request->agent_id)
            ->when($request->username, function ($query) use ($request) {
                $query->where('username', 'like', '%' . $request->username . '%');
            })
            ->when($request->period, function ($query) use ($request) {
                $query->whereDate('created_at', '=', $request->period);
            })
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->limit(300)->get();

        return response()->json([
            'status' => 'success',
            'data' => $transaction,
        ]);
    }

    public function get_transaction($id)
    {
        $transaction = Transaction::with('user')->where('trx_id', $id)->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $transaction,
        ]);
    }

    public function bulkActionReject(Request $request)
    {
        $transactions = Transaction::whereIn('trx_id', $request->multi)->get();
        $amount = $transactions->sum('total');
        foreach ($transactions as $transaction) {
            $user = User::find($transaction->id_user);
            $transaction->status = 'Ditolak';
            $transaction->transaction_by = $request->transaction_by;

            if ($transaction->dari_bank == 'Main Balance') {
                $user->balance = $transaction->total;
                $user->save();
            }
            $transaction->save();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Bulk action completed successfully.',
            'sum_amount' => $amount,
        ]);
    }

    public function bulkActionapprove(Request $request)
    {
        $transactions = Transaction::whereIn('trx_id', $request->multi)->get();
        $amount = $transactions->sum('total');

        foreach ($transactions as $transaction) {
            $bonus = Bonus::find($transaction->bonus);
            $user = User::find($transaction->id_user);
            $transaction->transaction_by = $request->transaction_by;
            $transaction->status = 'Sukses';
            $transaction->save();

            if ($transaction->dari_bank != 'Main Balance') {
                if (!empty($bonus)) {
                    $bonust =  $transaction->total * $bonus->bonus / 100;
                    if ($bonust > $bonus->max) {
                        $totals =  $bonus->max;
                    } else {
                        $totals = $transaction->total + $bonust;;
                    }
                } else {
                    $totals =  $transaction->total;
                }

                $user->balance = $user->balance + $totals;
                $user->save();
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Bulk action completed successfully.',
            'sum_amount' => $amount,
        ]);
    }

    public function approve($id, Request $request)
    {
        $transaction = Transaction::where('trx_id', $id)->first();
        $bonus = Bonus::find($transaction->bonus);
        $user = User::find($transaction->id_user);
        $bank = Bank::where('nama_bank', $transaction->metode)->where('level', 'admin')->first();

        $transaction->transaction_by = $request->transaction_by;
        $transaction->status = 'Sukses';
        $transaction->save();

        if ($transaction->transaksi == 'Top Up') {
            if ($transaction->dari_bank == 'Main Balance') {
                $user->balance = $user->balance + $transaction->total;
                $user->save();
            } else {
                if (!empty($bonus)) {
                    $bonust =  $transaction->total * $bonus->bonus / 100;
                    if ($bonust > $bonus->max) {
                        $totals =  $bonus->max;
                    } else {
                        $totals = $transaction->total + $bonust;
                    }
                } else {
                    $totals =  $transaction->total;
                }
                $user->balance = $user->balance + $totals;
                $user->save();
            }
        } else {
            if ($transaction->metode == 'Main Wallet') {
                $user = User::find($transaction->id_user);

                $user->balance = $user->balance + $transaction->total;
                $user->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction completed successfully.',
            'transaction' => $transaction->transakasi,
            'amount' => $transaction->total,
        ]);
    }

    public function reject($id, Request $request)
    {
        $transaction = Transaction::where('trx_id', $id)->first();

        $transaction->status = 'Ditolak';
        $transaction->transaction_by = $request->transaction_by;
        $transaction->save();
        $user = User::find($transaction->id_user);

        if ($transaction->transaksi == 'Top Up') {
            if ($transaction->dari_bank == 'Main Balance') {
                $user->balance = $transaction->total;
                $user->save();
            }
        } else {
            $user->balance = $user->balance + $transaction->total;
            $user->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction completed successfully.',
            'transaction' => $transaction->transakasi,
            'amount' => $transaction->total,
        ]);
    }

    public function reload_payment(Request $request)
    {
        $transaction = Transaction::where('trx_id', $request->SerialNo)->first();

        if (empty($transaction)) {
            return back()->with('error', 'Deposit Not Found');
        }

        $end = new SeamlesWsController();

        $check = $end->check_pay($transaction->trx_id);

        $bonus = Bonus::find($transaction->bonus);
        $user = User::find($transaction->id_user);

        $transaction->transaction_by = "Sistem";

        if ($check->Status == 1) {

            if (!empty($bonus)) {
                $bonust =  $transaction->total * $bonus->bonus / 100;
                if ($bonust > $bonus->max) {
                    $totals =  $bonus->max;
                } else {
                    $totals = $transaction->total + $bonust;
                }
            } else {
                $totals =  $transaction->total;
            }

            $user->balance = $user->balance + $totals;
            $user->save();
            $transaction->status = 'Sukses';
            $transaction->save();
            return back()->with('success', 'Deposit Approved By Payment Gateway');
        } elseif ($check->Status == 2) {
            $transaction->status = 'Ditolak';
            $transaction->save();
            return back()->with('success', 'Deposit Rejected By Payment Gateway');
        }



        return back()->with('success', 'Deposit Still Pending');
    }
}
