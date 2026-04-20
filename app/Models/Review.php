<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kost;
use App\Models\Rental;

class Review extends Model
{
    protected $fillable = ['user_id', 'kost_id', 'rental_id', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
