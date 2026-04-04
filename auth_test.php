<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== AUTH TEST ===\n\n";

// Test 1: Hash check
$admin = User::where('email', 'admin@gridspace.com')->first();
$hashValid = Hash::check('password', $admin->password);
echo "1. Hash::check: " . ($hashValid ? '✅ VALID' : '❌ INVALID') . "\n";

// Test 2: Auth attempt
$authValid = Auth::attempt(['email' => 'admin@gridspace.com', 'password' => 'password']);
echo "2. Auth::attempt: " . ($authValid ? '✅ SUCCESS' : '❌ FAILED') . "\n";

// Test 3: Check if user is approved
echo "3. User approved: " . ($admin->approved ? '✅ YES' : '❌ NO') . "\n";

// Test 4: Re-hash password properly
echo "\n4. Re-hashing with bcrypt directly...\n";
$admin->password = Hash::make('password');
$admin->save();

$adminFresh = User::where('email', 'admin@gridspace.com')->first();
$hashValid2 = Hash::check('password', $adminFresh->password);
echo "   After bcrypt - Hash::check: " . ($hashValid2 ? '✅ VALID' : '❌ INVALID') . "\n";

$authValid2 = Auth::attempt(['email' => 'admin@gridspace.com', 'password' => 'password']);
echo "   After bcrypt - Auth::attempt: " . ($authValid2 ? '✅ SUCCESS' : '❌ FAILED') . "\n";

echo "\n=== RESULT ===\n";
if ($authValid2) {
    echo "✅ LOGIN SHOULD WORK NOW!\n";
    echo "Email: admin@gridspace.com\n";
    echo "Password: password\n";
} else {
    echo "❌ Still investigating...\n";
}
