<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove duplicate categories - keep only singular versions
        $duplicates = [
            'Coworking Spaces' => 'Coworking Space',
            'Meeting Rooms' => 'Meeting Room',
            'Virtual Offices' => 'Virtual Office',
            'Event Spaces' => 'Event Space',
        ];

        foreach ($duplicates as $plural => $singular) {
            $pluralCat = Category::where('name', $plural)->first();
            $singularCat = Category::where('name', $singular)->first();

            if ($pluralCat && $singularCat) {
                // Update listings using plural category to use singular
                DB::table('listings')
                    ->where('category_id', $pluralCat->id)
                    ->update(['category_id' => $singularCat->id]);
                
                // Delete the duplicate plural category
                $pluralCat->delete();
            }
        }

        // Remove old long-named categories if they exist
        Category::where('name', 'Corporate Workspace Solutions')->delete();
        Category::where('name', 'Startup Infrastructure Services')->delete();

        // Add Studio category if not exists
        if (!Category::where('name', 'Studio')->exists()) {
            Category::create([
                'name' => 'Studio',
                'slug' => 'studio',
            ]);
        }

        // Add Startup Hub if not exists
        if (!Category::where('name', 'Startup Hub')->exists()) {
            Category::create([
                'name' => 'Startup Hub',
                'slug' => 'startup-hub',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this cleanup
    }
};
