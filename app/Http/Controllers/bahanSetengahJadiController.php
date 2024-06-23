<?php

namespace App\Http\Controllers;

use App\Models\bahan_setengah_jadi;
use Illuminate\Http\Request;

class bahanSetengahJadiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = bahan_setengah_jadi::all();
        return view('superadmin.data-master.bahan_setengah_jadi.index', ['data' => $data]);
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
        bahan_setengah_jadi::create([
            'name' => $request->name
        ]);

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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        bahan_setengah_jadi::where('id', $id)->update(['name' => $request->name]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        bahan_setengah_jadi::where('id', $id)->delete();

        return redirect()->back();
    }
}