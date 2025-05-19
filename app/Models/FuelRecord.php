<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelRecord extends Model
{
    protected $table = 'fuel_records';  
    protected $fillable = ['vehicle_id', 'date', 'fuel_amount'];

    public $timestamps = false; 
}
