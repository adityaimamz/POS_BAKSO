<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('kasir.dashboard');
    }

    public function konfirmasi(Request $request)
    {    
        $time = $time = now()->format('Y-m-d');
        $keyword = $request->input('keyword');
        // $data = Transaction::whereIn('user_id', User::where('role_id', 5)->pluck('id'))->get();
        $data = Transaction::join('users', 'users.id', 'transactions.user_id')
        ->join('user_details', 'users.id', 'user_details.user_id')
        ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
        ->whereDate('transactions.created_at', $time)
        ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
        ->whereIn('transactions.user_id', User::where('role_id', 5)->pluck('id'))->select('transactions.*')->orderBy('created_at', 'desc');
        // Jika ada keyword, tambahkan kondisi pencarian
        if ($keyword) {
            $data->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name_customer', 'like', "%$keyword%")
                    ->orWhereHas('table', function ($tableQuery) use ($keyword) {
                        $tableQuery->where('number', 'like', "%$keyword%");
                    });
            });
        }
        
    
        // Ambil hasil query dan kirimkan ke view
        $transaksi = $data->get();
        
        return view('kasir.transaksi.konfirmasi', ['transaksi' => $transaksi]);
    }
    
    public function konfirmasi_store($id) {
        Transaction::where('id', $id)->update(['confirm_order' => 1]);
        return redirect()->back();
    }
    public function berjalan(Request $request)
    {
        if($request->date) {
            $keyword = $request->input('keyword');
        
            // Query dasar untuk mendapatkan transaksi yang belum memiliki payment_id
            $query = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->join('tables', 'tables.id', 'transactions.table_id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)->where('transactions.payment_id', '!=', null)
            ->whereDate('transactions.created_at', $request->date)
            ->select(['transactions.*', 'tables.number as table', 'transaction_details.*']);
        
            // Jika ada keyword, tambahkan kondisi pencarian
            if ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name_customer', 'like', "%$keyword%")
                        ->orWhereHas('table', function ($tableQuery) use ($keyword) {
                            $tableQuery->where('number', 'like', "%$keyword%");
                        });
                });
            }
            
        
            // Ambil hasil query dan kirimkan ke view
            $transaksi = $query->get();
            echo json_encode($transaksi);
        }else {
            $time = now()->format('Y-m-d');
            $keyword = $request->input('keyword');
        
            // Query dasar untuk mendapatkan transaksi yang belum memiliki payment_id
            $query = Transaction::join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)->where('transactions.payment_id', null)
            ->whereDate('transactions.created_at', $time)
            ->select('transactions.*');
        
            // Jika ada keyword, tambahkan kondisi pencarian
            if ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name_customer', 'like', "%$keyword%")
                        ->orWhereHas('table', function ($tableQuery) use ($keyword) {
                            $tableQuery->where('number', 'like', "%$keyword%");
                        });
                });
            }
            
        
            // Ambil hasil query dan kirimkan ke view
            $transaksi = $query->get();
            return view('kasir.transaksi.berjalan', ['transaksi' => $transaksi]);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tables = Table::where('outlet_detail_id', Auth::user()->user_detail->outlet_detail_id)->get();
        $products = Produk::where('status_stock', 'Tersedia')->get();
        return view('kasir.transaksi.baru', ['products' => $products, 'tables' => $tables]);
    }
    public function create_transaksi()
    {
        $tables = Table::where('outlet_detail_id', Auth::user()->user_detail->outlet_detail_id)->get();
        $products = Produk::where('status_stock', 'Tersedia')->where('outlet_id', Auth::user()->outlet_id)->get();
        return view('kasir.transaksi.baru', ['products' => $products, 'tables' => $tables]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
            $transaction = Transaction::create([
                'payment_id' => ($request->payment_id) ? $request->payment_id : null,
                'table_id' => $request->table_id,
                'price_amount' => $request->price_amount,
                'payment_image' => ($request->payment_image) ? $request->payment_image : null,
                'discount' => ($request->discount) ? $request->discount : null,
                'pay_amount' => $request->price_amount,
                'user_id' => Auth::user()->id,
                'name_customer' => $request->name_customer
            ]);

            foreach($request->produk as $index => $product) {
                $qty = $request->qty[$index];
                $pesan = $request->pesan[$index];
                
                if ($qty != null) {
                    Transaction_detail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product,
                        'price' => Produk::where('id', $product)->first()->price,
                        'qty' => $qty,
                        'note' => $pesan,
                        'status' => "Berjalan",
                        'order_type' => $request->order_type,
                        'order_status' => 'Diproses',
                        'order_sequence' => 1
                    ]);
                }
            }

            $datasend = [
                'transaction_id' => $transaction->id,
                'product_id' => $product,
                'qty' => $qty,
                'time' => $transaction->created_at->format('D, d/mY'),
                'message' => 'Transaksi Baru',
            ];

            event(new OutletNotification($datasend));

            
            if (Auth::user()->role_id == 3) {
                return redirect()->route('transaksi.kasir_berjalan')->with('success', 'Data Transaksi berhasil ditambahkan.');
            } elseif (Auth::user()->role_id == 5) {
                return redirect()->route('transaksi.berjalan')->with('success', 'Data Transaksi berhasil ditambahkan.');
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = Produk::where('status_stock', 'Tersedia')->where('outlet_id', Auth::user()->outlet_id)->get();
        $tables = Table::all();        
        $data = Transaction::where('transactions.id', $id)
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(['transactions.*', 'transaction_details.id as transaction_detail_id', 'transaction_details.*'])
            ->get();

        return view('kasir.transaksi.detail', ['product' => $data, 'products' => $products , 'tables' => $tables, 'data' => Transaction::where('id', $id)->first()]);
    }   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Payment::all();
        $products = Produk::all();
        $tables = Table::all();        
        $data = Transaction::where('transactions.id', $id)
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(['transactions.*', 'transaction_details.*'])
            ->get();
        // return Transaction::where('id', $id)->first();
        return view('kasir.transaksi.edit', ['data' => Transaction::where('id', $id)->first(),'product' => $data, 'products' => $products, 'payment' => $payment]);
    }

    public function selesai(Request $request) {
        if($request->date) {
            $keyword = $request->input('keyword');
        
            // Query dasar untuk mendapatkan transaksi yang belum memiliki payment_id
            $query = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->join('tables', 'tables.id', 'transactions.table_id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)->where('transactions.payment_id', '!=', null)
            ->whereDate('transactions.created_at', $request->date)
            ->select(['transactions.*', 'tables.number as table', 'transaction_details.*']);
        
            // Jika ada keyword, tambahkan kondisi pencarian
            if ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name_customer', 'like', "%$keyword%")
                        ->orWhereHas('table', function ($tableQuery) use ($keyword) {
                            $tableQuery->where('number', 'like', "%$keyword%");
                        });
                });
            }
            
        
            // Ambil hasil query dan kirimkan ke view
            $transaksi = $query->get();
            echo json_encode($transaksi);
        }else {
            $time = now()->format('Y-m-d');
            $keyword = $request->input('keyword');
        
            // Query dasar untuk mendapatkan transaksi yang belum memiliki payment_id
            $query = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)->where('transactions.payment_id', '!=', null)
            ->orderBy('created_at', 'desc')
            ->whereDate('transactions.created_at', $time)
            ->select('transactions.*');
        
            // Jika ada keyword, tambahkan kondisi pencarian
            if ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name_customer', 'like', "%$keyword%")
                        ->orWhereHas('table', function ($tableQuery) use ($keyword) {
                            $tableQuery->where('number', 'like', "%$keyword%");
                        });
                });
            }
            
        
            // Ambil hasil query dan kirimkan ke view
            $transaksi = $query->distinct()->get();
            return view('kasir.transaksi.selesai', ['transaksi' => $transaksi]);
        }

    }

    public function selesaikan_pesanan(Request $request) {
        Transaction::where('id', $request->transaction_id)->update([
            'payment_id' => $request->payment_id,
            'pay_receive' => $request->paid,
            'pay_return' => $request->return
        ]);

        foreach (Transaction_detail::where('transaction_id', $request->transaction_id)->get() as $td) {
            Transaction_detail::where('id', $td->id)->update([
                'status' => "Selesai"
            ]);
        }

        $data = Transaction::where('transactions.id', $request->transaction_id)
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(['transactions.*', 'transaction_details.*'])
            ->get();
        return view('kasir.transaksi.nota', ['product' => $data,  'data' => Transaction::where('id', $request->transaction_id)->first(), 'location' => Location::where('id', Auth::user()->location->id)->first(), 'paid' => $request->paid]);
    }

    public function nota($id) {
        $data = Transaction::where('transactions.id', $id)
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(['transactions.*', 'transaction_details.*'])
            ->get();
        return view('kasir.transaksi.nota', ['product' => $data,  'data' => Transaction::where('id', $id)->first(), 'location' => Location::where('id', Auth::user()->location->id)->first()]);
    }
    public function nota_dapur($id) {
        $data = Transaction::where('transactions.id', $id)
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(['transactions.*', 'transaction_details.*'])
            ->get();
        return view('kasir.transaksi.nota-dapur', ['product' => $data,  'data' => Transaction::where('id', $id)->first(), 'location' => Location::where('id', Auth::user()->location->id)->first()]);
    }

    public function print_rekap_produk(Request $request) {
        // return $date;
        if($request->date) {
            $time = $request->date;
        }else {
            $time = now()->format('Y-m-d');
        }
                $data = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                ->where('transaction_details.status', '!=', 'Salah')
                ->join('produks', 'transaction_details.product_id', 'produks.id')
                ->join('users', 'users.id', 'transactions.user_id')
                ->join('user_details', 'users.id', 'user_details.user_id')
                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
                ->whereDate('transactions.created_at', $time)
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
                ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
                ->whereDate('transactions.created_at', $time)
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
    
                
                return view('kasir.laporan.rekap_produk_print', [
                    'data' => $hasil,
                    'jml_polos' => $jml_bakso
                ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    public function tambah_pesanan(Request $request) {
        $order_sequence = Transaction_detail::where('transaction_id', $request->transaksi_id)->orderBy('created_at', 'DESC')->limit(1)->first();
        $transaction = Transaction::where('id', $request->transaksi_id)->update([
            'price_amount' => $request->price_amount, 
            'pay_amount' => $request->price_amount,
        ]);

        foreach ($request->prev_transaction_detail_id as $index => $transaction_id) {
            $prev_produk = $request->prev_produk[$index];
            $prev_status = $request->prev_status[$index];
            $prev_order_sequence = $request->prev_order_sequence[$index];
            $prev_qty = $request->prev_qty[$index];

            Transaction_detail::where('id', $transaction_id)->update([
                'product_id' => $prev_produk,
                'status' => $prev_status,
                'qty' => $prev_qty,
                'order_sequence' => $prev_order_sequence,
            ]);
        }

        foreach($request->produk as $index => $product) {
            $qty = $request->qty[$index];
            $pesan = $request->pesan[$index];
            
            if ($qty != null) {
                Transaction_detail::create([
                    'transaction_id' => $request->transaksi_id,
                    'product_id' => $product,
                    'price' => Produk::where('id', $product)->first()->price,
                    'qty' => $qty,
                    'status' => "Berjalan",
                    'order_type' => $request->order_type,
                    'order_status' => "Diproses",
                    'order_sequence' => $order_sequence->order_sequence + 1,
                    'note' => $pesan
                ]);
            }
        }

        $datasend = [
            'transaction_id' => $request->transaksi_id,
            'product_id' => $product,
            'qty' => $qty,
            'message' => 'Transaksi Tambahan Baru',
        ];

        event(new OutletNotification($datasend));

        return redirect()->route('transaksi.berjalan');
    }

    public function pesanan_diproses()
    {
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
        return view('kasir.transaksi.pesanan', ['transaksi' => $transaksi]);
    }
    public function pesanan_diproses_waiters()
    {
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
        return view('waiters.transaksi.pesanan', ['transaksi' => $transaksi]);
    }

    public function pesanan_selesai() {
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
        return view('kasir.transaksi.pesanan-selesai', ['transaksi' => $transaksi]);
    }

    public function rekap_harian(Request $request) {
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
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)
            ->select(['transactions.*', 'users.name as user_name'])->distinct()
            ->get();
            $transaction_detail = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
                                ->join('produks', 'transaction_details.product_id', 'produks.id')
                                ->join('users', 'users.id', 'transactions.user_id')
                                ->join('user_details', 'users.id', 'user_details.user_id')
                                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                                ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
                                ->whereDate('transactions.created_at', $time)
                                ->select(['transaction_details.*', 'users.name as user_name', 'produks.name as produk_name'])
                                ->get();
            $revenue = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', '!=', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
            $earningCash = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 1)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
            $earningQris = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 2)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
            $earningBank = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 3)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
            $minus = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
            $salah = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status','Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
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
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)
            ->select('transactions.*')->distinct()->get();
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $revenue = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', '!=', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
            $earningCash = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 1)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();;
            $earningQris = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 2)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();;
            $earningBank = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 3)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();;
            $minus = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();;
            $salah = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status','Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
        
            // return $salah;
            return view('kasir.laporan.rekap_harian', [
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
    public function print_rekap_harian(Request $request) {
        // ada req date 
        if($request->date) {
            $time = $request->date;
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $transaction = Transaction::join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)
            ->select(['transactions.*', 'users.name as user_name'])
            ->get();
            $transaction_detail = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
                                ->join('produks', 'transaction_details.product_id', 'produks.id')
                                ->join('users', 'users.id', 'transactions.user_id')
                                ->join('user_details', 'users.id', 'user_details.user_id')
                                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                                ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
                                ->whereDate('transactions.created_at', $time)
                                ->select(['transaction_details.*', 'users.name as user_name', 'produks.name as produk_name'])
                                ->get();
            $revenue = Transaction::join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', '!=', null)->sum('pay_amount');
            $earningCash = Transaction::join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 1)->sum('pay_amount');
            $earningQris = Transaction::join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 2)->sum('pay_amount');
            $earningBank = Transaction::join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 3)->sum('pay_amount');
            $minus = Transaction::join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', null)->sum('pay_amount');
            echo json_encode([
                'transactions' => $transaction, 
                'transaction_details' => $transaction_detail,
                'human_time' => $humanTime,
                'revenue' => number_format($revenue, 0, ",", ","),
                'earningCash' => number_format($earningCash, 0, ",", ","),
                'earningQris' => number_format($earningQris, 0, ",", ","),
                'earningBank' => number_format($earningBank, 0, ",", ","),
                'minus' => number_format($minus, 0, ",", ",")
            ]);
        }else {
            $time = now()->format('Y-m-d');
            $data = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)
            ->select('transactions.*')->distinct()->get();
            $carbonDate = Carbon::parse($time);
            $humanTime = $carbonDate->format('d F Y');
            $revenue = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=' ,'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', '!=', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
            $earningCash = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 1)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();;
            $earningQris = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 2)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();;
            $earningBank = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', 3)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();;
            $minus = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status', '!=', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->where('payment_id', null)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();;
            $salah = Transaction::join('transaction_details', 'transactions.id', 'transaction_details.transaction_id')
            ->where('transaction_details.status','Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)->selectRaw('SUM(transaction_details.price * transaction_details.qty) as total')
            ->get()
            ->pluck('total')
            ->first();
        
            // return $salah;
            return view('kasir.laporan.rekap_harian_print', [
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

    public function rekap_produk(Request $request) {
        if($request->date) {

            $time = $request->date;
                $data = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                ->where('transaction_details.status', '!=', 'Salah')
                ->join('produks', 'transaction_details.product_id', 'produks.id')
                ->join('users', 'users.id', 'transactions.user_id')
                ->join('user_details', 'users.id', 'user_details.user_id')
                ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
                ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
                ->whereDate('transactions.created_at', $request->date)
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
                ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
                ->whereDate('transactions.created_at', $request->date)
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
                    'stok_awal' =>  Stok_harian::join('barang_stoks', function($q) use($time) {
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
                ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
                ->whereDate('transactions.created_at', $time)
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
                ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
                ->whereDate('transactions.created_at', $time)
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
    
                return view('kasir.laporan.rekap_produk', [
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

    public function transaction_salah_store($id) {
        Transaction_detail::where('transaction_id', $id)->update(['status' => 'Salah']);
        return redirect()->back();
    }

    public function transaction_salah(Request $request) {
            $time = now()->format('Y-m-d');
            $keyword = $request->input('keyword');
        
            // Query dasar untuk mendapatkan transaksi yang belum memiliki payment_id
            $query = Transaction::join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
            ->where('transaction_details.status', 'Salah')
            ->join('users', 'users.id', 'transactions.user_id')
            ->join('user_details', 'users.id', 'user_details.user_id')
            ->join('outlet_details', 'user_details.outlet_detail_id', 'outlet_details.id')
            ->where('outlet_details.id', Auth::user()->user_detail->outlet_detail_id)
            ->whereDate('transactions.created_at', $time)
            ->select('transactions.*');
        
            // Jika ada keyword, tambahkan kondisi pencarian
            if ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name_customer', 'like', "%$keyword%")
                        ->orWhereHas('table', function ($tableQuery) use ($keyword) {
                            $tableQuery->where('number', 'like', "%$keyword%");
                        });
                });
            }
            
        
            // Ambil hasil query dan kirimkan ke view
            $transaksi = $query->distinct()->get();
            return view('kasir.transaksi.transaction_salah', ['transaksi' => $transaksi]);
    }
}