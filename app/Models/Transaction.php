<?php

namespace App\Models;

use App\Models\Table;
use App\Models\Produk;
use App\Models\Transaction_detail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transaction_detail() {
        return $this->hasMany(Transaction_detail::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class)->withDefault();
    }

    public function table() {
        return $this->belongsTo(Table::class);
    }

    public function produk() {
        return $this->belongsTo(Produk::class);
    }
}