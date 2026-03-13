<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedRequest extends Model
{
    protected $fillable = [
        'listing_id',
        'user_id',
        'plan',
        'duration',
        'amount',
        'status',
        'contact_email',
        'notes',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPlanPriceAttribute()
    {
        return $this->plan === 'premium' ? 12000 : 5000;
    }

    public function getTotalPriceAttribute()
    {
        return $this->plan_price * $this->duration;
    }
}
