<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Agent extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'date_of_birth',
        'referral_code',
        'otp',
        'otp_expires_at',
        'is_verified',
        'phone_verified_at',
        'profile_completed',
        'wallet_balance',
        'total_earnings',
        'total_withdrawals',
        'upi_id',
        'bank_account_number',
        'ifsc_code',
        'account_holder_name',
        'address',
        'city',
        'state',
        'pincode',
        'profile_image',
        'aadhar_number',
        'pan_number',
        'gst_number',
        'bio',
        'status',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'otp',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'profile_completed' => 'boolean',
        'wallet_balance' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_withdrawals' => 'decimal:2',
        'otp_expires_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

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
        return [];
    }

    /**
     * Get the users referred by this agent.
     */
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'agent_id');
    }

    /**
     * Get the withdrawal requests for this agent.
     */
    public function withdrawalRequests()
    {
        return $this->hasMany(AgentWithdrawalRequest::class);
    }

    /**
     * Get the plan subscriptions for this agent.
     */
    public function planSubscriptions()
    {
        return $this->hasMany(AgentPlanSubscription::class);
    }

    /**
     * Get the active plan subscription for this agent.
     */
    public function activePlanSubscription()
    {
        return $this->hasMany(AgentPlanSubscription::class)->where('status', 'active');
    }

    /**
     * Get the current plan for this agent.
     */
    public function currentPlan()
    {
        $subscription = $this->activePlanSubscription()->latest('started_at')->first();
        return $subscription ? $subscription->plan : null;
    }

    /**
     * Check if agent has an active plan
     */
    public function hasActivePlan()
    {
        return $this->activePlanSubscription()->exists();
    }

    /**
     * Get current earning rate based on active plan
     */
    public function getCurrentEarningRate()
    {
        $subscription = $this->activePlanSubscription;
        if (!$subscription) {
            return 0; // No active plan
        }
        
        return $subscription->getCurrentEarningRate();
    }

    /**
     * Generate a unique referral code for the agent.
     */
    public static function generateReferralCode()
    {
        do {
            $code = 'AG' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Get the agent's current balance (earnings - withdrawals).
     */
    public function getBalanceAttribute()
    {
        return $this->total_earnings - $this->total_withdrawals;
    }

    public function walletTransactions()
    {
        return $this->hasMany(AgentWalletTransaction::class);
    }
}
