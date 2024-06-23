<?php

namespace App\Http\Controllers;

use App\Models\Outlet_detail;
use Illuminate\Http\Request;
use App\Models\Table;

class TabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = table::all();
        return view('superadmin.data-master.tables.index', [
            'tables' => $tables,
            'outlet_detail' => Outlet_detail::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.data-master.tables.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        table::create([
            'number' => $request->number,
            'outlet_detail_id' => $request->outlet_detail_id
        ]);

        return redirect()->back()->with('success', 'Data Lokasi berhasil ditambahkan.');
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
       
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'number' => $request->number,
            'outlet_detail_id' => $request->outlet_detail_id
        ];

        table::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Data Lokasi berhasil diperbarui.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        table::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Data Lokasi berhasil dihapus.');
    }
}