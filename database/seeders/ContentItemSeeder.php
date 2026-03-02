<?php

namespace Database\Seeders;

use App\Models\ContentItem;
use App\Models\ModerationStatus;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentItemSeeder extends Seeder
{
    private array $content = [
        // YouTube — approved
        [
            'title'          => 'Makharij Al-Huruf — Complete Guide',
            'type'           => 'youtube',
            'youtube_id'     => 'HcX-KarU3EU',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 1,
            'lesson_index'   => 0,
        ],
        [
            'title'          => 'Al-Fatiha Recitation — Sheikh Mishary',
            'type'           => 'youtube',
            'youtube_id'     => 'zjqoYWHQiGM',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 0,
            'lesson_index'   => 1,
        ],
        [
            'title'          => 'Understanding Noon Sakinah Rules',
            'type'           => 'youtube',
            'youtube_id'     => 'H7vUy9f2H8s',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 1,
            'lesson_index'   => 2,
        ],
        [
            'title'          => 'Children\'s Quran Learning — Fun Approach',
            'type'           => 'youtube',
            'youtube_id'     => 'LkWFvDsUl9I',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 2,
            'lesson_index'   => 0,
        ],
        // YouTube — pending
        [
            'title'          => 'Advanced Hifz Techniques — New Upload',
            'type'           => 'youtube',
            'youtube_id'     => 'dQw4w9WgXcQ',
            'is_quarantined' => true,
            'mod_status'     => 'pending',
            'program_index'  => 4,
            'lesson_index'   => 0,
        ],

        // PDF — approved
        [
            'title'          => 'Tajweed Rules Reference Sheet (PDF)',
            'type'           => 'pdf',
            'file_path'      => 'seed/tajweed-rules.pdf',
            'mime_type'      => 'application/pdf',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 1,
            'lesson_index'   => 0,
        ],
        [
            'title'          => 'Juz 30 Memorization Workbook',
            'type'           => 'pdf',
            'file_path'      => 'seed/juz30-workbook.pdf',
            'mime_type'      => 'application/pdf',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 0,
            'lesson_index'   => 0,
        ],
        [
            'title'          => 'Children\'s Alphabet Coloring Sheets',
            'type'           => 'pdf',
            'file_path'      => 'seed/arabic-alphabet.pdf',
            'mime_type'      => 'application/pdf',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 2,
            'lesson_index'   => 0,
        ],
        // PDF — pending
        [
            'title'          => 'New Lesson Notes — Advanced Hifz',
            'type'           => 'pdf',
            'file_path'      => 'seed/advanced-notes.pdf',
            'mime_type'      => 'application/pdf',
            'is_quarantined' => true,
            'mod_status'     => 'pending',
            'program_index'  => 4,
            'lesson_index'   => 1,
        ],
        // PDF — rejected
        [
            'title'          => 'Rejected Upload Example',
            'type'           => 'pdf',
            'file_path'      => 'seed/rejected.pdf',
            'mime_type'      => 'application/pdf',
            'is_quarantined' => true,
            'mod_status'     => 'rejected',
            'program_index'  => 0,
            'lesson_index'   => 0,
        ],

        // Audio — approved
        [
            'title'          => 'Al-Fatiha — Recitation Audio',
            'type'           => 'audio',
            'file_path'      => 'seed/al-fatiha.mp3',
            'mime_type'      => 'audio/mpeg',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 0,
            'lesson_index'   => 1,
        ],
        [
            'title'          => 'Al-Ikhlas — Slow Recitation for Memorization',
            'type'           => 'audio',
            'file_path'      => 'seed/al-ikhlas.mp3',
            'mime_type'      => 'audio/mpeg',
            'is_quarantined' => false,
            'mod_status'     => 'approved',
            'program_index'  => 0,
            'lesson_index'   => 2,
        ],
        // Audio — pending
        [
            'title'          => 'Teacher Recitation — Uploaded Today',
            'type'           => 'audio',
            'file_path'      => 'seed/new-recitation.mp3',
            'mime_type'      => 'audio/mpeg',
            'is_quarantined' => true,
            'mod_status'     => 'pending',
            'program_index'  => 1,
            'lesson_index'   => 1,
        ],
    ];

    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        $admin    = User::where('role', 'admin')->first();

        // Load all programs with their lessons ordered by sort_order
        $programs = Program::with(['lessons' => fn ($q) => $q->orderBy('sort_order')])->get();

        foreach ($this->content as $data) {
            $uploader = $teachers->random();
            $program  = $programs->get($data['program_index']);
            $lesson   = $program?->lessons->get($data['lesson_index']);

            $item = ContentItem::updateOrCreate(
                ['title' => $data['title']],
                [
                    'uploaded_by'    => $uploader->id,
                    'lesson_id'      => $lesson?->id,
                    'type'           => $data['type'],
                    'file_path'      => $data['file_path'] ?? null,
                    'mime_type'      => $data['mime_type'] ?? null,
                    'youtube_id'     => $data['youtube_id'] ?? null,
                    'is_quarantined' => $data['is_quarantined'],
                ]
            );

            ModerationStatus::updateOrCreate(
                ['content_item_id' => $item->id],
                [
                    'status'         => $data['mod_status'],
                    'reviewed_by'    => $data['mod_status'] !== 'pending' ? $admin->id : null,
                    'reviewed_at'    => $data['mod_status'] !== 'pending' ? now()->subDays(rand(1, 5)) : null,
                    'rejection_note' => $data['mod_status'] === 'rejected'
                        ? 'Content does not meet platform standards.'
                        : null,
                ]
            );
        }

        $this->command->info('  → ContentItemSeeder: ' . count($this->content) . ' content items created.');
    }
}
