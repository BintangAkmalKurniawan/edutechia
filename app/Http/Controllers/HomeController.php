<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Materi;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('welcome', [
            'courses' => Course::with('teacher')
                ->withCount(['materis' => fn ($query) => $query->where('is_published', true), 'enrollments'])
                ->where('status', 'published')
                ->latest('published_at')
                ->limit(9)
                ->get(),
            'stats' => [
                'courses' => Course::where('status', 'published')->count(),
                'materials' => Materi::where('is_published', true)->count(),
                'learners' => User::where('role', User::ROLE_STUDENT)->count(),
            ],
        ]);
    }

    public function indexProfil(): View
    {
        return view('Profil.profil');
    }
}
