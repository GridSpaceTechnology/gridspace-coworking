<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Update listings with price from price_range
$listings = DB::table('listings')->get();

foreach ($listings as $listing) {
    // Extract price from price_range (assuming format like '₦10,000 - ₦15,000')
    $priceRange = $listing->price_range;
    
    if (preg_match('/₦([\d,]+)/', $priceRange, $matches)) {
        $price = (int) str_replace(',', '', $matches[1]);
        
        DB::table('listings')
            ->where('id', $listing->id)
            ->update(['price' => $price]);
            
        echo "Updated listing {$listing->id}: {$listing->name} - ₦{$price}\n";
    }
}

echo "Price updates completed!";
