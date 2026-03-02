<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\ModerationStatus;
use App\Models\AuditLog;
use App\Services\YoutubeService;
use Illuminate\Http\Request;

class ContentModerationController extends Controller
{
    public function __construct(private YoutubeService $youtube) {}

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $items = ContentItem::with('moderationStatus', 'uploader', 'lesson.program')
            ->whereHas('moderationStatus', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('admin.moderation.index', compact('items', 'status'));
    }

    public function preview(ContentItem $item)
    {
        $embedUrl = $item->type === 'youtube' && $item->youtube_id
            ? $this->youtube->embedUrl($item->youtube_id)
            : null;

        return view('admin.moderation.preview', compact('item', 'embedUrl'));
    }

    public function approve(ContentItem $item)
    {
        ModerationStatus::where('content_item_id', $item->id)->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $item->update(['is_quarantined' => false]);

        AuditLog::record(auth()->id(), 'content_approved', 'ContentItem', $item->id);

        return back()->with('status', __('admin.content_approved'));
    }

    public function reject(Request $request, ContentItem $item)
    {
        $data = $request->validate([
            'rejection_note' => ['required', 'string', 'max:500'],
        ]);

        ModerationStatus::where('content_item_id', $item->id)->update([
            'status'         => 'rejected',
            'reviewed_by'    => auth()->id(),
            'reviewed_at'    => now(),
            'rejection_note' => $data['rejection_note'],
        ]);

        AuditLog::record(auth()->id(), 'content_rejected', 'ContentItem', $item->id, [
            'reason' => $data['rejection_note'],
        ]);

        return back()->with('status', __('admin.content_rejected'));
    }
}
