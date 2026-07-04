<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['name' => 'High-Speed WiFi', 'icon' => 'wifi'],
            ['name' => 'Parking', 'icon' => 'local_parking'],
            ['name' => 'Meeting Rooms', 'icon' => 'groups'],
            ['name' => 'Kitchen', 'icon' => 'coffee'],
            ['name' => 'Printing', 'icon' => 'print'],
            ['name' => 'Air Conditioning', 'icon' => 'ac_unit'],
            ['name' => 'Security', 'icon' => 'shield'],
            ['name' => '24/7 Access', 'icon' => 'schedule'],
            ['name' => 'Phone Booth', 'icon' => 'phone_in_talk'],
            ['name' => 'Lounge Area', 'icon' => 'weekend'],
            ['name' => 'Whiteboard', 'icon' => 'ink_pen'],
            ['name' => 'Projector', 'icon' => 'videocam'],
            ['name' => 'Power Outlets', 'icon' => 'power'],
            ['name' => 'Restrooms', 'icon' => 'wc'],
            ['name' => 'Reception', 'icon' => 'desk'],
            ['name' => 'CCTV', 'icon' => 'videocam'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::updateOrCreate(
                ['name' => $amenity['name']],
                ['icon' => $amenity['icon']]
            );
        }
    }
}
