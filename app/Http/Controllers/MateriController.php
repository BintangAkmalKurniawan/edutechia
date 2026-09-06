<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\Materi;
use App\Models\MateriFile;
use App\Models\MateriVideo;
use App\Services\CourseAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MateriController extends Controller
{
    public function create(Request $request, Course $course, CourseAccessService $access): View
    {
        $access->authorizeManage($request->user(), $course);

        return view('materi.form', ['course' => $course, 'materi' => new Materi]);
    }

    public function store(Request $request, Course $course, CourseAccessService $access): RedirectResponse
    {
        $access->authorizeManage($request->user(), $course);
        $validated = $this->validated($request);

        $materi = DB::transaction(function () use ($request, $course, $validated) {
            $data = $validated;
            $data['course_id'] = $course->id;
            $data['user_id'] = $request->user()->id;
            $data['slug'] = $this->uniqueSlug($course, $validated['judul']);
            $data['position'] = ($course->materis()->max('position') ?? 0) + 1;
            $data['is_published'] = $request->boolean('is_published');
            $data['published_at'] = $data['is_published'] ? now() : null;

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $materi = Materi::create($data);
            $this->storeAttachments($request, $materi);

            return $materi;
        });

        return redirect()->route('materi.show', $materi)->with('success', 'Materi berhasil ditambahkan.');
    }

    public function show(Request $request, Materi $materi, CourseAccessService $access): View
    {
        $materi->load(['course.teacher', 'files', 'videos', 'quiz.questions.options']);
        $access->authorizeAccess($request->user(), $materi->course);
        $canManage = $access->canManage($request->user(), $materi->course);
        abort_unless($materi->is_published || $canManage, 404);

        $progress = null;
        if ($request->user()->isStudent()) {
            $progress = LessonProgress::firstOrCreate(
                ['materi_id' => $materi->id, 'student_id' => $request->user()->id],
                ['started_at' => now()],
            );
        }

        $nextMateri = $materi->course->materis()
            ->where('is_published', true)
            ->where('position', '>', $materi->position)
            ->first();

        return view('materi.show', compact('materi', 'progress', 'canManage', 'nextMateri'));
    }

    public function edit(Request $request, Materi $materi, CourseAccessService $access): View
    {
        $materi->load(['course', 'files', 'videos']);
        $access->authorizeManage($request->user(), $materi->course);

        return view('materi.form', ['course' => $materi->course, 'materi' => $materi]);
    }

    public function update(Request $request, Materi $materi, CourseAccessService $access): RedirectResponse
    {
        $materi->load('course');
        $access->authorizeManage($request->user(), $materi->course);
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($materi->course, $validated['judul'], $materi->id);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? ($materi->published_at ?? now()) : null;

        if ($request->hasFile('thumbnail')) {
            if ($materi->thumbnail) {
                Storage::disk('public')->delete($materi->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        DB::transaction(function () use ($request, $materi, $validated): void {
            $materi->update($validated);
            $this->storeAttachments($request, $materi);
        });

        return redirect()->route('materi.show', $materi)->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Request $request, Materi $materi, CourseAccessService $access): RedirectResponse
    {
        $materi->load(['course', 'files', 'videos']);
        $access->authorizeManage($request->user(), $materi->course);
        $course = $materi->course;

        collect([$materi->thumbnail])->filter()->each(fn ($path) => Storage::disk('public')->delete($path));
        $materi->files->each(fn ($file) => Storage::disk('public')->delete($file->file_path));
        $materi->videos->each(fn ($video) => Storage::disk('public')->delete($video->video_path));
        $materi->delete();

        return redirect()->route('courses.show', $course)->with('success', 'Materi berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string', 'max:500'],
            'deskripsi' => ['required', 'string', 'min:20'],
            'video_url' => ['nullable', 'url', 'max:2048'],
            'link_diskusi' => ['nullable', 'url', 'max:2048'],
            'link_kuis' => ['nullable', 'url', 'max:2048'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'is_published' => ['nullable', 'boolean'],
            'thumbnail' => ['nullable', 'image', 'max:3072'],
            'files.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip', 'max:15360'],
            'videos.*' => ['nullable', 'file', 'mimes:mp4,mov,ogg,webm', 'max:512000'],
        ]);
    }

    private function storeAttachments(Request $request, Materi $materi): void
    {
        foreach ($request->file('files', []) as $file) {
            MateriFile::create([
                'materi_id' => $materi->id,
                'file_path' => $file->store('materi_files', 'public'),
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        foreach ($request->file('videos', []) as $video) {
            MateriVideo::create([
                'materi_id' => $materi->id,
                'video_path' => $video->store('materi_videos', 'public'),
            ]);
        }
    }

    private function uniqueSlug(Course $course, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while ($course->materis()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
