<?php

namespace App\Http\Controllers;

use App\Models\DiscussionPost;
use App\Models\Materi;
use App\Services\CourseAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function index(Request $request, Materi $materi, CourseAccessService $access): View
    {
        $materi->load('course');
        $access->authorizeAccess($request->user(), $materi->course);

        $posts = $materi->discussionPosts()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderByDesc('is_pinned')->latest()->paginate(15);

        return view('discussions.index', [
            'materi' => $materi,
            'posts' => $posts,
            'canManage' => $access->canManage($request->user(), $materi->course),
        ]);
    }

    public function store(Request $request, Materi $materi, CourseAccessService $access): RedirectResponse
    {
        $materi->load('course');
        $access->authorizeAccess($request->user(), $materi->course);
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:5000'],
            'parent_id' => ['nullable', 'integer', 'exists:discussion_posts,id'],
        ]);

        if (! empty($validated['parent_id'])) {
            abort_unless(DiscussionPost::whereKey($validated['parent_id'])->where('materi_id', $materi->id)->exists(), 422);
        }

        $materi->discussionPosts()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Pesan berhasil dikirim ke forum.');
    }

    public function destroy(Request $request, DiscussionPost $post, CourseAccessService $access): RedirectResponse
    {
        $post->load('materi.course');
        $allowed = $post->user_id === $request->user()->id || $access->canManage($request->user(), $post->materi->course);
        abort_unless($allowed, 403);
        $post->delete();

        return back()->with('success', 'Pesan diskusi dihapus.');
    }
}
