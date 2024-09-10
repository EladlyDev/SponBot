<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Sponsorship;
use App\Models\SponsorshipRequest;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_code',
        'sponsee_limit',
        'preferred_languages',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationship for sponsorship requests sent by the user (sponsee)
    public function sentSponsorshipRequests()
    {
        return $this->hasMany(SponsorshipRequest::class, 'sponsee_id');
    }

    // Relationship for sponsorship requests received by the user (sponsor)
    public function receivedSponsorshipRequests()
    {
        return $this->hasMany(SponsorshipRequest::class, 'sponsor_id');
    }

    // Relationship for users who sponsor others
    public function sponsoredUsers()
    {
        return $this->hasMany(Sponsorship::class, 'sponsor_id');
    }

    // Relationship for the user's sponsor (if any)
    public function sponsor()
    {
        return $this->hasOne(Sponsorship::class, 'sponsee_id');
    }

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
        ];
    }
}
