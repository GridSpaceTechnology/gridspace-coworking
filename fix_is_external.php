<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('listing_images', 'is_external')) {
        Schema::table('listing_images', function (Blueprint $table) {
            $table->boolean('is_external')->default(false)->after('sort_order');
        });
        echo "SUCCESS: Added is_external column to listing_images table\n";
    } else {
        echo "INFO: is_external column already exists\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
