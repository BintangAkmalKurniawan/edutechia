<?php

use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;

function workflowCourse(User $teacher): Course
{
    return Course::create([
        'teacher_id' => $teacher->id,
        'title' => 'Workflow Course',
        'slug' => 'workflow-course-'.uniqid(),
        'description' => 'Deskripsi kelas untuk menguji alur utama learning management system.',
        'level' => 'Pemula',
        'status' => 'published',
        'is_open_enrollment' => true,
        'published_at' => now(),
    ]);
}

test('admin can create a teacher account but a teacher cannot', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
    $payload = [
        'name' => 'Guru Baru',
        'email' => 'guru.baru@example.com',
        'password' => 'Teacher123!',
        'password_confirmation' => 'Teacher123!',
    ];

    $this->actingAs($teacher)->post(route('admin.users.store'), $payload)->assertForbidden();
    $this->actingAs($admin)->post(route('admin.users.store'), $payload)
        ->assertRedirect(route('admin.users.index', ['role' => User::ROLE_TEACHER]));

    $createdTeacher = User::where('email', 'guru.baru@example.com')->firstOrFail();
    expect($createdTeacher->role)->toBe(User::ROLE_TEACHER)
        ->and($createdTeacher->email_verified_at)->not->toBeNull();
});

test('teacher can create a draft course and publish it after adding material', function () {
    $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);

    $this->actingAs($teacher)->post(route('courses.store'), [
        'title' => 'Kelas Dinamis',
        'description' => 'Kelas dinamis dengan deskripsi yang cukup panjang untuk memenuhi validasi.',
        'level' => 'Pemula',
        'is_open_enrollment' => '1',
    ])->assertRedirect();

    $course = Course::where('title', 'Kelas Dinamis')->firstOrFail();
    expect($course->status)->toBe('draft');

    $this->actingAs($teacher)->post(route('materi.store', $course), [
        'judul' => 'Materi Pertama',
        'ringkasan' => 'Ringkasan materi pertama.',
        'deskripsi' => 'Isi materi pertama cukup panjang untuk memenuhi semua aturan validasi.',
        'duration_minutes' => 15,
        'is_published' => '1',
    ])->assertRedirect();

    $this->actingAs($teacher)->patch(route('courses.publish', $course))->assertRedirect();
    expect($course->fresh()->status)->toBe('published');
});

test('student can discuss complete material and receive automatic quiz score', function () {
    $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = workflowCourse($teacher);
    $materi = Materi::create([
        'course_id' => $course->id,
        'user_id' => $teacher->id,
        'judul' => 'Materi Workflow',
        'slug' => 'materi-workflow',
        'deskripsi' => 'Isi materi workflow yang cukup panjang untuk proses pengujian.',
        'position' => 1,
        'is_published' => true,
        'published_at' => now(),
    ]);
    $course->enrollments()->create(['student_id' => $student->id, 'status' => 'active', 'enrolled_at' => now()]);

    $this->actingAs($student)->post(route('discussion.store', $materi), ['body' => 'Saya memahami materi ini.'])->assertRedirect();
    $this->assertDatabaseHas('discussion_posts', ['materi_id' => $materi->id, 'user_id' => $student->id]);

    $this->actingAs($student)->patch(route('materi.progress', $materi))->assertRedirect();
    expect(LessonProgress::where('materi_id', $materi->id)->where('student_id', $student->id)->first()->completed_at)->not->toBeNull();

    $quiz = Quiz::create(['materi_id' => $materi->id, 'title' => 'Kuis Workflow', 'passing_score' => 70, 'is_published' => true]);
    $question = $quiz->questions()->create(['question' => 'Jawaban yang benar?', 'points' => 2, 'position' => 1]);
    $wrong = $question->options()->create(['option_text' => 'Salah', 'is_correct' => false, 'position' => 1]);
    $correct = $question->options()->create(['option_text' => 'Benar', 'is_correct' => true, 'position' => 2]);

    $response = $this->actingAs($student)->post(route('quiz.submit', $materi), [
        'answers' => [$question->id => $correct->id],
    ]);
    $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->firstOrFail();
    $response->assertRedirect(route('quiz.result', [$materi, $attempt]));
    expect($attempt->score)->toBe(100);
});
