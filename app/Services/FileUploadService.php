<?php

namespace App\Services;

use App\Models\ContentItem;
use App\Models\ModerationStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadService
{
    // Strict MIME allowlist — checked after PHP extension check
    private const ALLOWED_MIMES = [
        'pdf'   => ['application/pdf'],
        'image' => ['image/jpeg', 'image/png', 'image/webp'],
        'audio' => ['audio/mpeg', 'audio/mp4', 'audio/ogg', 'audio/wav'],
    ];

    public function store(UploadedFile $file, string $type, int $uploaderId): ContentItem
    {
        $allowedMimes = self::ALLOWED_MIMES[$type] ?? [];

        if (!in_array($file->getMimeType(), $allowedMimes, true)) {
            throw new \InvalidArgumentException(
                "File type not allowed for {$type}. Got: {$file->getMimeType()}"
            );
        }

        $maxBytes = config('platform.upload_max_mb', 20) * 1024 * 1024;
        if ($file->getSize() > $maxBytes) {
            throw new \InvalidArgumentException('File exceeds maximum size limit.');
        }

        // Store in private disk (never publicly accessible)
        $path = $file->storeAs(
            "uploads/{$type}s/" . date('Y/m'),
            Str::random(32) . '.' . $file->getClientOriginalExtension(),
            'local'
        );

        $item = ContentItem::create([
            'uploaded_by'    => $uploaderId,
            'type'           => $type,
            'title'          => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'file_path'      => $path,
            'mime_type'      => $file->getMimeType(),
            'is_quarantined' => true,
        ]);

        ModerationStatus::create([
            'content_item_id' => $item->id,
            'status'          => 'pending',
        ]);

        return $item;
    }
}
