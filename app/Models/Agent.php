<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone_number',
        'referral_code',
    ];

    /**
     * Get the users referred by this agent (optional relationship).
     */
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referral_code', 'referral_code');
    }
}
