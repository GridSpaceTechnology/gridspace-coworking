<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'city_id',
        'name',
        'slug',
        'description',
        'address',
        'contact_phone',
        'whatsapp_number',
        'website',
        'price_range',
        'capacity',
        'featured',
        'status',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'capacity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function getAvailableAttribute()
    {
        $activeBookings = $this->bookings()->where('status', 'confirmed')->get();

        if ($activeBookings->isEmpty()) {
            return true;
        }

        foreach ($activeBookings as $booking) {
            if (now()->between($booking->check_in_date, $booking->check_out_date)) {
                return false;
            }
        }

        return true;
    }

    public function analytics()
    {
        return $this->hasMany(ListingAnalytic::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            if (empty($listing->slug)) {
                $listing->slug = Str::slug($listing->name);
            }
        });

        static::updating(function ($listing) {
            if ($listing->isDirty('name') && empty($listing->slug)) {
                $listing->slug = Str::slug($listing->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
