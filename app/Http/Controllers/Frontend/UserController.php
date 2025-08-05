<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    public function profile()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }

        $bank = DB::table('tb_bank')->where('id_user', auth()->user()->id)->where('level', 'user')->first();
        $reff = DB::table('tb_refferal')->where('user_id', auth()->user()->id)->first();
        return view('frontend.profile.profile', compact('reff', 'bank'));
    }


    public function change_password()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }
        return view('frontend.profile.change_password');
    }

    public function bank_account()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }
        return view('frontend.profile.bank_account');
    }

    public function profile_information()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify')->with('success', 'The verification code has been sent');
            }
        }
        return view('frontend.profile.profile_information');
    }

    public function update(Request $request)
    {
        if (Hash::check($request->current_password, auth()->user()->password)) {
            $user = User::find(auth()->user()->id);
            $user->password = Hash::make($request->new_password_confirmation);
            $user->save();
            return redirect()->back()->with('error', __('public.reset'));
        } else {
            return redirect()->back()->with('success', __('public.incorrect'));
        }
    }
}
