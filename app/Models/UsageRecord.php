<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageRecord extends Model
{
    protected $table = 'usage_records';
    protected $fillable = ['vehicle_id', 'date', 'distance'];

    public $timestamps = false;
}
