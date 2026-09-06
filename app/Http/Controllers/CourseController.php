<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\User;
use App\Services\CourseAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function catalogue(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $courses = Course::with('teacher')
            ->withCount(['materis' => fn ($query) => $query->where('is_published', true), 'enrollments'])
            ->where('status', 'published')
            ->when($search, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            }))
            ->latest('published_at')->paginate(9)->withQueryString();

        return view('courses.index', compact('courses', 'search'));
    }

    public function show(Request $request, Course $course, CourseAccessService $access): View
    {
        $user = $request->user();
        $canManage = $user ? $access->canManage($user, $course) : false;
        abort_unless($course->isPublished() || $canManage, 404);

        $course->load([
            'teacher',
            'materis' => fn ($query) => $canManage ? $query : $query->where('is_published', true),
        ])->loadCount('enrollments');

        $isEnrolled = $user?->isStudent()
            ? $course->enrollments()->where('student_id', $user->id)->where('status', 'active')->exists()
            : false;
        $completedIds = $isEnrolled
            ? LessonProgress::where('student_id', $user->id)->whereNotNull('completed_at')->pluck('materi_id')
            : collect();

        return view('courses.show', compact('course', 'canManage', 'isEnrolled', 'completedIds'));
    }

    public function create(Request $request): View
    {
        return view('courses.form', [
            'course' => new Course(['is_open_enrollment' => true, 'level' => 'Umum']),
            'teachers' => $request->user()->isAdmin()
                ? User::where('role', User::ROLE_TEACHER)->where('status', 'active')->orderBy('name')->get()
                : collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $user = $request->user();
        $validated['teacher_id'] = $user->isAdmin() ? $validated['teacher_id'] : $user->id;
        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['status'] = 'draft';
        $validated['access_code'] = $validated['is_open_enrollment'] ? null : strtoupper(Str::random(8));

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course = Course::create($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Kelas berhasil dibuat. Tambahkan materi sebelum menerbitkannya.');
    }

    public function edit(Request $request, Course $course, CourseAccessService $access): View
    {
        $access->authorizeManage($request->user(), $course);

        return view('courses.form', [
            'course' => $course,
            'teachers' => $request->user()->isAdmin()
                ? User::where('role', User::ROLE_TEACHER)->where('status', 'active')->orderBy('name')->get()
                : collect(),
        ]);
    }

    public function update(Request $request, Course $course, CourseAccessService $access): RedirectResponse
    {
        $access->authorizeManage($request->user(), $course);
        $validated = $this->validated($request);
        $validated['teacher_id'] = $request->user()->isAdmin() ? $validated['teacher_id'] : $course->teacher_id;
        $validated['slug'] = $this->uniqueSlug($validated['title'], $course->id);

        if (! $validated['is_open_enrollment'] && ! $course->access_code) {
            $validated['access_code'] = strtoupper(Str::random(8));
        } elseif ($validated['is_open_enrollment']) {
            $validated['access_code'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }
        $course->update($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Informasi kelas berhasil diperbarui.');
    }

    public function publish(Request $request, Course $course, CourseAccessService $access): RedirectResponse
    {
        $access->authorizeManage($request->user(), $course);
        if ($course->status !== 'published' && ! $course->materis()->where('is_published', true)->exists()) {
            return back()->withErrors(['publish' => 'Terbitkan minimal satu materi sebelum menerbitkan kelas.']);
        }

        $published = $course->status !== 'published';
        $course->update([
            'status' => $published ? 'published' : 'draft',
            'published_at' => $published ? ($course->published_at ?? now()) : null,
        ]);

        return back()->with('success', $published ? 'Kelas telah diterbitkan.' : 'Kelas dikembalikan menjadi draf.');
    }

    public function enroll(Request $request, Course $course): RedirectResponse
    {
        abort_unless($request->user()->isStudent(), 403);
        abort_unless($course->isPublished(), 404);

        if (! $course->is_open_enrollment) {
            $request->validate(['access_code' => ['required', 'string']]);
            if (! hash_equals((string) $course->access_code, strtoupper((string) $request->input('access_code')))) {
                return back()->withErrors(['access_code' => 'Kode akses kelas tidak sesuai.']);
            }
        }

        $course->enrollments()->updateOrCreate(
            ['student_id' => $request->user()->id],
            ['status' => 'active', 'enrolled_at' => now(), 'completed_at' => null],
        );

        return redirect()->route('courses.show', $course)->with('success', 'Anda berhasil bergabung ke kelas.');
    }

    public function destroy(Request $request, Course $course, CourseAccessService $access): RedirectResponse
    {
        $access->authorizeManage($request->user(), $course);
        $course->load('materis.files', 'materis.videos');
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        foreach ($course->materis as $materi) {
            if ($materi->thumbnail) {
                Storage::disk('public')->delete($materi->thumbnail);
            }
            $materi->files->each(fn ($file) => Storage::disk('public')->delete($file->file_path));
            $materi->videos->each(fn ($video) => Storage::disk('public')->delete($video->video_path));
        }
        $course->delete();

        return redirect()->route('dashboard')->with('success', 'Kelas beserta seluruh isinya telah dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'level' => ['required', 'string', 'max:50'],
            'teacher_id' => [Rule::requiredIf($request->user()->isAdmin()), 'nullable', Rule::exists('users', 'id')->where('role', User::ROLE_TEACHER)],
            'is_open_enrollment' => ['required', 'boolean'],
            'thumbnail' => ['nullable', 'image', 'max:3072'],
        ]);
        $validated['is_open_enrollment'] = $request->boolean('is_open_enrollment');

        return $validated;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $counter = 2;
        while (Course::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
