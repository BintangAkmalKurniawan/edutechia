<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;

class CourseAccessService
{
    public function canManage(User $user, Course $course): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $course->teacher_id === $user->id);
    }

    public function canAccess(User $user, Course $course): bool
    {
        if ($this->canManage($user, $course)) {
            return true;
        }

        return $user->isStudent() && $course->enrollments()
            ->where('student_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public function authorizeManage(User $user, Course $course): void
    {
        abort_unless($this->canManage($user, $course), 403);
    }

    public function authorizeAccess(User $user, Course $course): void
    {
        abort_unless($this->canAccess($user, $course), 403, 'Anda belum terdaftar pada kelas ini.');
    }
}
