<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gridspace.com'],
            [
                'firstname' => 'Admin',
                'lastname' => 'User',
                'phone' => '+2348000000001',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'approved' => true,
                'onboarding_step' => 4,
            ]
        );

        User::updateOrCreate(
            ['email' => 'host@gridspace.com'],
            [
                'firstname' => 'Host',
                'lastname' => 'User',
                'phone' => '+2348000000002',
                'password' => bcrypt('password'),
                'role' => 'host',
                'approved' => true,
                'onboarding_step' => 4,
            ]
        );
    }
}
