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
        'icon',
        'caption_limit',
    ];

    /**
     * Get the users associated with this subscription plan.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'subscription_name', 'name');
    }
}
