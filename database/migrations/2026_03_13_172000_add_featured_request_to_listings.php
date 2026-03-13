<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('featured_request_status')->default('none')->after('featured'); // none, pending, paid, active, expired
            $table->string('featured_plan')->nullable()->after('featured_request_status'); // featured, premium
            $table->integer('featured_duration')->nullable()->after('featured_plan'); // months
            $table->decimal('featured_amount', 10, 2)->nullable()->after('featured_duration');
            $table->string('featured_contact_email')->nullable()->after('featured_amount');
            $table->text('featured_notes')->nullable()->after('featured_contact_email');
            $table->timestamp('featured_starts_at')->nullable()->after('featured_notes');
            $table->timestamp('featured_expires_at')->nullable()->after('featured_starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'featured_request_status',
                'featured_plan',
                'featured_duration',
                'featured_amount',
                'featured_contact_email',
                'featured_notes',
                'featured_starts_at',
                'featured_expires_at'
            ]);
        });
    }
};
