<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KostImage extends Model
{
    protected $fillable = [
        'kost_id',
        'image_path',
        'is_primary',
        'order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class);
    }

    public function getImageUrlAttribute()
    {
        // Check if the file exists
        if (file_exists(storage_path('app/public/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }
        
        // Return a placeholder or data URL for missing images
        return 'data:image/svg+xml;base64,'.base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="200" height="200" fill="#e9ecef"/><g fill="#6c757d"><text x="50%" y="45%" text-anchor="middle" dy=".3em" font-family="Arial" font-size="14">No Image</text><text x="50%" y="60%" text-anchor="middle" dy=".3em" font-family="Arial" font-size="14">Available</text></g></svg>');
    }
}
