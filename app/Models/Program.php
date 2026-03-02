<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by', 'title', 'title_ar', 'slug',
        'description', 'description_ar', 'access_level', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function creator()     { return $this->belongsTo(User::class, 'created_by'); }
    public function lessons()     { return $this->hasMany(Lesson::class); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }

    public function getRouteKeyName(): string { return 'slug'; }
}
