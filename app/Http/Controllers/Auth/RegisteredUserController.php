<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register-professional');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Debug: Log all incoming data
        \Log::info('Registration attempt with data: ' . json_encode($request->all()));

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'location' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Debug: Log validation success
        \Log::info('Registration validation passed');

        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'residence' => $validated['location'],
            'role' => 'user',
            'password' => Hash::make($validated['password']),
            'approved' => true,
            'onboarding_step' => 0,
        ]);

        // Debug: Log user creation success
        \Log::info('User created successfully: ' . $user->id);

        event(new Registered($user));

        Auth::login($user);

        $intent = $request->input('role') === 'host' ? 'host' : 'search';

        return redirect()
            ->route('onboarding.step1')
            ->with('onboarding_intent', $intent);
    }
}
