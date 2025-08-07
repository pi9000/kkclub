<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Saldo;
use Illuminate\Http\Request;
use App\Models\Refferal;
use App\Models\Bank;
use App\Http\Controllers\Api\SeamlesWsController;
use App\Http\Controllers\VerificationController;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    protected function create(array $data)
    {

        $otp = new VerificationController();
        $username = preg_replace("/[^a-zA-Z0-9]+/", "", $data['username']);
        $user = User::create([
            'extplayer' => strtoupper(general()->agent_id . random_string(4)),
            'agent_id' => general()->agent_id,
            'username' => strtolower($username),
            'password' => Hash::make($data['password']),
            'nama_lengkap' => $data['name'],
            'no_hp' => $data['contact_no'],
            'level' => 'user',
            'balance' => 0,
            'refferal' => $data['referral_code'],
            'status' => 1,
            'status_game' => 1,
            'nama_bank' => '-',
            'nomor_rekening' => '-',
            'nama_pemilik' => '-',
            'pinId' => $otp->sendsms('60' . $data['contact_no']),
            'pinReload' => 0,
            'verified' => 0,
        ]);

        $reffs = new Refferal();
        $reffs->user_id = $user->id;
        $reffs->reff_code = getReff();
        $reffs->upline = $data['referral_code'];
        $reffs->save();

        return $user;
    }
}
