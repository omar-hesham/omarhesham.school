<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function library()
    {
        $user = auth()->user();
        $isMinor = $user->profile?->age_group === 'child';

        $query = ContentItem::with('moderationStatus', 'uploader')->latest();

        // Minors see approved content only
        if ($isMinor) {
            $query->whereHas('moderationStatus', fn($q) => $q->where('status', 'approved'))
                  ->where('is_quarantined', false);
        }

        $items = $query->paginate(20);

        return view('content.library', compact('items'));
    }

    // Serve private files securely through PHP (never expose storage path)
    public function stream(ContentItem $item)
    {
        $user = auth()->user();
        $isMinor = $user->profile?->age_group === 'child';

        // Minors: only approved, non-quarantined items
        if ($isMinor) {
            abort_unless(
                $item->moderationStatus?->status === 'approved' && !$item->is_quarantined,
                403
            );
        }

        // YouTube items don't need streaming
        if ($item->type === 'youtube') {
            abort(400, 'YouTube items are rendered client-side.');
        }

        abort_unless($item->file_path && Storage::disk('local')->exists($item->file_path), 404);

        $mimeMap = [
            'pdf'   => 'application/pdf',
            'image' => $item->mime_type,
            'audio' => $item->mime_type,
        ];

        return response()->stream(function () use ($item) {
            $stream = Storage::disk('local')->readStream($item->file_path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type'           => $mimeMap[$item->type] ?? 'application/octet-stream',
            'Content-Disposition'    => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'private, no-store',
        ]);
    }
}
