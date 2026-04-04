<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== LOGIN DIAGNOSTICS ===\n\n";

// Check admin user
$admin = User::where('email', 'admin@gridspace.com')->first();

if (!$admin) {
    echo "❌ Admin user not found!\n";
    exit;
}

echo "✅ Admin user found\n";
echo "Email: {$admin->email}\n";
echo "Role: {$admin->role}\n";
echo "Approved: " . ($admin->approved ? 'Yes' : 'No') . "\n";
echo "\n";

// Check password hash
echo "Stored password hash:\n";
echo substr($admin->password, 0, 60) . "...\n\n";

// Test password verification
echo "Testing password verification:\n";
$testPassword = 'password';
$isValid = Hash::check($testPassword, $admin->password);
echo "Hash::check('password', hash): " . ($isValid ? '✅ VALID' : '❌ INVALID') . "\n\n";

// Check User model casts
echo "User model casts:\n";
$casts = $admin->casts();
print_r($casts);
echo "\n";

// Try setting password again and check
echo "Setting password to 'password' and saving...\n";
$admin->password = 'password';
echo "After assignment (before save): {$admin->password}\n";
$admin->save();
echo "After save: " . substr($admin->fresh()->password, 0, 60) . "...\n\n";

// Test again
$adminFresh = User::where('email', 'admin@gridspace.com')->first();
$isValid2 = Hash::check('password', $adminFresh->password);
echo "After re-save - Hash::check: " . ($isValid2 ? '✅ VALID' : '❌ INVALID') . "\n\n";

// Check if approved field is correct
echo "Approval status: " . ($adminFresh->approved ? '✅ APPROVED' : '❌ NOT APPROVED') . "\n";

// Try auth attempt
echo "\nTesting Auth::attempt:\n";
$authResult = Auth::attempt(['email' => 'admin@gridspace.com', 'password' => 'password']);
echo "Auth::attempt result: " . ($authResult ? '✅ SUCCESS' : '❌ FAILED') . "\n";
