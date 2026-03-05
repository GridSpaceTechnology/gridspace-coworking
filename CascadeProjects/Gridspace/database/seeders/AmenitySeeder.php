<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['name' => 'High-Speed WiFi', 'icon' => 'wifi'],
            ['name' => 'Parking', 'icon' => 'car'],
            ['name' => 'Meeting Rooms', 'icon' => 'users'],
            ['name' => 'Kitchen', 'icon' => 'coffee'],
            ['name' => 'Printing', 'icon' => 'printer'],
            ['name' => 'Air Conditioning', 'icon' => 'wind'],
            ['name' => 'Security', 'icon' => 'shield'],
            ['name' => '24/7 Access', 'icon' => 'clock'],
            ['name' => 'Phone Booth', 'icon' => 'phone'],
            ['name' => 'Lounge Area', 'icon' => 'couch'],
            ['name' => 'Whiteboard', 'icon' => 'clipboard'],
            ['name' => 'Projector', 'icon' => 'presentation'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
