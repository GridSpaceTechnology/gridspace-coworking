<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_space_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_space_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Replace legacy "night" pricing with "day".
        DB::table('listing_spaces')->where('price_period', 'night')->update(['price_period' => 'day']);
        DB::table('listings')->where('price_period', 'night')->update(['price_period' => 'day']);
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_space_images');
    }
};
