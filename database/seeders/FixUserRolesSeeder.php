<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class FixUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fix any users without roles by assigning them 'host' role
        $usersWithoutRole = User::whereNull('role')->get();
        
        foreach ($usersWithoutRole as $user) {
            $user->update(['role' => 'host']);
            echo "Assigned 'host' role to user: {$user->email}\n";
        }
        
        echo "Fixed {$usersWithoutRole->count()} users without roles\n";
    }
}
