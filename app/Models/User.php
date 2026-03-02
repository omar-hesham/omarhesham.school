<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_banned', 'ban_reason',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_banned'         => 'boolean',
        'password'          => 'hashed',
    ];

    public function profile()        { return $this->hasOne(UserProfile::class); }
    public function progressLogs()   { return $this->hasMany(ProgressLog::class); }
    public function enrollments()    { return $this->hasMany(Enrollment::class); }
    public function donations()      { return $this->hasMany(Donation::class); }
    public function consentRecords() { return $this->hasMany(ConsentRecord::class); }
    public function studentLinks()   { return $this->hasMany(TeacherStudentLink::class, 'teacher_id'); }
    public function teacherLinks()   { return $this->hasMany(TeacherStudentLink::class, 'student_id'); }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            UserProfile::create(['user_id' => $user->id]);
        });
    }
}
