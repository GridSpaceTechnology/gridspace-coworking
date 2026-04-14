<?php

use App\Models\User;
use App\Models\Inquiry;
use App\Models\Listing;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DATABASE CHECK ===\n\n";

// Check pending hosts
$pendingHosts = User::where('role', 'host')->where('approved', false)->get();
echo "1. PENDING HOSTS:\n";
echo "   Count: " . $pendingHosts->count() . "\n";
foreach ($pendingHosts as $host) {
    echo "   - {$host->email} (ID: {$host->id})\n";
}
echo "\n";

// Check all inquiries
$inquiries = Inquiry::all();
echo "2. ALL INQUIRIES:\n";
echo "   Count: " . $inquiries->count() . "\n";
foreach ($inquiries as $inquiry) {
    echo "   - {$inquiry->name} for listing ID {$inquiry->listing_id}\n";
}
echo "\n";

// Check pending listings
$pendingListings = Listing::where('status', 'pending')->get();
echo "3. PENDING LISTINGS:\n";
echo "   Count: " . $pendingListings->count() . "\n";
foreach ($pendingListings as $listing) {
    echo "   - {$listing->name} (ID: {$listing->id})\n";
}

echo "\n=== END ===\n";
