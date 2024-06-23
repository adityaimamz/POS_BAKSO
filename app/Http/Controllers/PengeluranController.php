<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PengeluranController extends Controller
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
            return view('kasir.laporan.pengeluaran_harian', [
                'active' => 'pengeluaran',
                'human_time' => $humanTime,
                'data' => Pengeluaran::whereDate('created_at', $time)->get()
            ]);
        }
    }
    public function pengeluaran_harian_print(Request $request)
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
            return view('kasir.laporan.pengeluaran_harian_print', [
                'active' => 'pengeluaran',
                'human_time' => $humanTime,
                'data' => Pengeluaran::whereDate('created_at', $time)->get()
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
        Pengeluaran::create([
            'name' => $request->name,
            'qty' => $request->qty,
            'amount' => $request->amount,
        ]);

        return Redirect()->back();
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
        Pengeluaran::where('id', $id)->delete();
        return redirect()->back();
    }
}