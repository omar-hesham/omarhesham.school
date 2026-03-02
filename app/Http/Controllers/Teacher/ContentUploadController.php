<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadContentRequest;
use App\Models\ContentItem;
use App\Models\AuditLog;
use App\Services\FileUploadService;
use App\Services\YoutubeService;
use Illuminate\Http\Request;

class ContentUploadController extends Controller
{
    public function __construct(
        private FileUploadService $uploader,
        private YoutubeService    $youtube
    ) {}

    public function showForm()
    {
        return view('teacher.content.upload');
    }

    public function upload(UploadContentRequest $request)
    {
        $data = $request->validated();
        $type = $data['type']; // pdf | image | audio

        try {
            $item = $this->uploader->store(
                $request->file('file'),
                $type,
                auth()->id()
            );

            if (!empty($data['lesson_id'])) {
                $item->update(['lesson_id' => $data['lesson_id']]);
            }

            AuditLog::record(auth()->id(), 'content_uploaded', 'ContentItem', $item->id);

            return redirect()->route('teacher.dashboard')
                ->with('status', __('teacher.upload_success_pending'));

        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        }
    }

    public function addYoutube(Request $request)
    {
        $data = $request->validate([
            'youtube_url' => ['required', 'string', 'max:200'],
            'title'       => ['required', 'string', 'max:255'],
            'lesson_id'   => ['nullable', 'exists:lessons,id'],
        ]);

        $videoId = $this->youtube->extractVideoId($data['youtube_url']);

        if (!$videoId) {
            return back()->withErrors(['youtube_url' => __('teacher.invalid_youtube_url')]);
        }

        $item = ContentItem::create([
            'uploaded_by'    => auth()->id(),
            'type'           => 'youtube',
            'title'          => htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8'),
            'youtube_id'     => $videoId,
            'lesson_id'      => $data['lesson_id'] ?? null,
            'is_quarantined' => true,
        ]);

        \App\Models\ModerationStatus::create([
            'content_item_id' => $item->id,
            'status'          => 'pending',
        ]);

        AuditLog::record(auth()->id(), 'youtube_added', 'ContentItem', $item->id);

        return redirect()->route('teacher.dashboard')
            ->with('status', __('teacher.youtube_added_pending'));
    }

    public function destroy(ContentItem $item)
    {
        abort_unless($item->uploaded_by === auth()->id(), 403);
        abort_if($item->moderationStatus?->status === 'approved', 403, __('teacher.cannot_delete_approved'));

        if ($item->file_path) {
            \Storage::disk('local')->delete($item->file_path);
        }

        $item->delete();

        AuditLog::record(auth()->id(), 'content_deleted', 'ContentItem', $item->id);

        return back()->with('status', __('teacher.content_deleted'));
    }
}
