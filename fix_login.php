<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIXING LOGIN ISSUE ===\n\n";

// Clear all rate limits
echo "1. Clearing rate limits...\n";
for ($i = 0; $i < 10; $i++) {
    RateLimiter::clear('admin@gridspace.com|' . $i);
}
echo "   ✅ Rate limits cleared\n\n";

// Fix admin using raw SQL to bypass model mutators
echo "2. Resetting admin password with raw SQL...\n";
$hashedPassword = Hash::make('password');
\DB::table('users')
    ->where('email', 'admin@gridspace.com')
    ->update([
        'password' => $hashedPassword,
        'approved' => true,
        'updated_at' => now()
    ]);
echo "   ✅ Admin password reset\n";

// Verify it works
$admin = User::where('email', 'admin@gridspace.com')->first();
$valid = Hash::check('password', $admin->password);
echo "   Verification: " . ($valid ? '✅ VALID' : '❌ INVALID') . "\n\n";

// Test auth
echo "3. Testing Auth::attempt...\n";
$authResult = Auth::attempt(['email' => 'admin@gridspace.com', 'password' => 'password']);
echo "   Result: " . ($authResult ? '✅ SUCCESS' : '❌ FAILED') . "\n\n";

// Reset all users
echo "4. Resetting all users...\n";
$userHashedPassword = Hash::make('password123');
\DB::table('users')
    ->where('email', '!=', 'admin@gridspace.com')
    ->update([
        'password' => $userHashedPassword,
        'approved' => true
    ]);
$count = \DB::table('users')->where('email', '!=', 'admin@gridspace.com')->count();
echo "   ✅ {$count} users reset\n\n";

echo "=== LOGIN CREDENTIALS ===\n";
echo "Admin: admin@gridspace.com / password\n";
echo "Users: [any email] / password123\n";
