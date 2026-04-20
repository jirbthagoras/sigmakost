<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'rental_id',
        'user_id',
        'amount',
        'due_date',
        'paid_date',
        'payment_method',
        'payment_proof',
        'status',
        'period_month',
        'period_year',
        'verified_by',
        'verified_at',
        'notes',
        'overdue_notification_sent',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'datetime',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
        'overdue_notification_sent' => 'boolean',
    ];

    // Relationships
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'unpaid')
            ->where('due_date', '<', now()->toDateString());
    }

    // Helpers
    public function isPaid()
    {
        return in_array($this->status, ['paid', 'verified']);
    }

    public function isOverdue()
    {
        return $this->status === 'unpaid' && $this->due_date->lt(now()->startOfDay());
    }

    public function getPeriodLabelAttribute()
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return ($months[$this->period_month] ?? '') . ' ' . $this->period_year;
    }
}
