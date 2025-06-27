<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'fee',
        'final_withdrawal_amount',
        'upi_id',
        'service_type',
        'status',
        'request_date',
        'approved_at',
        'admin_id',
        'remarks',
    ];

    /**
     * Get the user that owns the withdrawal request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved the withdrawal request.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
