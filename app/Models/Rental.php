<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'kost_id',
        'user_id',
        'room_number',
        'start_date',
        'end_date',
        'duration_months',
        'total_price',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function review()
    {
        return $this->hasOne(\App\Models\Review::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
