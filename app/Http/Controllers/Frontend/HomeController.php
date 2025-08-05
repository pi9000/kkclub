<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\SeamlesWsController;
use App\Models\Pasaran;
use App\Models\Result;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Refferal;
use App\Models\Bank;
use App\Http\Controllers\VerificationController;


class HomeController extends Controller
{

    public function index()
    {
        $otp = new VerificationController();
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }

        $banner = DB::table('tb_banner')->where('agent_id', config('agent_id'))->where('status', 'active')->get();
        $popup = DB::table('tb_popup')->where('agent_id', config('agent_id'))->where('status', 'active')->first();
        $popular = DB::table('game_lists')->where('GameType', '1')->limit(12)->get();
        $bank = DB::table('tb_bank')->where('agent_id', config('agent_id'))->where('level', 'admin')->get();
        $promotion = DB::table('tb_bonus')->where('agent_id', config('agent_id'))->where('status', 'active')->where('type', '!=', 2)->get();
        return view('frontend.index', compact('banner', 'popup', 'bank', 'promotion', 'popular'));
    }

    public function tutorial()
    {
        return view('frontend.tutorial');
    }

    public function home()
    {
        return redirect()->route('index');
    }

    public function verify()
    {
        if (auth()->user()->verified == 1) {
            return redirect('/');
        }

        return view('frontend.auth.verify');
    }

    public function verify_otp(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($user->pinId != $request->otp) {
            $verify = 0;
        } else {
            $verify = 1;
        }

        $user->verified = $verify;
        $user->save();

        if ($verify == 1) {
            return redirect('/')->with('success', 'Your number has been successfully verified');
        } else {
            return redirect('/')->with('error', 'Invalid verification code');
        }
    }

    public function resend_code()
    {
        $user = User::find(auth()->user()->id);
        $otp = new VerificationController();
        if ($user->pinReload < 2) {
            $pin = $otp->sendsms('60' . $user->no_hp);
            $user->pinId = $pin;
            $user->pinReload = $user->pinReload + 1;
            $user->save();
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Too many Attempt, Please contact Support for verification'
        ]);
    }


    public function help()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }

        return view('frontend.page.help');
    }

    public function contact()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }

        return view('frontend.page.contact');
    }

    public function popular()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }

        $games = DB::table('game_lists')->where('GameType', '1')->limit(12)->get();
        return view('frontend.popular', compact('games'));
    }

    public function check(Request $request)
    {
        $user = DB::table('users')->where('agent_id', config('agent_id'))->where('username', $request->username)->first();
        if (!$user) {
            return response()->json([
                'error' => __('public.name_available'),
                'success' => false
            ], 200);
        } elseif (!empty($user)) {
            return response()->json([
                'error' => __('public.name_taken'),
                'success' => true
            ], 200);
        }
    }

    public function phone(Request $request)
    {
        $user = DB::table('users')->where('agent_id', config('agent_id'))->where('no_hp', $request->phone)->first();
        if (!$user) {
            return response()->json([
                'error' => __('public.phone_available'),
                'success' => false
            ], 200);
        } elseif (!empty($user)) {
            return response()->json([
                'error' => __('public.phone_taken'),
                'success' => true
            ], 200);
        }
    }

    public function optionalBankCreate(Request $request)
    {
        $bank = DB::table('users')->where('agent_id', config('agent_id'))->where('nomor_rekening', $request->optAccountNumber)->first();
        if (!empty($bank)) {
            return redirect()->back()->with('error',  __('public.accnum_available'));
        }

        $user = User::find(auth()->user()->id);
        $user->nama_lengkap = $request->optAccountName;
        $user->nomor_rekening = $request->optAccountNumber;
        $user->nama_pemilik = $request->optAccountName;
        $user->nama_bank = $request->chooseOptionalBank;
        $user->save();

        return redirect()->back()->with('success', 'Bank added successfully');
    }

    public function norek(Request $request)
    {
        $bank = DB::table('users')->where('agent_id', config('agent_id'))->where('nomor_rekening', $request->norek)->first();
        if (!$bank) {
            return response()->json([
                'error' => __('public.accnum_available'),
                'success' => false
            ], 200);
        } elseif (!empty($bank)) {
            return response()->json([
                'error' => __('public.accnum_taken'),
                'success' => true
            ], 200);
        }
    }

    public function promotion()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }

        $promotion = DB::table('tb_bonus')->where('agent_id', config('agent_id'))->where('status', 'active')->get();
        return view('frontend.promotion.promotion', compact('promotion'));
    }

    public function promotiondetail($slug)
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }

        $promotion = DB::table('tb_bonus')->where('agent_id', config('agent_id'))->where('status', 'active')->get();
        $now = DB::table('tb_bonus')->where('agent_id', config('agent_id'))->where('slug', $slug)->first();
        return view('frontend.promotion.detail', compact('promotion', 'now'));
    }

    public function transfer_money(Request $request)
    {

        if ($request->game_acc == '' && $request->game_ps == '') {
            $chatID = general()->telegram_chat_id;
            $token  = env('TELEGRAM_BOT_TOKEN');
            $message = '<b>🆔🆔🆔 Request to Create APK ID </b>

<b>Username</b> : <code>' . auth()->user()->username . '</code>
<b>APK ID</b> : Save ID/Password in Member List.
<b>Provider</b> : ' . $request->game_id . '
<b>Date</b> : ' . date('Y-m-d H:i:s') . '';

            $url = "https://api.telegram.org/bot" . $token . "/sendMessage";

            $data = ['chat_id' => $chatID, 'text' => $message, 'parse_mode' => 'HTML'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);
            return response()->json([
                'status' => 'success',
                'msg' => __('public.apk_transfer_success')
            ]);
        } else {
            $check = DB::table('tb_transaksi')->where('id_user', auth()->user()->id)->where('transaksi', 'Top Up')->where('dari_bank', 'Main Balance')->where('status', 'Pending')->first();
            if (!empty($check)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('public.pend_trans')
                ]);
            }

            if (auth()->user()->balance < 3) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('public.apk_min_transfer')
                ]);
            }

            $chatID = general()->telegram_chat_id;
            $token  = env('TELEGRAM_BOT_TOKEN');
            $message = '<b>✅✅✅ Request Transfer Credit To Games</b>

<b>Username</b> : <code>' . auth()->user()->username . '</code>
<b>Balance</b> : ' . number_format(auth()->user()->balance, 2) . '
<b>Provider</b> : ' . $request->game_id . '
<b>Date</b> : ' . date('Y-m-d H:i:s') . '';

            $url = "https://api.telegram.org/bot" . $token . "/sendMessage";

            $data = ['chat_id' => $chatID, 'text' => $message, 'parse_mode' => 'HTML'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response, true);
            if ($result['ok']) {
                $trans = new Transaction();
                $trans->agent_id = auth()->user()->agent_id;
                $trans->trx_id = getTrx();
                $trans->transaksi = 'Top Up';
                $trans->total = auth()->user()->balance;
                $trans->dari_bank = 'Main Balance';
                $trans->metode = $request->provider . ' APK Balance';
                $trans->bonus = 'tanpabonus';
                $trans->bonus_amount = 0;
                $trans->keterangan = 'Transfer Credit To Games Account : ' . $request->id_game;
                $trans->status = 'Pending';
                $trans->id_user = auth()->user()->id;
                $trans->username = auth()->user()->username;
                $trans->save();

                $end = new SeamlesWsController();
                $end->withdraw(auth()->user()->extplayer, auth()->user()->balance);
                return response()->json([
                    'status' => 'success',
                    'msg' => __('public.apk_transfer_success')
                ]);
            }
        }
    }
}
