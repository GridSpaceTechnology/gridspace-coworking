<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_spaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('price_period')->default('day');
            $table->unsignedInteger('capacity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('amenity_listing_space', function (Blueprint $table) {
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listing_space_id')->constrained()->cascadeOnDelete();
            $table->primary(['amenity_id', 'listing_space_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('listing_space_id')->nullable()->after('listing_id')->constrained('listing_spaces')->nullOnDelete();
        });

        // Backfill: one space per existing listing so current data keeps working.
        $listings = DB::table('listings')->get();
        foreach ($listings as $listing) {
            $spaceId = DB::table('listing_spaces')->insertGetId([
                'listing_id' => $listing->id,
                'category_id' => $listing->category_id,
                'name' => $listing->name,
                'description' => $listing->description,
                'price' => $listing->price ?? 0,
                'price_period' => $listing->price_period ?? 'day',
                'capacity' => $listing->capacity ?? 1,
                'is_active' => true,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $amenityIds = DB::table('amenity_listing')
                ->where('listing_id', $listing->id)
                ->pluck('amenity_id');

            foreach ($amenityIds as $amenityId) {
                DB::table('amenity_listing_space')->insert([
                    'amenity_id' => $amenityId,
                    'listing_space_id' => $spaceId,
                ]);
            }

            DB::table('bookings')
                ->where('listing_id', $listing->id)
                ->whereNull('listing_space_id')
                ->update(['listing_space_id' => $spaceId]);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('listing_space_id');
        });

        Schema::dropIfExists('amenity_listing_space');
        Schema::dropIfExists('listing_spaces');
    }
};
