<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'event_type',
        'ip_address',
        'user_agent',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public static function trackView($listingId, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'listing_id' => $listingId,
            'event_type' => 'view',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    public static function trackPhoneClick($listingId, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'listing_id' => $listingId,
            'event_type' => 'phone_click',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    public static function trackWhatsAppClick($listingId, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'listing_id' => $listingId,
            'event_type' => 'whatsapp_click',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    public static function trackInquiry($listingId, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'listing_id' => $listingId,
            'event_type' => 'inquiry',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
