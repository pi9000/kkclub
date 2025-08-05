<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;

class ProfileController extends Controller
{
    public function index()
    {
        return view('backend.profile');
    }

    public function update(Request $request)
    {
    $admin = Admin::find($request->id);
        if ($request->newPassword != $request->confirmPassword) {
            return back()->with('error','New password is not the same as the confirm password');
        } elseif (Hash::check($request->currentPassword,$admin->password)) {
            $admin->password = Hash::make($request->newPassword);
            $admin->save();
            return back()->with('success','Password Sucessfully update');
        } else {
            return back()->with('error','Current password invalid!');
        }
    }
}
