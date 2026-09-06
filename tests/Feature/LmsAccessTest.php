<?php

use App\Models\Course;
use App\Models\Materi;
use App\Models\User;

function lmsCourse(User $teacher, array $attributes = []): Course
{
    return Course::create(array_merge([
        'teacher_id' => $teacher->id,
        'title' => 'Kelas Uji',
        'slug' => 'kelas-uji-'.uniqid(),
        'description' => 'Deskripsi kelas uji yang cukup panjang untuk kebutuhan pengujian.',
        'level' => 'Pemula',
        'status' => 'published',
        'is_open_enrollment' => true,
        'published_at' => now(),
    ], $attributes));
}

test('students cannot access course materials before enrollment', function () {
    $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = lmsCourse($teacher);
    $materi = Materi::create([
        'course_id' => $course->id,
        'user_id' => $teacher->id,
        'judul' => 'Materi Uji',
        'slug' => 'materi-uji',
        'deskripsi' => 'Isi materi uji yang cukup panjang agar valid.',
        'position' => 1,
        'is_published' => true,
        'published_at' => now(),
    ]);

    $this->actingAs($student)->get(route('materi.show', $materi))->assertForbidden();
});

test('students can enroll and access published materials', function () {
    $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = lmsCourse($teacher);
    $materi = Materi::create([
        'course_id' => $course->id,
        'user_id' => $teacher->id,
        'judul' => 'Materi Uji',
        'slug' => 'materi-uji',
        'deskripsi' => 'Isi materi uji yang cukup panjang agar valid.',
        'position' => 1,
        'is_published' => true,
        'published_at' => now(),
    ]);

    $this->actingAs($student)->post(route('courses.enroll', $course))->assertRedirect(route('courses.show', $course));
    $this->actingAs($student)->get(route('materi.show', $materi))->assertOk();
});

test('teachers cannot manage courses owned by another teacher', function () {
    $owner = User::factory()->create(['role' => User::ROLE_TEACHER]);
    $other = User::factory()->create(['role' => User::ROLE_TEACHER]);
    $course = lmsCourse($owner);

    $this->actingAs($other)->get(route('courses.edit', $course))->assertForbidden();
});

test('only admins can open user administration', function () {
    $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($teacher)->get(route('admin.users.index'))->assertForbidden();
    $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
});
