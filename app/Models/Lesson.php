<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'program_id', 'title', 'title_ar', 'description', 'sort_order',
    ];

    public function program()      { return $this->belongsTo(Program::class); }
    public function contentItems() { return $this->hasMany(ContentItem::class); }
    public function progressLogs() { return $this->hasMany(ProgressLog::class); }
}
