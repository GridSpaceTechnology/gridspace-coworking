<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'gender',
        'marital_status',
        'date_of_birth',
        'residence',
        'local_government_area',
        'state_of_origin',
        'home_town',
        'nationality',
        'religion',
        'company_name',
        'business_description',
        'password',
        'role',
        'approved',
        'onboarding_step',
        'profile_photo',
        'wallet_balance',
        'professional_title',
        'workspace_usage_frequency',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
        ];
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function defaultHomeRoute(): string
    {
        return $this->isAdmin() ? 'admin.index' : 'dashboard';
    }

    public function defaultHomeUrl(bool $absolute = true): string
    {
        return route($this->defaultHomeRoute(), absolute: $absolute);
    }

    public function isHost()
    {
        return $this->role === 'host';
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getDisplayNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function isApproved()
    {
        return $this->approved === true;
    }

    public function isPendingApproval()
    {
        return $this->approved === false;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo
            ? asset('storage/' . $this->profile_photo)
            : null;
    }
}
