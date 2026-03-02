<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    private array $programs = [
        [
            'title'          => 'Hifz Al-Quran — Beginners (Juz 30)',
            'title_ar'       => 'حفظ القرآن للمبتدئين — الجزء الثلاثون',
            'description'    => 'The perfect starting point for any memorization journey. Focus on the short surahs of Juz 30 with proper Tajweed from day one. Includes audio recitations and daily tracking.',
            'description_ar' => 'نقطة البداية المثالية لرحلة الحفظ. تركيز على سور الجزء الثلاثين القصيرة مع تجويد صحيح من اليوم الأول.',
            'access_level'   => 'free',
            'is_published'   => true,
            'lessons'        => [
                ['title' => 'Introduction to Tajweed',      'title_ar' => 'مقدمة في التجويد'],
                ['title' => 'Surah Al-Fatiha',              'title_ar' => 'سورة الفاتحة'],
                ['title' => 'Surah Al-Ikhlas',              'title_ar' => 'سورة الإخلاص'],
                ['title' => 'Surah Al-Falaq & Al-Nas',      'title_ar' => 'سورة الفلق والناس'],
                ['title' => 'Surah Al-Asr & Al-Kawthar',   'title_ar' => 'سورة العصر والكوثر'],
            ],
        ],
        [
            'title'          => 'Tajweed Mastery — Full Course',
            'title_ar'       => 'إتقان التجويد — الدورة الكاملة',
            'description'    => 'A comprehensive Tajweed course covering all rules from Makharij Al-Huruf to Waqf and Ibtida. Suitable for all ages. Includes quizzes and certification.',
            'description_ar' => 'دورة شاملة في التجويد تغطي جميع الأحكام من مخارج الحروف إلى الوقف والابتداء.',
            'access_level'   => 'free',
            'is_published'   => true,
            'lessons'        => [
                ['title' => 'Makharij Al-Huruf (Letters\' Origins)',  'title_ar' => 'مخارج الحروف'],
                ['title' => 'Sifaat Al-Huruf (Letters\' Attributes)', 'title_ar' => 'صفات الحروف'],
                ['title' => 'Ahkam Al-Nun Al-Sakinah',               'title_ar' => 'أحكام النون الساكنة'],
                ['title' => 'Ahkam Al-Meem Al-Sakinah',              'title_ar' => 'أحكام الميم الساكنة'],
                ['title' => 'Al-Madd (Elongation Rules)',             'title_ar' => 'أحكام المد'],
                ['title' => 'Waqf and Ibtida',                       'title_ar' => 'الوقف والابتداء'],
            ],
        ],
        [
            'title'          => 'Quran for Children — Interactive Program',
            'title_ar'       => 'القرآن للأطفال — البرنامج التفاعلي',
            'description'    => 'A fun, age-appropriate program for children aged 6–12. Uses stories, games, and short memorization goals. All content is teacher-approved and child-safe.',
            'description_ar' => 'برنامج ممتع ومناسب للأطفال من 6 إلى 12 سنة باستخدام القصص والألعاب.',
            'access_level'   => 'free',
            'is_published'   => true,
            'lessons'        => [
                ['title' => 'Learn the Arabic Alphabet',   'title_ar' => 'تعلم الحروف العربية'],
                ['title' => 'Surah Al-Fatiha (for Kids)',  'title_ar' => 'سورة الفاتحة للأطفال'],
                ['title' => 'Short Surahs Song & Listen',  'title_ar' => 'السور القصيرة — استمع وردد'],
                ['title' => 'My First Ayahs',              'title_ar' => 'أولى آياتي'],
            ],
        ],
        [
            'title'          => 'Hifz Al-Quran — Intermediate (Juz 28–30)',
            'title_ar'       => 'حفظ القرآن المتوسط — الأجزاء 28-30',
            'description'    => 'Expand your memorization from Juz 30 into Juz 29 and 28. Weekly review sessions with your assigned teacher, plus progress certification on completion.',
            'description_ar' => 'توسيع الحفظ من الجزء 30 إلى الأجزاء 29 و28 مع مراجعات أسبوعية مع المعلم.',
            'access_level'   => 'premium',
            'is_published'   => true,
            'lessons'        => [
                ['title' => 'Review: Juz 30 Mastery Test',    'title_ar' => 'مراجعة: اختبار إتقان الجزء 30'],
                ['title' => 'Juz 29 — Block 1 (Al-Mulk)',    'title_ar' => 'الجزء 29 — القسم الأول'],
                ['title' => 'Juz 29 — Block 2 (Al-Haqqah)',  'title_ar' => 'الجزء 29 — القسم الثاني'],
                ['title' => 'Juz 28 — Block 1 (Al-Mujadila)','title_ar' => 'الجزء 28 — القسم الأول'],
                ['title' => 'Juz 28 — Weekly Review',         'title_ar' => 'الجزء 28 — المراجعة الأسبوعية'],
            ],
        ],
        [
            'title'          => 'Advanced Hifz — Full Quran Program',
            'title_ar'       => 'برنامج الحفظ المتقدم — القرآن الكريم كاملاً',
            'description'    => 'The complete Quran memorization program for dedicated students. Includes a personal teacher, daily review schedule, monthly evaluations, and an Ijazah-track certificate.',
            'description_ar' => 'برنامج حفظ القرآن الكريم كاملاً للطلاب المتفانين مع معلم شخصي وجدول مراجعة يومي.',
            'access_level'   => 'premium',
            'is_published'   => true,
            'lessons'        => [
                ['title' => 'Program Orientation & Goal Setting',  'title_ar' => 'توجيه البرنامج وتحديد الأهداف'],
                ['title' => 'Juz 1 — Al-Baqarah (Part 1)',        'title_ar' => 'الجزء الأول — البقرة (1)'],
                ['title' => 'Juz 1 — Al-Baqarah (Part 2)',        'title_ar' => 'الجزء الأول — البقرة (2)'],
                ['title' => 'Monthly Evaluation — Juz 1',         'title_ar' => 'التقييم الشهري — الجزء الأول'],
                ['title' => 'Juz 2 — Al-Baqarah (Continued)',     'title_ar' => 'الجزء الثاني — البقرة (تابع)'],
            ],
        ],
    ];

    public function run(): void
    {
        $admin       = User::where('role', 'admin')->first();
        $lessonCount = 0;

        foreach ($this->programs as $i => $data) {
            $lessons = $data['lessons'];
            unset($data['lessons']);

            $slug    = Str::slug($data['title']) . '-' . ($i + 1);
            $program = Program::updateOrCreate(
                ['slug' => $slug],
                [
                    ...$data,
                    'slug'       => $slug,
                    'created_by' => $admin->id,
                ]
            );

            foreach ($lessons as $order => $lessonData) {
                Lesson::updateOrCreate(
                    [
                        'program_id' => $program->id,
                        'title'      => $lessonData['title'],
                    ],
                    [
                        'title_ar'   => $lessonData['title_ar'],
                        'sort_order' => $order + 1,
                    ]
                );
                $lessonCount++;
            }
        }

        $this->command->info("  → ProgramSeeder: 5 programs + {$lessonCount} lessons created.");
    }
}
