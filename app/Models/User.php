<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'otp',
        'otp_expires_at',
        'phone_verified_at',
        'is_verified',
        'profile_photo_path',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'agent_id',
        'agent_referral_code',
        'subscription_name',
        'purchased_date',
        'total_amount_paid',
        'level',
        'profile_completed',
        'upi_id',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expires_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'profile_completed' => 'boolean',
        'date_of_birth' => 'date',
        'purchased_date' => 'datetime',
        'last_login_at' => 'datetime',
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
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'requires_profile_completion' => !$this->isProfileComplete(),
        ];
    }
    
    /**
     * Check if user has completed their profile
     *
     * @return bool
     */
    public function isProfileComplete(): bool
    {
        return !empty($this->name) && 
               !empty($this->email) && 
               $this->phone_verified_at !== null;
    }
    
    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
                    ? Storage::url($this->profile_photo_path)
                    : $this->defaultProfilePhotoUrl();
    }
    
    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the agent who referred this user.
     */
    public function referringAgent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    /**
     * Get the users referred by this user.
     */
    public function referredUsers()
    {
        return $this->hasMany(UserReferral::class, 'referrer_id');
    }

    /**
     * Get the user who referred this user.
     */
    public function referrer()
    {
        return $this->hasOne(UserReferral::class, 'referred_id');
    }

    /**
     * Get the user's subscription plan.
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_name', 'name');
    }
}
