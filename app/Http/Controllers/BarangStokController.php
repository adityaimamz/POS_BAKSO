<?php

namespace App\Http\Controllers;

use App\Models\Barang_stok;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.barang_stok', [
            'active' => 'BarangStok',
            'data' => Barang_stok::where('location_id', Auth::user()->location_id)->get(),
            'locations' => Location::all()
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
        Barang_stok::create([
            'name' => $request->name,
            'location_id' => $request->location_id
        ]);

        return redirect()->back()->with('success', 'data barang stok berhasil ditambahkan');
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
        Barang_stok::where('id', $id)->update([
            'name' => $request->name,
            'location_id' => $request->location_id
        ]);

        return redirect()->back()->with('success', 'data barang stok berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Barang_stok::where('id', $id)->delete();
        return redirect()->back()->with('success', 'data berang stok berhasil dihapus');
    }
}