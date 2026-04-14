<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    // Check if inquiries table has contacted column
    if (!Schema::hasColumn('inquiries', 'contacted')) {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->boolean('contacted')->default(false)->after('message');
        });
        echo "SUCCESS: Added contacted column to inquiries table\n";
    } else {
        echo "INFO: contacted column already exists in inquiries\n";
    }
    
    // Check if featured_requests table exists
    if (!Schema::hasTable('featured_requests')) {
        Schema::create('featured_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plan');
            $table->integer('duration');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('contact_email');
            $table->text('notes')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['listing_id', 'status']);
            $table->index(['user_id', 'status']);
        });
        echo "SUCCESS: Created featured_requests table\n";
    } else {
        echo "INFO: featured_requests table already exists\n";
    }
    
    // Mark migrations as run
    $migrations = [
        '2026_03_13_170000_fix_featured_requests_table',
        '2026_03_13_171000_fix_featured_requests_v2',
        '2026_03_28_213311_fix_featured_request_status_inconsistency',
        '2026_04_14_201300_add_contacted_to_inquiries'
    ];
    
    foreach ($migrations as $migration) {
        $exists = DB::table('migrations')->where('migration', $migration)->first();
        if (!$exists) {
            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => DB::table('migrations')->max('batch') + 1
            ]);
            echo "INFO: Marked $migration as run\n";
        }
    }
    
    echo "\nAll fixes applied successfully!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
