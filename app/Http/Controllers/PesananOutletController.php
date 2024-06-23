<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Transaction_detail;
use App\Models\User;
use App\Events\OutletNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananOutletController extends Controller
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
        $transaksi = Transaction::join('transaction_details', function($q) {
                $q->on('transactions.id', 'transaction_details.transaction_id')
                ->where('transaction_details.order_status', 'Diproses')
                ->orderByDesc('transaction_details.created_at')
                ->limit(1);
            })
            ->join('produks', 'transaction_details.product_id', 'produks.id')
            ->where('produks.location_id', '!=', null)
            ->join('users', 'transactions.user_id', 'users.id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            // ->where('transactions.created_at', now()->format('Y-m-d'))
            ->where('outlet_details.id', $user->id)
            ->orderBy('transaction_details.updated_at')
            ->select(['transactions.*'])
            ->distinct()
            ->get();
            // return $transaksi;
        return view('outlet.pesanan.index', ['transaksi' => $transaksi]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function testnotif()
    {
        event(new OutletNotification('Latihan Pusher'));
    }

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
        // return $request;

        Transaction_detail::where('transaction_id', $id)->update(['order_status' => 'Jadi']);

        // foreach ($request->produk as $produk) {
        //     Transaction_detail::where('transaction_id', $id)->where('product_id', $produk)->update(['status' => 'Jadi']);
        // }
        $datasend = [
            'transaction_id' => $id,
            'message' => 'Transaksi Tambahan Baru',
        ];

        event(new OutletNotification($datasend));

        return redirect()->route('nota_dapur', $id);
    }

    public function selesai() {
        // $transaksi = Transaction::where('payment_id', null)->get();
        $user = User::join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', '=', 'outlet_details.id')
            ->where('users.id', Auth::user()->id)
            ->select('outlet_details.id')
            ->first();
        $transaksi = Transaction::join('transaction_details', function($q) {
                $q->on('transactions.id', 'transaction_details.transaction_id')
                ->where('transaction_details.order_status', 'Jadi')
                ->orderByDesc('transaction_details.created_at')
                ->limit(1);
            })
            ->join('users', 'transactions.user_id', 'users.id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            // ->where('transactions.created_at', now()->format('Y-m-d'))
            ->where('outlet_details.id', $user->id)
            ->orderByDesc('transaction_details.updated_at')
            ->select(['transactions.*'])
            ->distinct()
            ->get();

            
    

        return view('outlet.pesanan.pesanan-selesai', ['transaksi' => $transaksi]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}