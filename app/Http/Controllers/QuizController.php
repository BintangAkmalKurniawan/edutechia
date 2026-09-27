<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Services\CourseAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function show(Request $request, Materi $materi, CourseAccessService $access): View|RedirectResponse
    {
        $materi->load(['course', 'quiz.questions.options']);
        $access->authorizeAccess($request->user(), $materi->course);
        $canManage = $access->canManage($request->user(), $materi->course);
        $quiz = $materi->quiz;

        if ($quiz && ! $quiz->is_published && ! $canManage) {
            return redirect()->route('materi.show', $materi)
                ->withErrors(['quiz' => 'Kuis ini belum diterbitkan oleh guru.']);
        }

        $activeAttempt = null;
        $attempts = collect();

        if ($quiz && $request->user()->isStudent()) {
            $attempts = $quiz->attempts()
                ->where('student_id', $request->user()->id)
                ->whereNotNull('submitted_at')
                ->latest('submitted_at')
                ->get();

            if ($quiz->duration_minutes && $quiz->questions->isNotEmpty()) {
                $activeAttempt = $quiz->attempts()
                    ->where('student_id', $request->user()->id)
                    ->whereNull('submitted_at')
                    ->latest('started_at')
                    ->first();

                if (! $activeAttempt) {
                    $activeAttempt = $quiz->attempts()->create([
                        'student_id' => $request->user()->id,
                        'started_at' => now(),
                        'total_points' => $quiz->questions->sum('points'),
                    ]);
                }
            }
        }

        return view('quizzes.show', compact('materi', 'attempts', 'activeAttempt', 'canManage'));
    }

    public function submit(Request $request, Materi $materi, CourseAccessService $access): RedirectResponse
    {
        abort_unless($request->user()->isStudent(), 403);
        $materi->load(['course', 'quiz.questions.options']);
        $access->authorizeAccess($request->user(), $materi->course);
        $quiz = $materi->quiz;

        if (! $quiz || ! $quiz->is_published) {
            return redirect()->route('materi.show', $materi)
                ->withErrors(['quiz' => 'Kuis belum tersedia atau belum diterbitkan oleh guru.']);
        }

        $validated = $request->validate([
            'answers' => ['sometimes', 'array'],
            'answers.*' => ['required', 'integer'],
            'attempt_id' => ['nullable', 'integer'],
            'auto_submitted' => ['nullable', 'boolean'],
        ]);

        $answers = $validated['answers'] ?? [];
        $autoSubmitted = $request->boolean('auto_submitted');

        if (! $autoSubmitted) {
            foreach ($quiz->questions as $question) {
                if (! array_key_exists($question->id, $answers)) {
                    throw ValidationException::withMessages([
                        'answers' => 'Semua pertanyaan harus dijawab sebelum jawaban dikirim.',
                    ]);
                }
            }
        }

        $attempt = DB::transaction(function () use ($answers, $quiz, $request, $validated) {
            $totalPoints = $quiz->questions->sum('points');
            $earnedPoints = 0;
            $attempt = null;

            if (! empty($validated['attempt_id'])) {
                $attempt = QuizAttempt::query()
                    ->whereKey($validated['attempt_id'])
                    ->where('quiz_id', $quiz->id)
                    ->where('student_id', $request->user()->id)
                    ->lockForUpdate()
                    ->first();

                if (! $attempt) {
                    throw ValidationException::withMessages([
                        'attempt_id' => 'Percobaan kuis tidak valid. Silakan muat ulang halaman kuis.',
                    ]);
                }

                if ($attempt->submitted_at) {
                    return $attempt;
                }
            }

            $attempt ??= QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => $request->user()->id,
                'started_at' => now(),
                'total_points' => $totalPoints,
            ]);

            foreach ($quiz->questions as $question) {
                $optionId = isset($answers[$question->id]) ? (int) $answers[$question->id] : null;
                $option = $question->options->firstWhere('id', $optionId);
                $isCorrect = (bool) ($option?->is_correct);
                $points = $isCorrect ? $question->points : 0;
                $earnedPoints += $points;

                QuizAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'option_id' => $option?->id,
                    'is_correct' => $isCorrect,
                    'points_earned' => $points,
                ]);
            }

            $attempt->update([
                'earned_points' => $earnedPoints,
                'total_points' => $totalPoints,
                'score' => $totalPoints > 0 ? (int) round(($earnedPoints / $totalPoints) * 100) : 0,
                'submitted_at' => now(),
            ]);

            return $attempt;
        });

        return redirect()->route('quiz.result', [
            'materi' => $materi,
            'attempt' => $attempt,
        ]);
    }

    public function result(Request $request, Materi $materi, QuizAttempt $attempt, CourseAccessService $access): View
    {
        $materi->load(['course', 'quiz.questions.options']);
        $access->authorizeAccess($request->user(), $materi->course);
        abort_unless($attempt->quiz_id === $materi->quiz?->getKey(), 404);
        abort_unless($access->canManage($request->user(), $materi->course) || $attempt->student_id === $request->user()->id, 403);
        $attempt->load('answers');

        return view('quizzes.result', compact('materi', 'attempt'));
    }

    public function edit(Request $request, Materi $materi, CourseAccessService $access): View
    {
        $materi->load('course');
        $access->authorizeManage($request->user(), $materi->course);
        $quiz = Quiz::firstOrCreate(
            ['materi_id' => $materi->id],
            ['title' => 'Kuis '.$materi->judul, 'passing_score' => 70],
        );
        $quiz->load('questions.options');

        return view('quizzes.edit', compact('materi', 'quiz'));
    }

    public function update(Request $request, Materi $materi, CourseAccessService $access): RedirectResponse
    {
        $materi->load('course');
        $access->authorizeManage($request->user(), $materi->course);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string', 'max:2000'],
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:300'],
            'is_published' => ['nullable', 'boolean'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string', 'max:2000'],
            'questions.*.explanation' => ['nullable', 'string', 'max:2000'],
            'questions.*.points' => ['required', 'integer', 'min:1', 'max:100'],
            'questions.*.correct_index' => ['required', 'integer', 'min:0'],
            'questions.*.options' => ['required', 'array', 'min:2', 'max:6'],
            'questions.*.options.*' => ['required', 'string', 'max:1000'],
        ]);

        foreach ($validated['questions'] as $question) {
            if ($question['correct_index'] >= count($question['options'])) {
                throw ValidationException::withMessages(['questions' => 'Pilihan jawaban benar tidak valid.']);
            }
        }

        DB::transaction(function () use ($validated, $materi, $request): void {
            $quiz = Quiz::updateOrCreate(
                ['materi_id' => $materi->id],
                [
                    'title' => $validated['title'],
                    'instructions' => $validated['instructions'] ?? null,
                    'passing_score' => $validated['passing_score'],
                    'duration_minutes' => $validated['duration_minutes'] ?? null,
                    'is_published' => $request->boolean('is_published'),
                ],
            );
            $quiz->questions()->delete();

            foreach ($validated['questions'] as $questionIndex => $questionData) {
                $question = $quiz->questions()->create([
                    'question' => $questionData['question'],
                    'explanation' => $questionData['explanation'] ?? null,
                    'points' => $questionData['points'],
                    'position' => $questionIndex + 1,
                ]);
                foreach ($questionData['options'] as $optionIndex => $optionText) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $optionIndex === (int) $questionData['correct_index'],
                        'position' => $optionIndex + 1,
                    ]);
                }
            }
        });

        return redirect()->route('quiz.show', $materi)->with('success', 'Kuis berhasil diperbarui.');
    }
}
