<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\Materi;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin', [
                'stats' => [
                    'teachers' => User::where('role', User::ROLE_TEACHER)->count(),
                    'students' => User::where('role', User::ROLE_STUDENT)->count(),
                    'courses' => Course::count(),
                    'published' => Course::where('status', 'published')->count(),
                ],
                'latestUsers' => User::latest()->limit(6)->get(),
                'latestCourses' => Course::with('teacher')->latest()->limit(5)->get(),
            ]);
        }

        if ($user->isTeacher()) {
            $courses = Course::withCount(['materis', 'enrollments'])
                ->where('teacher_id', $user->id)
                ->latest()
                ->get();

            return view('dashboard.teacher', [
                'courses' => $courses,
                'stats' => [
                    'courses' => $courses->count(),
                    'published' => $courses->where('status', 'published')->count(),
                    'students' => Enrollment::whereIn('course_id', $courses->pluck('id'))->distinct('student_id')->count('student_id'),
                    'materials' => Materi::whereIn('course_id', $courses->pluck('id'))->count(),
                ],
            ]);
        }

        $courses = $user->enrolledCourses()
            ->wherePivot('status', 'active')
            ->with(['teacher', 'materis' => fn ($query) => $query->where('is_published', true)])
            ->latest('enrollments.enrolled_at')
            ->get();
        $completedIds = LessonProgress::where('student_id', $user->id)
            ->whereNotNull('completed_at')
            ->pluck('materi_id');

        $courses->each(function (Course $course) use ($completedIds): void {
            $total = $course->materis->count();
            $completed = $course->materis->whereIn('id', $completedIds)->count();
            $course->setAttribute('progress_percentage', $total > 0 ? (int) round(($completed / $total) * 100) : 0);
        });

        return view('dashboard.student', [
            'courses' => $courses,
            'stats' => [
                'courses' => $courses->count(),
                'completedLessons' => $completedIds->count(),
                'quizAttempts' => QuizAttempt::where('student_id', $user->id)->count(),
            ],
            'recommended' => Course::with('teacher')
                ->withCount('enrollments')
                ->where('status', 'published')
                ->whereNotIn('id', $courses->pluck('id'))
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
