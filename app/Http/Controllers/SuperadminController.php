<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Table;
use App\Models\Produk;
use App\Models\Payment;
use App\Models\Location;
use App\Models\Transaction;
use Illuminate\Http\Request;
use GuzzleHttp\Handler\Proxy;
use App\Events\OutletNotification;
use App\Models\Stok_harian;
use App\Models\Transaction_detail;
use App\Models\jurnal_harian;
use App\Models\stok_barang_jurnal_harian;
use App\Models\bahan_setengah_jadi;
use Illuminate\Support\Facades\Auth;

class SuperadminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pengeluaran_superadmin(Request $request)
    {
        if ($request->date) {
            $time = $request->date;
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $data = Pengeluaran::whereDate('created_at', $request->date)->get();
            $pengeluaran = Pengeluaran::whereDate('created_at', $request->date)->sum('amount');
            echo json_encode([
                'data' => $data,
                'human_time' => $humanTime,
                'pengeluaran' => number_format($pengeluaran, 0, ",", ",")
            ]);
        }else {
            $time = now()->format('Y-m-d');
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            return view('superadmin.laporan.pengeluaran_harian', [
                'active' => 'pengeluaran',
                'human_time' => $humanTime,
                'data' => Pengeluaran::whereDate('created_at', $time)->get()
            ]);
        }
    }

    public function rekap_harian_superadmin(Request $request) {
        // ada req date 
        if($request->date) {
            $time = $request->date;
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $transaction = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)
            ->where('users.outlet_id', 1)
            ->select(['transactions.*', 'users.name as user_name'])->distinct()
            ->get();
            $transaction_detail = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
                                ->join('produks', 'transaction_details.product_id', 'produks.id')
                                ->join('users', 'users.id', 'transactions.user_id')
                                ->join('user_details', 'users.id', 'user_details.user_id')
                                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                
                                ->whereDate('transactions.created_at', $request->date)
                                ->where('users.outlet_id', 1)
                                ->select(['transaction_details.*', 'users.name as user_name', 'produks.name as produk_name'])
                                ->get();
            $revenue = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', '!=', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();
            $earningCash = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', 1)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();
            $earningQris = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', 2)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();
            $earningBank = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', 3)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();
            $minus = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();
            $salah = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status','Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();
            echo json_encode([
                'transactions' => $transaction, 
                'transaction_details' => $transaction_detail,
                'human_time' => $humanTime,
                'revenue' => number_format($revenue, 0, ",", ","),
                'earningCash' => number_format($earningCash, 0, ",", ","),
                'earningQris' => number_format($earningQris, 0, ",", ","),
                'earningBank' => number_format($earningBank, 0, ",", ","),
                'minus' => number_format($minus, 0, ",", ","),
                'salah' => number_format($salah, 0, ",", ",")
            ]);
        }else {
            $time = now()->format('Y-m-d');
            $data = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)
            ->where('users.outlet_id', 1)
            ->select('transactions.*')->distinct()->get();
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $revenue = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', '!=', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();
            $earningCash = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', 1)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();;
            $earningQris = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', 2)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();;
            $earningBank = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', 3)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();;
            $minus = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();;
            $salah = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status','Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 1)
            ->get()
            ->pluck('total')
            ->first();
        
            // return $data;
            return view('superadmin.laporan.rekap_harian', [
                'data' => $data, 
                'human_time' => $humanTime,
                'revenue' => number_format($revenue, 0, ",", ","),
                'earningCash' => number_format($earningCash, 0, ",", ","),
                'earningQris' => number_format($earningQris, 0, ",", ","),
                'earningBank' => number_format($earningBank, 0, ",", ","),
                'minus' => number_format($minus, 0, ",", ","),
                'salah' => number_format($salah, 0, ",", ",")
            ]);
        }
    }
    public function rekap_harian_superadmin_cafe(Request $request) {
        // ada req date 
        if($request->date) {
            $time = $request->date;
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $transaction = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)
            ->where('users.outlet_id', 2)
            ->select(['transactions.*', 'users.name as user_name'])->distinct()
            ->get();
            $transaction_detail = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
                                ->join('produks', 'transaction_details.product_id', 'produks.id')
                                ->join('users', 'users.id', 'transactions.user_id')
                                ->join('user_details', 'users.id', 'user_details.user_id')
                                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                
                                ->whereDate('transactions.created_at', $request->date)
                                ->where('users.outlet_id', 2)
                                ->select(['transaction_details.*', 'users.name as user_name', 'produks.name as produk_name'])
                                ->get();
            $revenue = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', '!=', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();
            $earningCash = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', 1)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();
            $earningQris = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', 2)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();
            $earningBank = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', 3)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();
            $minus = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->where('payment_id', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();
            $salah = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status','Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $request->date)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();
            echo json_encode([
                'transactions' => $transaction, 
                'transaction_details' => $transaction_detail,
                'human_time' => $humanTime,
                'revenue' => number_format($revenue, 0, ",", ","),
                'earningCash' => number_format($earningCash, 0, ",", ","),
                'earningQris' => number_format($earningQris, 0, ",", ","),
                'earningBank' => number_format($earningBank, 0, ",", ","),
                'minus' => number_format($minus, 0, ",", ","),
                'salah' => number_format($salah, 0, ",", ",")
            ]);
        }else {
            $time = now()->format('Y-m-d');
            $data = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)
            ->where('users.outlet_id', 2)
            ->select('transactions.*')->distinct()->get();
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $revenue = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', '!=', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();
            $earningCash = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', 1)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();;
            $earningQris = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', 2)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();;
            $earningBank = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', 3)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();;
            $minus = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->where('payment_id', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();;
            $salah = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status','Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->whereDate('transactions.created_at', $time)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->where('users.outlet_id', 2)
            ->get()
            ->pluck('total')
            ->first();
        
            // return $salah;
            return view('superadmin.laporan.rekap_harian_cafe', [
                'data' => $data, 
                'human_time' => $humanTime,
                'revenue' => number_format($revenue, 0, ",", ","),
                'earningCash' => number_format($earningCash, 0, ",", ","),
                'earningQris' => number_format($earningQris, 0, ",", ","),
                'earningBank' => number_format($earningBank, 0, ",", ","),
                'minus' => number_format($minus, 0, ",", ","),
                'salah' => number_format($salah, 0, ",", ",")
            ]);
        }
    }

    public function rekap_produk_superadmin(Request $request) {
        if($request->date) {

            $time = $request->date;
                $data = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                ->where('transaction_details.status', '!=', 'Salah')
                ->join('produks', 'transaction_details.product_id', 'produks.id')
                ->join('users', 'users.id', 'transactions.user_id')
                ->join('user_details', 'users.id', 'user_details.user_id')
                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                ->whereDate('transactions.created_at', $request->date)
                ->where('users.outlet_id', 1)
                ->select(['produks.name', 'transaction_details.qty as porsi'])
                ->get();
    
                // echo json_encode($data);
                $groupedData = $data->groupBy('name')->map(function ($item) {
                    return $item->sum('porsi');
                });
    
                // Menampilkan hasil
                $hasil = [];
                foreach ($groupedData as $name => $totalPorsi) {
                    $hasil[] = [
                        'menu' => $name,
                        'porsi' => $totalPorsi
                    ];
                }

                $jml_bakso = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                ->where('transaction_details.status', '!=', 'Salah')
                ->join('produks', 'transaction_details.product_id', 'produks.id')
                ->join('users', 'users.id', 'transactions.user_id')
                ->join('user_details', 'users.id', 'user_details.user_id')
                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                ->whereDate('transactions.created_at', $request->date)
                ->where('users.outlet_id', 1)
                ->where(function($q) {
                    $q->where('produks.qty_bakso_polos', '!=', 0)
                    ->orWhere('produks.qty_bakso_urat', '!=', 0)
                    ->orWhere('produks.qty_bakso_daging', '!=', 0);
                })
                ->selectRaw('SUM(produks.qty_bakso_polos * transaction_details.qty) as bakso_polos')
                ->selectRaw('SUM(produks.qty_bakso_urat * transaction_details.qty) as bakso_urat')
                ->selectRaw('SUM(produks.qty_bakso_daging * transaction_details.qty) as bakso_daging')
                // ->select(['produks.qty_bakso_polos as bakso_polos', 'transaction_details.qty as qty'])
                ->get();

                echo json_encode([
                    'data' => $hasil,
                    'jml_bakso' => $jml_bakso,
                    'stok_harian' => Stok_harian::join('barang_stoks', function($q) use($time) {
                        $q->on('stok_harians.barang_stok_id', 'barang_stoks.id')
                        ->where('barang_stoks.name', 'LIKE', '%bakso%')
                        ->whereDate('stok_harians.created_at', $time);
                    })->pluck('stok_harians.qty')
                ]);
        }else {
            $time = now()->format('Y-m-d');
                $data = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                ->where('transaction_details.status', '!=', 'Salah')
                ->join('produks', 'transaction_details.product_id', 'produks.id')
                ->join('users', 'users.id', 'transactions.user_id')
                ->join('user_details', 'users.id', 'user_details.user_id')
                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                ->whereDate('transactions.created_at', $time)
                ->where('users.outlet_id', 1)
                ->select(['produks.name', 'transaction_details.qty as porsi'])
                ->get();
    
                $groupedData = $data->groupBy('name')->map(function ($item) {
                    return $item->sum('porsi');
                });
    
                // Menampilkan hasil
                $hasil = [];
                foreach ($groupedData as $name => $totalPorsi) {
                    $hasil[] = [
                        'menu' => $name,
                        'porsi' => $totalPorsi
                    ];
                }
    
                $jml_bakso = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                ->where('transaction_details.status', '!=', 'Salah')
                ->join('produks', 'transaction_details.product_id', 'produks.id')
                ->join('users', 'users.id', 'transactions.user_id')
                ->join('user_details', 'users.id', 'user_details.user_id')
                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                ->whereDate('transactions.created_at', $time)
                ->where('users.outlet_id', 1)
                ->where(function($q) {
                    $q->where('produks.qty_bakso_polos', '!=', 0)
                    ->orWhere('produks.qty_bakso_urat', '!=', 0)
                    ->orWhere('produks.qty_bakso_daging', '!=', 0);
                })
                ->selectRaw('SUM(produks.qty_bakso_polos * transaction_details.qty) as bakso_polos')
                ->selectRaw('SUM(produks.qty_bakso_urat * transaction_details.qty) as bakso_urat')
                ->selectRaw('SUM(produks.qty_bakso_daging * transaction_details.qty) as bakso_daging')
                // ->select(['produks.qty_bakso_polos as bakso_polos', 'transaction_details.qty as qty'])
                ->get();
    
                
                return view('superadmin.laporan.rekap_produk', [
                    'data' => $hasil,
                    'jml_polos' => $jml_bakso,
                    'stok_awal' =>  Stok_harian::join('barang_stoks', function($q) use($time) {
                        $q->on('stok_harians.barang_stok_id', 'barang_stoks.id')
                        ->where('barang_stoks.name', 'LIKE', '%bakso%')
                        ->whereDate('stok_harians.created_at', $time);
                    })->pluck('stok_harians.qty')
                ]);
        }
    }
    public function rekap_produk_superadmin_cafe(Request $request) {
        if($request->date) {

            $time = $request->date;
                $data = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                ->where('transaction_details.status', '!=', 'Salah')
                ->join('produks', 'transaction_details.product_id', 'produks.id')
                ->join('users', 'users.id', 'transactions.user_id')
                ->join('user_details', 'users.id', 'user_details.user_id')
                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                ->whereDate('transactions.created_at', $request->date)
                ->where('users.outlet_id', 2)
                ->select(['produks.name', 'transaction_details.qty as porsi'])
                ->get();
    
                // echo json_encode($data);
                $groupedData = $data->groupBy('name')->map(function ($item) {
                    return $item->sum('porsi');
                });
    
                // Menampilkan hasil
                $hasil = [];
                foreach ($groupedData as $name => $totalPorsi) {
                    $hasil[] = [
                        'menu' => $name,
                        'porsi' => $totalPorsi
                    ];
                }

                echo json_encode([
                    'data' => $hasil
                ]);
        }else {
            $time = now()->format('Y-m-d');
                $data = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                ->where('transaction_details.status', '!=', 'Salah')
                ->join('produks', 'transaction_details.product_id', 'produks.id')
                ->join('users', 'users.id', 'transactions.user_id')
                ->join('user_details', 'users.id', 'user_details.user_id')
                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                ->whereDate('transactions.created_at', $time)
                ->where('users.outlet_id', 2)
                ->select(['produks.name', 'transaction_details.qty as porsi'])
                ->get();
    
                $groupedData = $data->groupBy('name')->map(function ($item) {
                    return $item->sum('porsi');
                });
    
                // Menampilkan hasil
                $hasil = [];
                foreach ($groupedData as $name => $totalPorsi) {
                    $hasil[] = [
                        'menu' => $name,
                        'porsi' => $totalPorsi
                    ];
                }
                
                return view('superadmin.laporan.rekap_produk_cafe', [
                    'data' => $hasil
                ]);
        }
    }

    public function jurnal_superadmin(Request $request) 
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
            return view('superadmin.laporan.jurnal', [
                'active' => 'jurnal_harian',
                'human_time' => $humanTime,
                'data' => jurnal_harian::whereDate('created_at', $time)->get()
            ]);
        }
    }

    public function index()
    {
        //
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