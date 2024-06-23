<?php

namespace App\Models;

use App\Models\Outlet_detail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function location() {
        return $this->belongsTo(Location::class);
    }
    public function outlet_detail() {
        return $this->hasMany(Outlet_detail::class);
    }
}