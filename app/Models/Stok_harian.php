<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok_harian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function barang_stok() {
        return $this->belongsTo(Barang_stok::class);
    }
}