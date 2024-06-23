<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang_stok extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function stok_harian() {
        return $this->hasMany(Stok_harian::class);
    }
}