<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uploaded_by', 'lesson_id', 'type', 'title',
        'file_path', 'mime_type', 'youtube_id', 'is_quarantined',
    ];

    protected $casts = ['is_quarantined' => 'boolean'];

    public function uploader()        { return $this->belongsTo(User::class, 'uploaded_by'); }
    public function lesson()          { return $this->belongsTo(Lesson::class); }
    public function moderationStatus(){ return $this->hasOne(ModerationStatus::class); }
}
