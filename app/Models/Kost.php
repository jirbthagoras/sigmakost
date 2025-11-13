<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kost extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'contact_number',
        'latitude',
        'longitude',
        'price_per_month',
        'room_count',
        'available_rooms',
        'facilities',
        'rules',
        'status',
        'created_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'price_per_month' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(KostImage::class)->orderBy('order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(KostImage::class)->where('is_primary', true);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'kost_category');
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() 
               ?? $this->images()->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_rooms', '>', 0);
    }
}
