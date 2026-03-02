<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'currency', 'type',
        'stripe_pi_id', 'status', 'donor_name',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
