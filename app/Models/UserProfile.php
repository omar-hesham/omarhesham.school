<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 'age_group', 'guardian_email', 'consent_status',
        'locale', 'bio', 'avatar_path',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
