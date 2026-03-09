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
        $cities = [
            'Lagos',
            'Ikorodu',
            'Lekki',
            'Victoria Island',
            'Ikoyi',
            'Epe',
            'Ikeja',
            'Ajah',
            'Badagry',
            'Port Harcourt',
            'Warri',
            'Benin City',
            'Owerri',
            'Onitsha',
            'Ilorin',
            'Oyo',
            'Ife',
            'Akure',
            'Kogi',
            'Kwara',
            'Abuja',
            'Nasarawa',
            'Katsina',
            'Kano',
            'Kaduna',
            'Jos',
            'Maiduguri',
            'Bauchi',
            'Gombe',
            'Yola',
            'Jalingo',
            'Ibadan',
            'Sokoto',
            'Zamfara',
            'Minna',
            'Lafia',
            'Makurdi',
            'Birnin Kebbi',
            'Borno',
            'Yobe',
            'Taraba',
        ];

        foreach ($cities as $city) {
            City::create([
                'name' => $city,
                'slug' => Str::slug($city),
            ]);
        }
    }
}
