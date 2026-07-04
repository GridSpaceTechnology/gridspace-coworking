<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListingSpace extends Model
{
    protected $fillable = [
        'listing_id',
        'category_id',
        'name',
        'description',
        'price',
        'price_period',
        'capacity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingSpaceImage::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        $period = match ($this->price_period) {
            'hour' => 'hour',
            'week' => 'week',
            'month' => 'month',
            default => 'day',
        };

        return '₦' . number_format((float) $this->price, 0) . '/' . $period;
    }

    public function getPricePeriodLabelAttribute(): string
    {
        return match ($this->price_period) {
            'hour' => 'per hour',
            'week' => 'per week',
            'month' => 'per month',
            default => 'per day',
        };
    }

    public function isAvailableBetween($checkIn, $checkOut): bool
    {
        // Only approved/accepted bookings reserve the space.
        return ! $this->bookings()
            ->where('status', 'confirmed')
            ->where('check_in_date', '<', $checkOut)
            ->where('check_out_date', '>', $checkIn)
            ->exists();
    }

    public function getIsCurrentlyOccupiedAttribute(): bool
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->where('check_in_date', '<=', now())
            ->where('check_out_date', '>', now())
            ->exists();
    }

    /**
     * Space is booked once a host has approved a booking that has not ended yet.
     */
    public function getIsBookedAttribute(): bool
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->where('check_out_date', '>', now())
            ->exists();
    }
}
