<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherStudentLink extends Model
{
    protected $fillable = ['teacher_id', 'student_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
}
