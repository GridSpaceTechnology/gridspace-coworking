<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    private const COMPLETED_STEP = 4;

    public function step1(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->onboarding_step >= self::COMPLETED_STEP) {
            return redirect($this->destinationFor($user));
        }

        if ($user->onboarding_step >= 1) {
            return redirect($this->stepRouteFor($user));
        }

        return view('onboarding.step1', [
            'selectedIntent' => old(
                'user_intent',
                session('onboarding_intent', $user->isHost() ? 'host' : 'search')
            ),
        ]);
    }

    public function storeStep1(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_intent' => ['required', 'in:search,host'],
        ]);

        $user = $request->user();
        $user->update([
            'role' => $validated['user_intent'] === 'host' ? 'host' : 'user',
            'onboarding_step' => 1,
        ]);

        return redirect()->route('onboarding.step2');
    }

    public function step2(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->onboarding_step >= self::COMPLETED_STEP) {
            return redirect($this->destinationFor($user));
        }

        if ($user->onboarding_step < 1) {
            return redirect()->route('onboarding.step1');
        }

        if ($user->onboarding_step >= 2) {
            return redirect()->route('onboarding.step3');
        }

        return view('onboarding.step2', [
            'user' => $user,
            'popularCities' => $this->popularCities(),
        ]);
    }

    public function storeStep2(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->onboarding_step < 1) {
            return redirect()->route('onboarding.step1');
        }

        $validated = $request->validate([
            'location' => ['required', 'string', 'max:255'],
        ]);

        $user->update([
            'residence' => $validated['location'],
            'onboarding_step' => 2,
        ]);

        return redirect()->route('onboarding.step3');
    }

    public function step3(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->onboarding_step >= self::COMPLETED_STEP) {
            return redirect($this->destinationFor($user));
        }

        if ($user->onboarding_step < 2) {
            return redirect($this->stepRouteFor($user));
        }

        if ($user->onboarding_step >= 3) {
            return redirect()->route('onboarding.step4');
        }

        return view('onboarding.step3', ['user' => $user]);
    }

    public function storeStep3(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->onboarding_step < 2) {
            return redirect($this->stepRouteFor($user));
        }

        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            ]);

            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->profile_photo = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->onboarding_step = 3;
        $user->save();

        return redirect()->route('onboarding.step4');
    }

    public function step4(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->onboarding_step >= self::COMPLETED_STEP) {
            return redirect($this->destinationFor($user));
        }

        if ($user->onboarding_step < 3) {
            return redirect($this->stepRouteFor($user));
        }

        return view('onboarding.step4', ['user' => $user]);
    }

    public function storeStep4(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->onboarding_step < 3) {
            return redirect($this->stepRouteFor($user));
        }

        $validated = $request->validate([
            'professional_title' => ['required', 'string', 'max:255'],
            'workspace_usage_frequency' => ['required', 'in:daily,weekly,monthly'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data = [
            'professional_title' => $validated['professional_title'],
            'workspace_usage_frequency' => $validated['workspace_usage_frequency'],
            'onboarding_step' => self::COMPLETED_STEP,
        ];

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $data['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->update($data);

        return redirect($this->destinationFor($user->fresh()))
            ->with('success', 'Welcome to GridSpace! Your account is ready.');
    }

    private function stepRouteFor($user): string
    {
        return match (true) {
            $user->onboarding_step < 1 => route('onboarding.step1'),
            $user->onboarding_step < 2 => route('onboarding.step2'),
            $user->onboarding_step < 3 => route('onboarding.step3'),
            default => route('onboarding.step4'),
        };
    }

    private function popularCities(): array
    {
        $cities = City::orderBy('name')->pluck('name')->all();

        if (! empty($cities)) {
            return $cities;
        }

        return ['Abuja', 'Lagos', 'Port Harcourt', 'Ibadan', 'Benin', 'Uyo', 'Kaduna'];
    }

    private function destinationFor($user): string
    {
        return $user->isHost()
            ? route('dashboard')
            : route('home');
    }
}
