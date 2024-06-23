<?php

namespace App\Models;

use App\Models\User;
use App\Models\Outlet_detail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->hasMany(User::class);
    }

    public function outlet_detail() {
        return $this->hasMany(Outlet_detail::class);
    } 

    public function produk() {
        return $this->hasMany(Produk::class);
    }
}