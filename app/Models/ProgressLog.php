<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'lesson_id', 'surah_number', 'ayah_from',
        'ayah_to', 'quality_score', 'notes', 'logged_at', 'approved_by',
    ];

    protected $casts = ['logged_at' => 'date'];

    public function student()  { return $this->belongsTo(User::class, 'user_id'); }
    public function lesson()   { return $this->belongsTo(Lesson::class); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
