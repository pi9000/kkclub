<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Bank;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $banks = Bank::where('agent_id', $request->agent_id)->where('level', 'admin')->get();
        return response()->json([
            'status' => 'success',
            'data' => $banks
        ]);
    }

    public function edit($id, Request $request)
    {

        $banks = Bank::find($id);
        if (empty($banks)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank not found'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $banks
        ]);
    }

    public function create(Request $request)
    {
        $bank = new Bank();
        $bank->agent_id = $request->agent_id;
        $bank->icon = $request->icon;
        $bank->nama_bank = $request->nama_bank;
        $bank->nomor_rekening = $request->nomor_rekening;
        $bank->nama_pemilik = $request->nama_pemilik;
        $bank->id_user = $request->agent_id;
        $bank->level = 'admin';
        $bank->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Bank Successfully update'
        ]);
    }

    public function update($id, Request $request)
    {
        $bank = Bank::find($id);
        if (empty($bank)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank not found'
            ]);
        }
        $bank->nama_bank = $request->nama_bank;
        $bank->nomor_rekening = $request->nomor_rekening;
        $bank->nama_pemilik = $request->nama_pemilik;
        $bank->level = 'admin';
        $bank->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Bank Successfully update'
        ]);
    }

    public function delete($id, Request $request)
    {
        $bank = Bank::find($id);
        $bank->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Bank Successfully deleted'
        ]);
    }
}
