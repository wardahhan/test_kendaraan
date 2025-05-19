<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',  
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

   
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function usageRecords()
    {
        return $this->hasMany(UsageRecord::class, 'driver_id');
    }

    public function ordersRequested()
    {
        return $this->hasMany(Order::class, 'requester_id');
    }

    public function ordersToApproveLevel1()
    {
        return $this->hasMany(Order::class, 'approver_level_1');
    }

    public function ordersToApproveLevel2()
    {
        return $this->hasMany(Order::class, 'approver_level_2');
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
