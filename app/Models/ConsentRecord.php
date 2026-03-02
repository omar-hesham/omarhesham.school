<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsentRecord extends Model
{
    protected $fillable = [
        'user_id', 'consent_token', 'action', 'guardian_email', 'responded_at',
    ];

    protected $casts = ['responded_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
}
