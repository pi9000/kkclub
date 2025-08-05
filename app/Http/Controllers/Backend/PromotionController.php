<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\Floating;
use App\Models\Popup;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $promotion = Bonus::where('agent_id', $request->agent_id)->get();
        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'data' => $promotion
        ]);
    }


    public function create(Request $request)
    {
        $promotion = new Bonus();
        $promotion->agent_id = $request->agent_id;
        $promotion->slug = Str::slug($request->judul);
        $promotion->gambar = $request->file;
        $promotion->judul = $request->judul;
        $promotion->text = $request->deskripsi;
        $promotion->minimal_deposit = $request->minimal_depo;
        $promotion->bonus = $request->bonus;
        $promotion->max = $request->max_bonus;
        $promotion->max_claim = $request->max_claim;
        $promotion->bonus_type = $request->bonus_type;
        $promotion->status = $request->status;
        $promotion->type = $request->type;
        $promotion->sequence = $request->sequence;
        $promotion->save();
        return response()->json([
            'status' => 1,
            'message' => 'Promotion Successfully added',
        ]);
    }

    public function update($id, Request $request)
    {
        $promotion = Bonus::find($id);
        if ($request->hasFile('file')) {
            $url = $request->file('file')->storePublicly(
                'revplay1',
                's3',
                'public'
            );

            $gambar = config('filesystems.disks.s3.url') . $url;
        } else {
            $gambar = $promotion->gambar;
        }

        $promotion->slug = Str::slug($request->judul);
        $promotion->gambar = $gambar;
        $promotion->judul = $request->judul;
        $promotion->text = $request->deskripsi;
        $promotion->minimal_deposit = $request->minimal_depo;
        $promotion->bonus = $request->bonus;
        $promotion->max = $request->max_bonus;
        $promotion->max_claim = $request->max_claim;
        $promotion->bonus_type = $request->bonus_type;
        $promotion->turnover = $request->to;
        $promotion->status = $request->status;
        $promotion->save();

        return back()->with('success', 'Promotion Successfully update');
    }

    public function delete(Request $request)
    {
        $bonus = Bonus::where('agent_id', $request->agent_id)->where('id', $request->id)->first();
        if (empty($bonus)) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid promotion data',
            ]);
        }
        $bonus->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Promotion Successfully deleted',
        ]);
    }

    public function banner(Request $request)
    {
        $banner = Banner::all();
        return view('backend.promotion.banner', compact('banner'));
    }

    public function bcreate(Request $request)
    {
        if ($request->hasFile('file')) {
            $url = $request->file('file')->storePublicly(
                'revplay1',
                's3',
                'public'
            );

            $gambar = config('filesystems.disks.s3.url') . $url;
        }

        $banner = new Banner();
        $banner->gambar = $gambar;
        $banner->status = 'active';
        $banner->save();
        return back()->with('success', 'Banner Successfully added');
    }

    public function bdelete($id, Request $request)
    {
        $bank = Banner::find($id);
        $bank->delete();
        return back()->with('success', 'Banner Successfully deleted');
    }

    public function popup(Request $request)
    {

        $popup = Popup::first();
        if ($request->hasFile('file')) {
            $url = $request->file('file')->storePublicly(
                'revplay1',
                's3',
                'public'
            );

            $gambar = config('filesystems.disks.s3.url') . $url;
        } else {
            $gambar = $popup->gambar;
        }
        $popup->gambar = $gambar;
        $popup->title = $request->title;
        $popup->status = $request->status;
        $popup->description = $request->description;
        $popup->save();
        return back()->with('success', 'Popup Successfully updated');
    }

    public function float(Request $request)
    {
        $floating = Floating::all();
        $popup = Popup::first();
        return view('backend.floating.floating', compact('floating', 'popup'));
    }


    public function floatedit($id, Request $request)
    {
        $floating = Floating::all();
        $floats = Floating::find($id);
        $popup = Popup::first();
        return view('backend.floating.floating', compact('floating', 'floats', 'popup'));
    }

    public function floatcreate(Request $request)
    {
        if ($request->hasFile('file')) {
            $url = $request->file('file')->storePublicly(
                'revplay1',
                's3',
                'public'
            );

            $image = config('filesystems.disks.s3.url') . $url;
        }

        $floating = new Floating();
        $floating->image = $image;
        $floating->url = $request->url;
        $floating->save();

        return back()->with('success', 'Floating Successfully Created');
    }

    public function floatupdate($id, Request $request)
    {

        $floating = Floating::find($id);
        if ($request->hasFile('file')) {
            $url = $request->file('file')->storePublicly(
                'revplay1',
                's3',
                'public'
            );

            $image = config('filesystems.disks.s3.url') . $url;
        } else {
            $image = $floating->image;
        }
        $floating->image = $image;
        $floating->url = $request->url;
        $floating->save();
        return back()->with('success', 'Floating Successfully updated');
    }

    public function floatdelete($id, Request $request)
    {
        $bank = Floating::find($id);
        $bank->delete();

        return back()->with('success', 'Floating Successfully deleted');
    }
}
