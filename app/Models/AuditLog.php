<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'action', 'auditable_type', 'auditable_id',
        'metadata', 'ip_address', 'created_at',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'created_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public static function record(
        ?int    $userId,
        string  $action,
        ?string $type = null,
        ?int    $id   = null,
        array   $meta = []
    ): void {
        static::create([
            'user_id'        => $userId,
            'action'         => $action,
            'auditable_type' => $type,
            'auditable_id'   => $id,
            'metadata'       => empty($meta) ? null : $meta,
            'ip_address'     => request()?->ip(),
            'created_at'     => now(),
        ]);
    }
}
