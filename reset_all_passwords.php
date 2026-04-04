<?php

use App\Models\User;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Reset admin password
$admin = User::where('email', 'admin@gridspace.com')->first();
if ($admin) {
    $admin->password = 'password';
    $admin->approved = true;
    $admin->save();
    echo "✅ Admin password reset: admin@gridspace.com / password\n";
}

// Reset all other users to a default password (they can reset later)
$users = User::where('email', '!=', 'admin@gridspace.com')->get();
$count = 0;
foreach ($users as $user) {
    $user->password = 'password123';
    $user->approved = true;
    $user->save();
    $count++;
}

echo "✅ Reset {$count} user passwords to: password123\n";
echo "\n📋 Login Credentials:\n";
echo "Admin: admin@gridspace.com / password\n";
echo "Users: [email] / password123\n";
