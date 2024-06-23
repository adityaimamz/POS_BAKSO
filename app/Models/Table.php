<?php

namespace App\Models;

use App\Models\Outlet_detail;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transaction() {
        return $this->hasMany(Transaction::class);
    }

    public function outlet_detail() {
        return $this->belongsTo(Outlet_detail::class);
    }
}