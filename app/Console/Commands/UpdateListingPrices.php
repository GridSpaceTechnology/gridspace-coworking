<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;

class UpdateListingPrices extends Command
{
    protected $signature = 'listings:update-prices';
    protected $description = 'Update listing prices from price_range field';

    public function handle()
    {
        $listings = Listing::all();
        $updated = 0;

        foreach ($listings as $listing) {
            // Extract price from price_range (assuming format like '₦10,000 - ₦15,000')
            $priceRange = $listing->price_range;
            
            if (preg_match('/₦([\d,]+)/', $priceRange, $matches)) {
                $price = (int) str_replace(',', '', $matches[1]);
                
                $listing->price = $price;
                $listing->save();
                $updated++;
                
                $this->info("Updated listing {$listing->id}: {$listing->name} - ₦{$price}");
            }
        }

        $this->info("Price updates completed! {$updated} listings updated.");
        return 0;
    }
}
