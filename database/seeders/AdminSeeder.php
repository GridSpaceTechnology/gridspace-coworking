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
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@gridspace.com',
            'phone' => '+2348000000001',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'approved' => true,
        ]);

        User::create([
            'firstname' => 'Host',
            'lastname' => 'User',
            'email' => 'host@gridspace.com',
            'phone' => '+2348000000002',
            'password' => bcrypt('password'),
            'role' => 'host',
            'approved' => true,
        ]);
    }
}
