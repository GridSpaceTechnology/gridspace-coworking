<?php
// Add to the top of AuthenticatedSessionController@store for debugging

// DEBUG: Log the incoming request
\Log::info('Login attempt', [
    'email' => $request->input('email'),
    'has_password' => $request->has('password'),
    'password_length' => strlen($request->input('password')),
    'csrf_token_present' => $request->has('_token'),
]);

// DEBUG: Check if user exists before auth attempt
$user = \App\Models\User::where('email', $request->input('email'))->first();
if ($user) {
    \Log::info('User found', [
        'user_id' => $user->id,
        'password_hash_prefix' => substr($user->password, 0, 20),
    ]);
    
    // DEBUG: Test password validation
    $passwordValid = \Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password);
    \Log::info('Password validation', ['valid' => $passwordValid]);
} else {
    \Log::info('User not found');
}

// Continue with normal auth flow...
