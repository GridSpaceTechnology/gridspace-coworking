<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cities organized by state
        $citiesByState = [
            'Lagos' => [
                ['name' => 'Lagos', 'slug' => 'lagos'],
                ['name' => 'Ikeja', 'slug' => 'ikeja'],
                ['name' => 'Ikorodu', 'slug' => 'ikorodu'],
                ['name' => 'Lekki', 'slug' => 'lekki'],
                ['name' => 'Victoria Island', 'slug' => 'victoria-island'],
                ['name' => 'Ikoyi', 'slug' => 'ikoyi'],
                ['name' => 'Epe', 'slug' => 'epe'],
                ['name' => 'Ajah', 'slug' => 'ajah'],
                ['name' => 'Badagry', 'slug' => 'badagry'],
            ],
            'Rivers' => [
                ['name' => 'Port Harcourt', 'slug' => 'port-harcourt'],
                ['name' => 'Warri', 'slug' => 'warri'],
            ],
            'Edo' => [
                ['name' => 'Benin City', 'slug' => 'benin-city'],
            ],
            'Delta' => [
                ['name' => 'Warri', 'slug' => 'warri-delta'],
            ],
            'Imo' => [
                ['name' => 'Owerri', 'slug' => 'owerri'],
            ],
            'Anambra' => [
                ['name' => 'Onitsha', 'slug' => 'onitsha'],
            ],
            'Kwara' => [
                ['name' => 'Ilorin', 'slug' => 'ilorin'],
            ],
            'Oyo' => [
                ['name' => 'Ibadan', 'slug' => 'ibadan'],
                ['name' => 'Oyo', 'slug' => 'oyo'],
            ],
            'Osun' => [
                ['name' => 'Ife', 'slug' => 'ife'],
            ],
            'Ondo' => [
                ['name' => 'Akure', 'slug' => 'akure'],
            ],
            'Kogi' => [
                ['name' => 'Lokoja', 'slug' => 'lokoja'],
            ],
            'FCT' => [
                ['name' => 'Abuja', 'slug' => 'abuja'],
            ],
            'Nasarawa' => [
                ['name' => 'Lafia', 'slug' => 'lafia'],
            ],
            'Niger' => [
                ['name' => 'Minna', 'slug' => 'minna'],
            ],
            'Katsina' => [
                ['name' => 'Katsina', 'slug' => 'katsina'],
            ],
            'Kano' => [
                ['name' => 'Kano', 'slug' => 'kano'],
            ],
            'Kaduna' => [
                ['name' => 'Kaduna', 'slug' => 'kaduna'],
            ],
            'Plateau' => [
                ['name' => 'Jos', 'slug' => 'jos'],
            ],
            'Borno' => [
                ['name' => 'Maiduguri', 'slug' => 'maiduguri'],
            ],
            'Bauchi' => [
                ['name' => 'Bauchi', 'slug' => 'bauchi'],
            ],
            'Gombe' => [
                ['name' => 'Gombe', 'slug' => 'gombe'],
            ],
            'Adamawa' => [
                ['name' => 'Yola', 'slug' => 'yola'],
            ],
            'Taraba' => [
                ['name' => 'Jalingo', 'slug' => 'jalingo'],
            ],
            'Sokoto' => [
                ['name' => 'Sokoto', 'slug' => 'sokoto'],
            ],
            'Zamfara' => [
                ['name' => 'Gusau', 'slug' => 'gusau'],
            ],
            'Benue' => [
                ['name' => 'Makurdi', 'slug' => 'makurdi'],
            ],
            'Kebbi' => [
                ['name' => 'Birnin Kebbi', 'slug' => 'birnin-kebbi'],
            ],
        ];

        foreach ($citiesByState as $state => $cities) {
            foreach ($cities as $city) {
                City::updateOrCreate(
                    ['slug' => $city['slug']],
                    [
                        'name' => $city['name'],
                        'state' => $state,
                    ]
                );
            }
        }
    }
}
