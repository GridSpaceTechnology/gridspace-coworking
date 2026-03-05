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

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
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
