<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 
        'requester_id', 
        'driver_id',
        'approver_level_1', 
        'approver_level_2',
        'status', 
        'approver_level_1_status', 
        'approver_level_2_status',
        'approved_at_level_1', 
        'approved_by_level_1',
        'approved_at_level_2', 
        'approved_by_level_2',
        'rejected_at', 
        'rejected_by',
        'start_date', 
        'end_date', 
        'purpose',
    ];

    protected $dates = [
        'start_date', 
        'end_date', 
        'approved_at_level_1', 
        'approved_at_level_2', 
        'rejected_at',
        'created_at',
        'updated_at',
    ];

    // Relasi ke kendaraan
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Relasi ke user yang membuat permintaan
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

   
    public function user()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    // Relasi ke driver
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Relasi ke approver level 1
    public function approverLevel1()
    {
        return $this->belongsTo(User::class, 'approver_level_1');
    }

    // Relasi ke approver level 2
    public function approverLevel2()
    {
        return $this->belongsTo(User::class, 'approver_level_2');
    }

    // Relasi ke user yang menyetujui level 1 
    public function approvedByLevel1()
    {
        return $this->belongsTo(User::class, 'approved_by_level_1');
    }

    // Relasi ke user yang menyetujui level 2
    public function approvedByLevel2()
    {
        return $this->belongsTo(User::class, 'approved_by_level_2');
    }

    // Relasi ke user yang menolak
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Scope: Pemesanan yang menunggu approval
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope: Pemesanan yang telah disetujui
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope: Pemesanan yang ditolak
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
