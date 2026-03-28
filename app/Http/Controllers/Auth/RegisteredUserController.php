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
        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female,other'],
            'marital_status' => ['required', 'in:single,married,divorced,widowed'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'residence' => ['required', 'string', 'max:255'],
            'local_government_area' => ['required', 'string', 'max:255'],
            'state_of_origin' => ['required', 'string', 'max:255'],
            'home_town' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'religion' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'business_description' => ['nullable', 'string', 'max:1000'],
            'role' => ['required', 'in:user,host'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'marital_status' => $validated['marital_status'],
            'date_of_birth' => $validated['date_of_birth'],
            'residence' => $validated['residence'],
            'local_government_area' => $validated['local_government_area'],
            'state_of_origin' => $validated['state_of_origin'],
            'home_town' => $validated['home_town'],
            'nationality' => $validated['nationality'],
            'religion' => $validated['religion'],
            'company_name' => $validated['company_name'],
            'business_description' => $validated['business_description'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'approved' => true, // Regular users don't need approval
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user role
        if ($user->isHost()) {
            return redirect(route('dashboard', absolute: false));
        } else {
            return redirect(route('home', absolute: false))->with('success', 'Welcome to Gridspace Cowork!');
        }
    }
}
