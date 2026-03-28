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
        // Fix listings that are featured but still have 'pending' status
        DB::table('listings')
            ->where('featured_request_status', 'pending')
            ->where('featured', true)
            ->update(['featured_request_status' => 'active']);

        // Fix listings that have 'active' status but are not featured
        DB::table('listings')
            ->where('featured_request_status', 'active')
            ->where('featured', false)
            ->update(['featured_request_status' => 'pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data fix
    }
};
