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
        try {
            // Discard tablespace if it exists
            DB::statement('DROP TABLE IF EXISTS featured_requests');
        } catch (\Exception $e) {
            // Ignore errors
        }

        try {
            // Discard tablespace
            DB::statement('ALTER TABLE featured_requests DISCARD TABLESPACE');
        } catch (\Exception $e) {
            // Ignore if table doesn't exist
        }

        try {
            // Create the table
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
        } catch (\Exception $e) {
            // If Schema fails, try raw SQL
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
                    updated_at TIMESTAMP NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_requests');
    }
};
