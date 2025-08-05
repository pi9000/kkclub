<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->verified != 1) {
                return redirect()->route('verify');
            }
        }
        $reff = DB::table('tb_refferal')->where('user_id', auth()->user()->id)->first();
        $upline = DB::table('tb_refferal')->where('upline', $reff->reff_code)->count();
        return view('frontend.referral', compact('reff', 'upline'));
    }

    public function redirect($code)
    {
        return redirect('home?referral='.$code);
    }
}
