<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModerationStatus extends Model
{
    protected $fillable = [
        'content_item_id', 'status', 'reviewed_by', 'reviewed_at', 'rejection_note',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function contentItem() { return $this->belongsTo(ContentItem::class); }
    public function reviewer()    { return $this->belongsTo(User::class, 'reviewed_by'); }
}
