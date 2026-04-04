<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== COMPREHENSIVE LOGIN DEBUG ===\n\n";

// 1. Check user exists
$email = 'admin@gridspace.com';
$user = User::where('email', $email)->first();

echo "1. USER CHECK:\n";
if (!$user) {
    echo "   ❌ User not found: {$email}\n";
    exit;
}
echo "   ✅ User found: {$email}\n";
echo "   ID: {$user->id}\n";
echo "   Role: {$user->role}\n";
echo "   Approved: " . ($user->approved ? 'Yes' : 'No') . "\n\n";

// 2. Check password hash details
echo "2. PASSWORD HASH:\n";
$hash = $user->password;
echo "   Length: " . strlen($hash) . "\n";
echo "   Algorithm: " . (str_starts_with($hash, '$2y$') ? 'BCRYPT ✅' : 'UNKNOWN ❌') . "\n";
echo "   Cost: " . (str_starts_with($hash, '$2y$') ? explode('$', $hash)[2] : 'N/A') . "\n\n";

// 3. Direct Hash::check
echo "3. HASH VERIFICATION:\n";
$testPassword = 'password';
$hashResult = Hash::check($testPassword, $hash);
echo "   Hash::check('{$testPassword}', hash): " . ($hashResult ? '✅ PASS' : '❌ FAIL') . "\n\n";

// 4. Check for model mutator interference
echo "4. MODEL CHECK:\n";
$freshUser = User::find($user->id);
echo "   Fresh from DB hash: " . substr($freshUser->password, 0, 30) . "...\n";

// Try setting and getting password
$freshUser->password = 'test123';
echo "   After setting 'test123': " . substr($freshUser->password, 0, 30) . "...\n";

// Check if mutator changed it
if ($freshUser->password === 'test123') {
    echo "   ⚠️  NO MUTATOR - Password stored as plain text!\n";
} else {
    echo "   ✅ MUTATOR ACTIVE - Password was hashed\n";
}

// 5. Raw database check
echo "\n5. RAW DATABASE CHECK:\n";
$raw = DB::table('users')->where('id', $user->id)->first();
echo "   Raw DB password: " . substr($raw->password, 0, 30) . "...\n";
$rawCheck = Hash::check('password', $raw->password);
echo "   Hash::check on raw: " . ($rawCheck ? '✅ PASS' : '❌ FAIL') . "\n\n";

// 6. Auth guard check
echo "6. AUTH GUARD CHECK:\n";
$guard = Auth::guard('web');
echo "   Guard type: " . get_class($guard) . "\n";

// Try manual auth
echo "\n7. MANUAL AUTH ATTEMPT:\n";
try {
    $credentials = ['email' => $email, 'password' => 'password'];
    $authResult = Auth::attempt($credentials);
    echo "   Auth::attempt result: " . ($authResult ? '✅ SUCCESS' : '❌ FAILED') . "\n";
    
    if ($authResult) {
        echo "   User logged in: " . (Auth::check() ? 'Yes' : 'No') . "\n";
        Auth::logout(); // Clean up
    }
} catch (Exception $e) {
    echo "   ❌ Exception: " . $e->getMessage() . "\n";
}

// 8. User provider check
echo "\n8. USER PROVIDER CHECK:\n";
$provider = Auth::createUserProvider(config('auth.guards.web.provider'));
echo "   Provider: " . get_class($provider) . "\n";
$providerUser = $provider->retrieveByCredentials(['email' => $email]);
echo "   Retrieved user: " . ($providerUser ? 'Yes' : 'No') . "\n";
if ($providerUser) {
    $validateResult = $provider->validateCredentials($providerUser, ['password' => 'password']);
    echo "   validateCredentials: " . ($validateResult ? '✅ PASS' : '❌ FAIL') . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "If all checks show ❌, there's a fundamental auth issue.\n";
echo "If some show ✅ but Auth::attempt fails, check guards/middleware.\n";
