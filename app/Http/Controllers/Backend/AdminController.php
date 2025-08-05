<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaction;
use App\Models\Admin;
use App\Models\Settings;
use Illuminate\Support\Facades\Process;

class AdminController extends Controller
{

    public function index()
    {
        $admins = Admin::where('id', '!=' ,'1')->get();
        return view('backend.admin.admin', compact('admins'));
    }

    public function edit($id)
    {
        $admins = Admin::where('id', '!=' ,'1')->get();
        $admin = Admin::find($id);
        return view('backend.admin.admin', compact('admins', 'admin'));
    }

    public function create(Request $request)
    {
        $admin = Admin::create([
            'username' => $request->username,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'status' => 1,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Admin ' . $admin->username . ' Sucesssfully created');
    }

    public function update($id, Request $request)
    {
        $admin = Admin::find($id);
        $admin->username = $request->username;
        $admin->fullname = $request->fullname;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->level = $request->level;
        $admin->type = $request->type;
        $admin->save();
        return back()->with('success', 'Admin Sucesssfully update');
    }

    public function delete($id)
    {
        $admin = Admin::find($id);
        $admin->delete();
        return back()->with('success', 'Admin Sucesssfully delete');
    }


    public function getNotif()
    {
        $today = date('Y-m-d');

        $depos = Transaction::where('transaksi', 'Top Up')->whereDate('created_at', $today)->where('status', 'Pending')->limit(1)->get();

        foreach ($depos as $depo)

            if (!empty($depo)) {
                $notif = '<div class="alert alert-info alert-dismissible text-dark" role="alert">
            Deposit request from ' . $depo->username . ' amount MYR ' . number_format($depo->total, 2) . '<br>
            <a href="' . route('admin.deposits.pending') . '">Click here</a> to confirm
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup Navigasi"></button>
           </div><audio controls autoplay hidden="true"><source src="' . url('assets/office/audio/alert.mp3') . '" type="audio/mp3">
           </audio>';
            }

        return $notif;
    }

    public function getNotifwd()
    {
        $today = date('Y-m-d');
        $wds = Transaction::where('transaksi', 'Withdraw')->whereDate('created_at', $today)->where('status', 'Pending')->limit(1)->get();

        foreach ($wds as $wd)

            if (!empty($wd)) {
                $notif = '<div class="mt-5 alert alert-danger alert-dismissible text-dark" role="alert">
            Withdrawal request from ' . $wd->username . ' amount MYR ' . number_format($wd->total, 2) . '<br>
            <a href="' . route('admin.withdrawal.pending') . '">Click here</a> to confirm
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup Navigasi"></button>
        </div><audio controls autoplay hidden="true"><source src="' . url('assets/office/audio/alert.mp3') . '" type="audio/mp3">
           </audio>';
            }

        return $notif;
    }

    public function exportdb()
    {
        $title = general()->judul;
        $date = 'backup_' . Carbon::now()->format('Y-m-d-h');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');
        $command = "mysqldump --user={$user} -p{$password} {$database} > {$date}.sql";
        $process = Process::run($command);
        if ($process->successful()) {
            $s3 = Storage::disk('s3');
            $s3->put('BackupFiles/' . $date . ".sql", file_get_contents("{$date}.sql"));
            unlink("{$date}.sql");

            $filename = 'BackupFiles/' . $date . ".sql";

            $data = json_decode(Http::post(env('BACKEND_URL') . 'api/upload/database', [
                'accesskey' => config('filesystems.disks.s3.key'),
                'title' => $title . '-' . $filename,
                'files' => config('filesystems.disks.s3.url') . $filename,
                'website' => url('/')
            ]));
        }

        return $data;
    }
}
