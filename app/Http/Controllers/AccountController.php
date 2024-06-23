<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use App\Models\Outlet;
use App\Models\Outlet_detail;
use App\Models\User_detail;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();
        $data = User::all();
        return view('superadmin.data-master.accounts.index', [
            'data' => $data , 
            'locations' => $locations, 
            'data_outlet' => Outlet::all(),
            'outlet_detail' => Outlet_detail::all()]);
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
        // return $request;
        $user = User::create([
            'name' => $request->name,
            'role_id' => $request->role_id,
            'location_id' => $request->location_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'outlet_id' => $request->user_outlet_id,
        ]);

        User_detail::create([
            'user_id' => $user->id, 
            'outlet_detail_id' => $request->outlet_detail_id
        ]);

        return redirect()->route('accounts.index')->with('success', 'Akun Cabang berhasil ditambahkan.');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::where('id', $id)->first();
        return view('superadmin.data-master.accounts.index',[
            'data' => $data,
            'outlet_detail' => Outlet_detail::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'name' => $request->name,
            'location_id' => $request->location_id,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];

        try {
            User::where('id', $id)->update($data);
            if(User_detail::where('user_id', $id)->first()) {
                User_detail::where('user_id', $id)->update(['outlet_detail_id' => $request->outlet_detail_id]);
            }else {
                User_detail::create([
                    'user_id' => $id, 
                    'outlet_detail_id' => $request->outlet_detail_id
                ]);
            }
        } catch (\Throwable $th) {
            return redirect()->route('accounts.index')->with('success', 'Gagal Update data!');
        }
        return redirect()->route('accounts.index')->with('success', 'Akun Cabang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User_detail::where('user_id', $id)->delete();
        User::where('id', $id)->delete();
        return redirect()->route('accounts.index')->with('success', 'Data User berhasil dihapus.');
    }
}