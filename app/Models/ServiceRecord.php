<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
    protected $table = 'service_records';
    protected $fillable = ['vehicle_id', 'service_date', 'description'];

    public $timestamps = false;
}
