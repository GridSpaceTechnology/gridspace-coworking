<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Shared Coworking Desk',
            'Private Office',
            'Single Desk',
            'Conference Room',
            'Meeting Room',
            'Event Space',
            'Studio',
            'Virtual Office',
            'Startup Hub',
            'Corporate Workspace',
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category)],
                ['name' => $category]
            );
        }
    }
}
