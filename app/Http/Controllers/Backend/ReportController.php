<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GameHistory;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Bonus;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function summary_report(Request $request)
    {
        $user = User::where('agent_id', $request->agent_id)->where('status', 1)->whereBetween('created_at', [$request->start_date, $request->end_date]);
        $transaction = Transaction::where('agent_id', $request->agent_id)->where('status', 'Sukses')->whereBetween('created_at', [$request->start_date, $request->end_date]);
        $games = GameHistory::where('agent_id', $request->agent_id)->whereBetween('created_at', [$request->start_date, $request->end_date]);
        return response()->json([
            'status' => 1,
            'message' => 'success',
            'member_count' => (clone $user)->count(),
            'active_user' => (clone $user)->where('level', 'active')->count(),
            'new_regitser_user' => (clone $user)->where('level', 'new')->count(),
            'new_regitser_user_deposit' => (clone $user)->where('level', 'new')->where('deposit', 1)->count(),
            'total_win_lose' => (clone $transaction)->where('transaksi', 'Top Up')->sum('total') + (clone $transaction)->where('transaksi', 'Withdraw')->sum('total'),
            'total_turnover' => $games->sum('bet_amount') + $games->sum('win_amount'),
            'deposit_total' => (clone $transaction)->where('transaksi', 'Top Up')->where('metode', '!=', 'by_system')->sum('total'),
            'withdrawal_total' => (clone $transaction)->where('transaksi', 'Withdraw')->where('metode', '!=', 'by_system')->sum('total'),
            'deposit_count' => (clone $transaction)->where('transaksi', 'Top Up')->where('metode', '!=', 'by_system')->count(),
            'withdrawal_count' => (clone $transaction)->where('transaksi', 'Withdraw')->where('metode', '!=', 'by_system')->count(),
            'deposit_adj' => (clone $transaction)->where('transaksi', 'Top Up')->where('metode', 'by_system')->sum('total'),
            'withdrawal_adj' => (clone $transaction)->where('transaksi', 'Withdraw')->where('metode', 'by_system')->sum('total'),
            'bonus_credit' => (clone $transaction)->where('bonus', '!=', 'tanpabonus')->sum('bonus_amount'),
        ]);
    }

    public function get_member_summary(Request $request)
    {
        $userQuery = User::where('agent_id', $request->agent_id)->where('status', 1)->whereBetween('created_at', [$request->start_date, $request->end_date]);
        $data = DataTables::of($userQuery)
            ->addIndexColumn()
            ->addColumn('last_deposit_date', function ($row) {
                $lastDeposit = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Top Up')
                    ->orderByDesc('created_at')
                    ->value('created_at');

                return $lastDeposit;
            })
            ->addColumn('last_deposit_amount', function ($row) {
                $lastDeposit = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Top Up')
                    ->orderByDesc('created_at')
                    ->first();

                return $lastDeposit ? $lastDeposit->total : '0';
            })
            ->addColumn('total_deposit', function ($row) use ($request) {
                $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
                $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

                $totalDeposit = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Top Up')
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('total');

                return $totalDeposit;
            })
            ->addColumn('total_withdraw', function ($row) use ($request) {
                $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
                $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

                $totalWithdraw = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Withdraw')
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('total');

                return $totalWithdraw;
            })
            ->addColumn('total_deposit_count', function ($row) use ($request) {
                $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
                $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

                $totalDeposit = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Top Up')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

                return $totalDeposit;
            })
            ->addColumn('total_withdraw_count', function ($row) use ($request) {
                $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
                $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

                $totalWithdraw = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Withdraw')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

                return $totalWithdraw;
            })
            ->addColumn('bonuses', function ($row) use ($request) {
                $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
                $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

                $totalDeposit = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Top Up')
                    ->where('bonus', '!=', 'tanpabonus')
                    ->whereBetween('created_at', [$start, $end])->get();
                if ($totalDeposit->count() > 0) {
                    $html = '<div style="font-size: 12px;">';
                    foreach ($totalDeposit as $bonus) {
                        $html .= '<small>' . number_format($bonus->bonus_amount, 2) . '</small><br><div>' . e($bonus->bonus_title) . '</div><hr class="my-1" />';
                    }
                    $html .= '</div>';
                    return $html;
                }
            })

            ->addColumn('total_sale', function ($row) use ($request) {
                $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
                $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

                $totalWithdraw = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Withdraw')
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('total');

                $totalDeposit = Transaction::where('id_user', $row->id)
                    ->where('status', 'Sukses')
                    ->where('transaksi', 'Top Up')
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('total');

                $total_sale = $totalDeposit - $totalWithdraw;
                $formatted = number_format($total_sale, 2);
                $color = $total_sale < 0 ? 'text-danger' : 'text-success';
                $status = $total_sale < 0 ? '-' : '';
                return "<span class='{$color}'>$status$formatted</span>";
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->rawColumns(['created_at', 'status', 'last_deposit_date', 'last_deposit_amount', 'total_deposit', 'total_withdraw', 'total_deposit_count', 'total_withdraw_count', 'bonuses', 'total_sale'])
            ->make(true);

        $dataArray = $data->getData(true);

        return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => $dataArray['data'],
        ]);
    }
}
