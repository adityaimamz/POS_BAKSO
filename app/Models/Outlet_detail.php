<?php

namespace App\Models;

use App\Models\Table;
use App\Models\Outlet;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet_detail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function location() {
        return $this->belongsTo(Location::class);
    }
    public function outlet() {
        return $this->belongsTo(Outlet::class);
    }

    public function table() {
        return $this->hasMany(Table::class);
    }
}