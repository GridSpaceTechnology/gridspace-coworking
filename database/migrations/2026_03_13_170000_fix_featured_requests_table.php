<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the problematic table if it exists
        try {
            DB::statement('DROP TABLE IF EXISTS featured_requests');
        } catch (\Exception $e) {
            // Ignore if table doesn't exist
        }

        // Create the table with explicit engine
        DB::statement("
            CREATE TABLE featured_requests (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                listing_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                plan VARCHAR(255) NOT NULL,
                duration INT NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                status VARCHAR(255) NOT NULL DEFAULT 'pending',
                contact_email VARCHAR(255) NOT NULL,
                notes TEXT NULL,
                starts_at TIMESTAMP NULL,
                expires_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_listing_status (listing_id, status),
                INDEX idx_user_status (user_id, status),
                FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_requests');
    }
};
