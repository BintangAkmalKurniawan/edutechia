<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\DiscussionPost;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@edutechia.test'],
            [
                'name' => 'Admin Edutechia',
                'role' => User::ROLE_ADMIN,
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin123!'),
            ],
        );

        $teacher = User::updateOrCreate(
            ['email' => 'guru@edutechia.test'],
            [
                'name' => 'Ibu Ayu Pratama',
                'role' => User::ROLE_TEACHER,
                'status' => 'active',
                'institution_id' => 'GURU-001',
                'bio' => 'Pengajar sains yang senang menghubungkan konsep dengan kehidupan sehari-hari.',
                'email_verified_at' => now(),
                'password' => Hash::make('Guru123!'),
            ],
        );

        $student = User::updateOrCreate(
            ['email' => 'siswa@edutechia.test'],
            [
                'name' => 'Raka Pembelajar',
                'role' => User::ROLE_STUDENT,
                'status' => 'active',
                'institution_id' => 'SISWA-001',
                'email_verified_at' => now(),
                'password' => Hash::make('Siswa123!'),
            ],
        );

        $course = Course::updateOrCreate(
            ['slug' => 'sistem-pencernaan-manusia'],
            [
                'teacher_id' => $teacher->id,
                'title' => 'Sistem Pencernaan Manusia',
                'description' => 'Pelajari perjalanan makanan, fungsi setiap organ pencernaan, proses penyerapan nutrisi, dan kebiasaan yang menjaga sistem pencernaan tetap sehat.',
                'level' => 'Pemula',
                'status' => 'published',
                'is_open_enrollment' => true,
                'published_at' => now()->subDays(5),
            ],
        );

        $materi = Materi::updateOrCreate(
            ['course_id' => $course->id, 'slug' => 'perjalanan-makanan-di-tubuh'],
            [
                'user_id' => $teacher->id,
                'judul' => 'Perjalanan Makanan di Tubuh',
                'ringkasan' => 'Mengikuti perjalanan makanan dari mulut hingga nutrisi diserap dan sisa makanan dikeluarkan.',
                'deskripsi' => "Tubuh manusia tersusun atas miliaran sel yang membentuk jaringan, organ, hingga sistem organ. Setiap sel memerlukan nutrisi agar dapat bekerja dengan baik.\n\nMakanan yang masuk ke tubuh tidak langsung dapat digunakan. Sistem pencernaan menguraikannya secara mekanis dan kimiawi menjadi zat yang lebih sederhana. Proses dimulai di mulut, berlanjut melalui kerongkongan dan lambung, lalu penyerapan nutrisi terutama terjadi di usus halus.\n\nDengan memahami alur ini, kita dapat memilih pola makan yang lebih seimbang dan mengenali pentingnya menjaga kesehatan organ pencernaan.",
                'video_url' => 'https://www.youtube.com/watch?v=Og5xAdC8EUI',
                'link_kuis' => 'https://wordwall.net/id/embed/db78deccc5c64e8780d7302605aa1179?themeId=1&templateId=5&fontStackId=0',
                'link_diskusi' => 'https://padlet.com/embed/74j16l8zzx26bg6m',
                'position' => 1,
                'is_published' => true,
                'published_at' => now()->subDays(4),
            ],
        );

        Materi::updateOrCreate(
            ['course_id' => $course->id, 'slug' => 'menjaga-kesehatan-pencernaan'],
            [
                'user_id' => $teacher->id,
                'judul' => 'Menjaga Kesehatan Pencernaan',
                'ringkasan' => 'Kebiasaan sederhana untuk membantu sistem pencernaan bekerja optimal.',
                'deskripsi' => "Kesehatan pencernaan dipengaruhi oleh pilihan makanan, kecukupan cairan, aktivitas fisik, dan kebiasaan harian.\n\nKonsumsi makanan berserat, minum air yang cukup, makan secara teratur, serta membatasi makanan tinggi gula dan lemak membantu menjaga keseimbangan sistem pencernaan.",
                'position' => 2,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
        );

        $enrollment = Enrollment::updateOrCreate(
            ['course_id' => $course->id, 'student_id' => $student->id],
            ['status' => 'active', 'enrolled_at' => now()->subDays(2)],
        );

        LessonProgress::updateOrCreate(
            ['materi_id' => $materi->id, 'student_id' => $student->id],
            ['started_at' => now()->subDay(), 'completed_at' => now()->subHours(12)],
        );

        DiscussionPost::firstOrCreate(
            ['materi_id' => $materi->id, 'user_id' => $teacher->id, 'body' => 'Bagian mana dari perjalanan makanan yang paling menarik bagi kalian? Jelaskan alasannya.'],
            ['is_pinned' => true],
        );

        $quiz = Quiz::updateOrCreate(
            ['materi_id' => $materi->id],
            [
                'title' => 'Cek Pemahaman Sistem Pencernaan',
                'instructions' => 'Pilih satu jawaban yang paling tepat untuk setiap pertanyaan.',
                'passing_score' => 70,
                'duration_minutes' => 10,
                'is_published' => true,
            ],
        );

        $quiz->questions()->delete();
        $questions = [
            ['question' => 'Di organ manakah proses pencernaan pertama kali dimulai?', 'explanation' => 'Pencernaan mekanis oleh gigi dan kimiawi oleh enzim amilase dimulai di mulut.', 'options' => ['Mulut', 'Lambung', 'Usus halus', 'Usus besar'], 'correct' => 0],
            ['question' => 'Organ utama tempat penyerapan nutrisi adalah ...', 'explanation' => 'Permukaan usus halus memiliki vili yang memperluas area penyerapan nutrisi.', 'options' => ['Kerongkongan', 'Lambung', 'Usus halus', 'Rektum'], 'correct' => 2],
            ['question' => 'Kebiasaan yang membantu kesehatan pencernaan adalah ...', 'explanation' => 'Serat dan air membantu pergerakan makanan serta menjaga konsistensi feses.', 'options' => ['Mengurangi minum air', 'Mengonsumsi cukup serat dan air', 'Melewatkan sarapan setiap hari', 'Selalu makan terburu-buru'], 'correct' => 1],
        ];

        foreach ($questions as $questionIndex => $item) {
            $question = $quiz->questions()->create([
                'question' => $item['question'],
                'explanation' => $item['explanation'],
                'points' => 1,
                'position' => $questionIndex + 1,
            ]);
            foreach ($item['options'] as $optionIndex => $option) {
                $question->options()->create([
                    'option_text' => $option,
                    'is_correct' => $optionIndex === $item['correct'],
                    'position' => $optionIndex + 1,
                ]);
            }
        }
    }
}
