<?php

namespace App\Http\Controllers;

use App\Models\Barang_stok;
use App\Models\Location;
use App\Models\Outlet;
use App\Models\Stok_harian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $time = now()->format('Y-m-d');
        return view('admin.stok_harian', [
            'data' => Stok_harian::where('location_id', Auth::user()->location_id)->whereDate('created_at', $time)->get(),
            'barang_stok' => Barang_stok::where('location_id', Auth::user()->location_id)->get()
        ]);
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
        foreach ($request->name as $index => $val) {
            $name = $request->name[$index];
            $qty = $request->qty[$index];
            Stok_harian::create([
                'barang_stok_id' => $name,
                'qty' => $qty,
                'location_id' => Auth::user()->location_id
            ]);
        }

        return redirect()->back()->with('success', 'Data Stok Berhasil Di Tambah');
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
        Stok_harian::where('id', $id)->update([
            'barang_stok_id' => $request->name,
            'qty' => $request->qty 
        ]);

        return redirect()->back()->with('success', 'Data Stok Berhasil Di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Stok_harian::where('id', $id)->delete();
        return redirect()->back();
    }
}