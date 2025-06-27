<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'captcha_per_day',
        'min_withdrawal_limit',
        'cost',
        'earning_type',
        'plan_type',
        'icon',
        'image',
        'caption_limit',
        'earnings',
        'min_daily_earning',
    ];

    protected $casts = [
        'earnings' => 'array',
    ];

    /**
     * Get the users associated with this subscription plan.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'subscription_name', 'name');
    }
}
