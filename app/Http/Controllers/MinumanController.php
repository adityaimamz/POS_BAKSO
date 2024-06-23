<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Transaction_detail;
use Illuminate\Support\Facades\Auth;

class MinumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $transaksi = Transaction::where('payment_id', null)->get();
        $user = User::join('user_details', 'users.id', '=', 'user_details.user_id')
        ->join('outlet_details', 'user_details.outlet_detail_id', '=', 'outlet_details.id')
        ->where('users.id', Auth::user()->id)
        ->select('outlet_details.id')
        ->first();
        $transaksi = Transaction::join('users', 'transactions.user_id', 'users.id')
                                ->join('user_details', 'users.id', 'user_details.user_id')
                                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                                ->join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
                                ->where('outlet_details.id', $user->id)
                                ->select(['transactions.*'])
                                ->get();
        return view('outlet.minuman.index', ['transaksi' => $transaksi]);
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
        //
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