<?php

namespace App\Http\Controllers;

use App\Models\LessonProgress;
use App\Models\Materi;
use App\Services\CourseAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(Request $request, Materi $materi, CourseAccessService $access): RedirectResponse
    {
        abort_unless($request->user()->isStudent(), 403);
        $materi->load('course');
        $access->authorizeAccess($request->user(), $materi->course);

        $progress = LessonProgress::firstOrCreate(
            ['materi_id' => $materi->id, 'student_id' => $request->user()->id],
            ['started_at' => now()],
        );
        $progress->update(['completed_at' => $progress->completed_at ? null : now()]);

        return back()->with('success', $progress->completed_at ? 'Materi ditandai selesai.' : 'Status selesai dibatalkan.');
    }
}
