<?php

namespace App\Http\Controllers;

use App\Models\bahan_setengah_jadi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\jurnal_harian;
use App\Models\stok_barang_jurnal_harian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class JurnalHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->date) {
            $time = $request->date;
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $data = jurnal_harian::whereDate('created_at', $time)->first();
            
            echo json_encode([
                'data' => $data,
                'human_time' => $humanTime,
                'bp_dandang' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'dandang')->first()->qty : "",
                'bp_freezer_belakang' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'freezer belakang')->first()->qty : "",
                'bp_freezer_depan' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'freezer depan')->first()->qty : "",
                'bp_minus' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 1)->first()->minus : "",
                'bu_dandang' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'dandang')->first()->qty : "",
                'bu_freezer_belakang' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'freezer belakang')->first()->qty : "",
                'bu_freezer_depan' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'freezer depan')->first()->qty : "",
                'bu_minus' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 2)->first()->minus : "",
                'bd_dandang' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'dandang')->first()->qty : "",
                'bd_freezer_belakang' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'freezer belakang')->first()->qty : "",
                'bd_freezer_depan' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'freezer depan')->first()->qty : "",
                'bd_minus' => ($data) ? stok_barang_jurnal_harian::where('jurnal_harian_id', $data->id)->where('bahan_setengah_jadi_id', 3)->first()->minus : ""
            ]);
        }else {
            $time = now()->format('Y-m-d');
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            return view('kasir.laporan.jurnal', [
                'active' => 'jurnal_harian',
                'human_time' => $humanTime,
                'data' => jurnal_harian::whereDate('created_at', $time)->get()
            ]);
        }
    }
    public function jurnal_harian_print(Request $request)
    {
        if ($request->date) {
            $time = $request->date;
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $data = jurnal_harian::whereDate('created_at', $time)->get();
            $bp_dandang = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'dandang')->first()->qty;
            $bp_freezer_belakang = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'freezer belakang')->first()->qty;
            $bp_freezer_depan = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'freezer depan')->first()->qty;
            $bu_dandang = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'dandang')->first()->qty;
            $bu_freezer_belakang = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'freezer belakang')->first()->qty;
            $bu_freezer_depan = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'freezer depan')->first()->qty;
            $bd_dandang = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'dandang')->first()->qty;
            $bd_freezer_belakang = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'freezer belakang')->first()->qty;
            $bd_freezer_depan = stok_barang_jurnal_harian::where('jurnal_harian_id', $data[0]->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'freezer depan')->first()->qty;
            echo json_encode([
                'data' => $data,
                'human_time' => $humanTime,
                'bp_dandang' => $bp_dandang,
                'bp_freezer_belakang' => $bp_freezer_belakang,
                'bp_freezer_depan' => $bp_freezer_depan,
                'bu_dandang' => $bu_dandang,
                'bu_freezer_belakang' => $bu_freezer_belakang,
                'bu_freezer_depan' => $bu_freezer_depan,
                'bd_dandang' => $bd_dandang,
                'bd_freezer_belakang' => $bd_freezer_belakang,
                'bd_freezer_depan' => $bd_freezer_depan
            ]);
        }else {
            $time = now()->format('Y-m-d');
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            return view('kasir.laporan.jurnal_print', [
                'active' => 'jurnal_harian',
                'human_time' => $humanTime,
                'data' => jurnal_harian::whereDate('created_at', $time)->get()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jurnal_harian = jurnal_harian::create([
            'user_id' => Auth::user()->id,
            'jml_cash_laporan' => $request->cash_laporan, 
            'jml_cash_lapangan' => $request->cash_lapangan, 
        ]);

        // bakso polos 
        for ($i=0; $i < 3; $i++) { 
            stok_barang_jurnal_harian::create([
                'jurnal_harian_id' => $jurnal_harian->id,
                'bahan_setengah_jadi_id' => 1,
                'lokasi' => $request->bp_lokasi[$i],
                'qty' => $request->bp_qty[$i],
                'minus' => $request->bp_minus
            ]);
        }
        // bakso urat 
        for ($i=0; $i < 3; $i++) { 
            stok_barang_jurnal_harian::create([
                'jurnal_harian_id' => $jurnal_harian->id,
                'bahan_setengah_jadi_id' => 2,
                'lokasi' => $request->bu_lokasi[$i],
                'qty' => $request->bu_qty[$i],
                'minus' => $request->bu_minus
            ]);
        }
        // bakso daging 
        for ($i=0; $i < 3; $i++) { 
            stok_barang_jurnal_harian::create([
                'jurnal_harian_id' => $jurnal_harian->id,
                'bahan_setengah_jadi_id' => 3,
                'lokasi' => $request->bd_lokasi[$i],
                'qty' => $request->bd_qty[$i],
                'minus' => $request->bd_minus
            ]);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}