<?php

use App\Models\User;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'admin@gridspace.com')->first();

if ($user) {
    $user->password = 'password'; // Laravel will auto-hash via casts
    $user->approved = true;
    $user->save();
    echo "Admin password reset successfully!\n";
    echo "Email: admin@gridspace.com\n";
    echo "Password: password\n";
} else {
    // Create new admin if doesn't exist
    $user = User::create([
        'firstname' => 'Admin',
        'lastname' => 'User',
        'email' => 'admin@gridspace.com',
        'phone' => '+2348000000001',
        'password' => 'password', // Laravel will auto-hash via casts
        'role' => 'admin',
        'approved' => true,
    ]);
    echo "Admin user created successfully!\n";
    echo "Email: admin@gridspace.com\n";
    echo "Password: password\n";
}
