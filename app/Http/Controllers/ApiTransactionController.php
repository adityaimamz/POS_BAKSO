<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Transaction_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;

class ApiTransactionController extends Controller
{
    public function store(Request $request) {
        $price_amount = collect($request->products)->sum(function($item) {
            return $item['price'] * $item['qty'];
        });
        $pay_amount = $price_amount - $request->discount;

        try {
            $file = $request->file('payment_image');
            $path = 'assets/images/transactions/';
            $filename = $path . $file->getClientOriginalName();
            $file->move($path, $filename);

            $transaction = Transaction::create([
                'payment_id' => ($request->payment_id) ? $request->payment_id : null,
                'table_id' => $request->table_id,
                'price_amount' => $price_amount,
                'payment_image' => ($request->payment_image) ? $request->payment_image : null,
                'discount' => ($request->discount) ? $request->discount : null,
                'pay_amount' => $pay_amount,
                'user_id' => Auth::user()->id,
                'name_customer' => $request->name_customer
            ]);

            foreach($request->products as $product) {
                Transaction_detail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product['id'],
                    'price' => $product['price'],
                    'qty' => $product['qty'],
                    'status' => "Diproses"
                ]);
            }
            
            return response()->json([
                'code' => 201,
                'message' => "success create transaction!"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => "transaction Failed"
            ], 500);
        }
    }
}