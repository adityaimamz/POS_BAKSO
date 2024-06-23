<?php

namespace App\Models;

use App\Models\Outlet;
use App\Models\Location;
use App\Models\Transaction_detail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transaction_detail() {
        return $this->hasMany(Transaction_detail::class);
    }

    public function outlet() {
        return $this->belongsTo(Outlet::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }
    public function table() {
        return $this->belongsTo(Table::class);
    }
}