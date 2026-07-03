@extends('layouts.dashboard')

@section('title', 'Profile - GridSpace')

@push('head')
<style>
    .profile-toggle {
        position: relative;
        display: inline-flex;
        height: 1.5rem;
        width: 2.75rem;
        flex-shrink: 0;
        cursor: pointer;
        border-radius: 9999px;
        border: 2px solid transparent;
        transition: background-color 0.2s ease-in-out;
    }
    .profile-toggle.active { background-color: #ff5a1f; }
    .profile-toggle.inactive { background-color: #e0e3e5; }
    .profile-toggle-thumb {
        pointer-events: none;
        display: inline-block;
        height: 1.25rem;
        width: 1.25rem;
        border-radius: 9999px;
        background-color: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        transition: transform 0.2s ease-in-out;
    }
    .profile-toggle.active .profile-toggle-thumb { transform: translateX(1.25rem); }
    .profile-toggle.inactive .profile-toggle-thumb { transform: translateX(0); }
</style>
@endpush

@section('content')
@php
    $roleLabel = $user->isAdmin() ? 'Admin' : ($user->isHost() ? 'Host' : 'Guest');
    $emailVerified = $user->email_verified_at !== null;
    $phoneVerified = filled($user->phone);
    $avatarUrl = $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->display_name) . '&background=ff5a1f&color=fff&size=256';
@endphp

@if($user->isHost())
    @include('host.partials.subnav')
@endif

<div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-stack-lg">
    <div>
        <h1 class="font-manrope text-3xl md:text-4xl font-bold text-on-surface tracking-tight">Profile</h1>
        <p class="font-inter text-on-surface-variant mt-1">Manage your personal information and account settings</p>
    </div>
    <button type="button" onclick="document.getElementById('profile-form').scrollIntoView({ behavior: 'smooth' }); document.getElementById('firstname').focus();"
        class="flex items-center justify-center gap-2 px-6 py-2 border border-primary text-primary font-inter font-semibold rounded-lg hover:bg-primary-fixed transition-all shrink-0">
        <span class="material-symbols-outlined text-[20px]">edit</span>
        Edit Profile
    </button>
</div>

@if(session('status') === 'profile-updated')
    <div class="mb-stack-md rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 font-inter">
        Your profile has been updated successfully.
    </div>
@endif

@if(session('status') === 'password-updated')
    <div class="mb-stack-md rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 font-inter">
        Your password has been updated successfully.
    </div>
@endif

@if($errors->any() && !$errors->updatePassword->any() && !$errors->userDeletion->any())
    <div class="mb-stack-md rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 font-inter">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-stack-md">
    {{-- Personal Information --}}
    <section class="bg-white border border-outline-variant rounded-xl p-stack-md shadow-sm">
        <h2 class="font-manrope text-xl font-semibold text-on-surface mb-stack-md">Personal Information</h2>

        <form id="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-shrink-0 flex flex-col items-center gap-4">
                    <label for="profile_photo" class="relative group cursor-pointer">
                        <img id="profile-preview" alt="{{ $user->display_name }}" src="{{ $avatarUrl }}"
                            class="w-32 h-32 rounded-full object-cover shadow-md ring-4 ring-surface-container-low">
                        <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-white">photo_camera</span>
                        </div>
                    </label>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                    <p class="font-mono text-[10px] uppercase tracking-wider text-on-surface-variant">Click to change photo</p>
                    @error('profile_photo')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-stack-md">
                    <div class="space-y-1">
                        <label for="firstname" class="text-sm font-semibold text-secondary block font-inter">First Name</label>
                        <input id="firstname" name="firstname" type="text" value="{{ old('firstname', $user->firstname) }}" required
                            class="w-full p-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container focus:border-transparent font-inter text-sm @error('firstname') border-red-400 @enderror">
                        @error('firstname')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1">
                        <label for="lastname" class="text-sm font-semibold text-secondary block font-inter">Last Name</label>
                        <input id="lastname" name="lastname" type="text" value="{{ old('lastname', $user->lastname) }}" required
                            class="w-full p-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container focus:border-transparent font-inter text-sm @error('lastname') border-red-400 @enderror">
                        @error('lastname')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <label for="email" class="text-sm font-semibold text-secondary block font-inter">Email Address</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">mail</span>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                class="w-full pl-10 pr-10 p-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container focus:border-transparent font-inter text-sm @error('email') border-red-400 @enderror">
                            @if($emailVerified)
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-[#2E7D32] text-[20px]">check_circle</span>
                            @endif
                        </div>
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <label for="phone" class="text-sm font-semibold text-secondary block font-inter">Phone Number</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">call</span>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}"
                                class="w-full pl-10 pr-10 p-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container focus:border-transparent font-inter text-sm @error('phone') border-red-400 @enderror"
                                placeholder="09123456789">
                            @if($phoneVerified)
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-[#2E7D32] text-[20px]">check_circle</span>
                            @endif
                        </div>
                        @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2 flex items-center justify-between pt-2">
                        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide">Role: {{ $roleLabel }}</p>
                        <button type="submit" class="bg-primary-container text-white px-6 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>

    {{-- Account Verification --}}
    <section class="bg-white border border-outline-variant rounded-xl p-stack-md shadow-sm">
        <h2 class="font-manrope text-xl font-semibold text-on-surface mb-stack-md">Account Verification</h2>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="p-2 bg-surface rounded-lg shrink-0">
                        <span class="material-symbols-outlined text-secondary">mail</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-inter font-semibold text-on-surface">Email Verification</p>
                        <p class="text-xs text-on-surface-variant font-inter">Verify your email address</p>
                    </div>
                </div>
                @if($emailVerified)
                    <span class="bg-[#E8F5E9] text-[#2E7D32] px-2 py-0.5 rounded-full font-mono text-[10px] uppercase tracking-wider shrink-0">verified</span>
                @else
                    <form method="POST" action="{{ route('verification.send') }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="bg-primary-container text-white px-4 py-1.5 rounded-lg font-inter font-semibold text-sm hover:opacity-90 transition-all">Verify</button>
                    </form>
                @endif
            </div>

            <div class="flex items-center justify-between p-4 border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="p-2 bg-surface rounded-lg shrink-0">
                        <span class="material-symbols-outlined text-secondary">call</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-inter font-semibold text-on-surface">Phone Verification</p>
                        <p class="text-xs text-on-surface-variant font-inter">Verify your phone number</p>
                    </div>
                </div>
                @if($phoneVerified)
                    <span class="bg-[#E8F5E9] text-[#2E7D32] px-2 py-0.5 rounded-full font-mono text-[10px] uppercase tracking-wider shrink-0">verified</span>
                @else
                    <span class="bg-surface-container text-on-surface-variant px-2 py-0.5 rounded-full font-mono text-[10px] uppercase tracking-wider shrink-0">pending</span>
                @endif
            </div>

            <div class="flex items-center justify-between p-4 border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="p-2 bg-surface rounded-lg shrink-0">
                        <span class="material-symbols-outlined text-secondary">shield</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-inter font-semibold text-on-surface">Identity Verification</p>
                        <p class="text-xs text-on-surface-variant font-inter">Upload government ID for enhanced security</p>
                    </div>
                </div>
                <button type="button" disabled class="bg-surface-variant text-on-surface-variant px-4 py-1.5 rounded-lg font-inter font-semibold text-sm cursor-not-allowed shrink-0">Coming Soon</button>
            </div>
        </div>
    </section>

    {{-- Security --}}
    <section class="bg-white border border-outline-variant rounded-xl p-stack-md shadow-sm">
        <h2 class="font-manrope text-xl font-semibold text-on-surface mb-stack-md">Security</h2>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-outline-variant rounded-lg gap-4">
            <div class="flex items-center gap-4">
                <div class="p-2 bg-surface rounded-lg shrink-0">
                    <span class="material-symbols-outlined text-secondary">lock</span>
                </div>
                <div>
                    <p class="font-inter font-semibold text-on-surface">Password</p>
                    <p class="text-xs text-on-surface-variant font-inter">Keep your account secure with a strong password</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('password-panel').classList.toggle('hidden')"
                class="px-4 py-1.5 border border-outline text-on-surface font-inter font-semibold text-sm rounded-lg hover:bg-surface-container-low transition-colors shrink-0">
                Change Password
            </button>
        </div>

        <div id="password-panel" class="hidden mt-stack-md pt-stack-md border-t border-outline-variant">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4 max-w-lg">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label for="current_password" class="text-sm font-semibold text-secondary block font-inter">Current Password</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                        class="w-full p-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container focus:border-transparent font-inter text-sm">
                    @if($errors->updatePassword->has('current_password'))
                        <p class="text-sm text-red-600 mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                    @endif
                </div>
                <div class="space-y-1">
                    <label for="password" class="text-sm font-semibold text-secondary block font-inter">New Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                        class="w-full p-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container focus:border-transparent font-inter text-sm">
                    @if($errors->updatePassword->has('password'))
                        <p class="text-sm text-red-600 mt-1">{{ $errors->updatePassword->first('password') }}</p>
                    @endif
                </div>
                <div class="space-y-1">
                    <label for="password_confirmation" class="text-sm font-semibold text-secondary block font-inter">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                        class="w-full p-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container focus:border-transparent font-inter text-sm">
                    @if($errors->updatePassword->has('password_confirmation'))
                        <p class="text-sm text-red-600 mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                    @endif
                </div>
                <button type="submit" class="bg-primary-container text-white px-6 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors">
                    Update Password
                </button>
            </form>
        </div>
    </section>

    {{-- Notification Preferences --}}
    <section class="bg-white border border-outline-variant rounded-xl p-stack-md shadow-sm">
        <h2 class="font-manrope text-xl font-semibold text-on-surface mb-stack-md">Notification Preferences</h2>
        <div class="space-y-stack-md">
            <div>
                <h3 class="text-sm font-bold text-secondary uppercase tracking-wider mb-4 font-inter">Communication</h3>
                <div class="space-y-4">
                    @foreach([
                        ['icon' => 'mail', 'label' => 'Email Notifications', 'active' => true],
                        ['icon' => 'forum', 'label' => 'SMS Notifications', 'active' => true],
                        ['icon' => 'notifications_active', 'label' => 'Push Notifications', 'active' => true],
                    ] as $pref)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-on-surface-variant">{{ $pref['icon'] }}</span>
                                <span class="font-inter font-medium text-on-surface">{{ $pref['label'] }}</span>
                            </div>
                            <button type="button" class="profile-toggle {{ $pref['active'] ? 'active' : 'inactive' }}" onclick="togglePref(this)" aria-label="Toggle {{ $pref['label'] }}">
                                <span class="profile-toggle-thumb"></span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="pt-stack-sm border-t border-outline-variant/50">
                <h3 class="text-sm font-bold text-secondary uppercase tracking-wider mb-4 font-inter">Content</h3>
                <div class="space-y-4">
                    @foreach([
                        ['label' => 'Marketing emails and promotions', 'active' => false],
                        ['label' => 'Booking reminders and updates', 'active' => true],
                        ['label' => 'Messages from hosts', 'active' => true],
                    ] as $pref)
                        <div class="flex items-center justify-between gap-4">
                            <span class="font-inter font-medium text-on-surface">{{ $pref['label'] }}</span>
                            <button type="button" class="profile-toggle {{ $pref['active'] ? 'active' : 'inactive' }}" onclick="togglePref(this)" aria-label="Toggle {{ $pref['label'] }}">
                                <span class="profile-toggle-thumb"></span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Actions --}}
    <div class="space-y-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 hover:bg-surface-container-low transition-all font-inter font-semibold text-on-surface shadow-sm active:scale-[0.99]">
                <span class="material-symbols-outlined rotate-180">logout</span>
                Logout
            </button>
        </form>
        @unless(auth()->user()->isAdmin())
        <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
            class="w-full bg-white border border-red-200 p-4 rounded-xl flex items-center gap-3 hover:bg-red-50 transition-all font-inter font-semibold text-red-700 shadow-sm active:scale-[0.99]">
            <span class="material-symbols-outlined">delete_forever</span>
            Delete Account
        </button>
        @endunless
    </div>
</div>

@if(! auth()->user()->isAdmin())
{{-- Delete Account Modal --}}
<div id="delete-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50" onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 border border-outline-variant">
        <h2 class="font-manrope text-xl font-bold text-on-surface mb-2">Delete Account</h2>
        <p class="font-inter text-sm text-on-surface-variant mb-6">
            Once your account is deleted, all of its resources and data will be permanently removed. Enter your password to confirm.
        </p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <div class="space-y-1 mb-6">
                <label for="delete_password" class="text-sm font-semibold text-secondary block font-inter">Password</label>
                <input id="delete_password" name="password" type="password" required
                    class="w-full p-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary-container focus:border-transparent font-inter text-sm {{ $errors->userDeletion->has('password') ? 'border-red-400' : '' }}"
                    placeholder="Enter your password">
                @if($errors->userDeletion->has('password'))
                    <p class="text-sm text-red-600 mt-1">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="px-4 py-2 border border-outline-variant rounded-lg font-inter font-semibold text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-inter font-semibold text-sm hover:bg-red-700 transition-colors">
                    Delete Account
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    function togglePref(btn) {
        const isActive = btn.classList.contains('active');
        btn.classList.toggle('active', !isActive);
        btn.classList.toggle('inactive', isActive);
    }

    const photoInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('profile-preview');
    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                photoPreview.src = URL.createObjectURL(file);
            }
        });
    }

    @if($errors->updatePassword->any())
        document.getElementById('password-panel')?.classList.remove('hidden');
    @endif

    @if($errors->userDeletion->any() && ! auth()->user()->isAdmin())
        document.getElementById('delete-modal')?.classList.remove('hidden');
    @endif
</script>
@endpush
