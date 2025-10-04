<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\MateriFile;
use App\Models\MateriVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth;    

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::where('user_id', Auth::id())->latest()->get();
        return view('dashboard.dashboard', compact('materis')); 
    }

    public function create()
    {
        return view('materi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'link_diskusi' => 'string|min:2',
            'link_kuis' => 'string|min:2',
            'deskripsi' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'files.*' => 'nullable|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'videos.*' => 'nullable|mimes:mp4,mov,ogg|max:512000',
        ]);

        $materi = new Materi();
        $materi->user_id = Auth::id();
        $materi->judul = $request->judul;
        $materi->link_kuis = $request->link_kuis;
        $materi->link_diskusi = $request->link_diskusi;
        $materi->deskripsi = $request->deskripsi;

        if ($request->hasFile('thumbnail')) {
            $materi->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        $materi->save();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                MateriFile::create([
                    'materi_id' => $materi->id,
                    'file_path' => $file->store('materi_files', 'public'),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                MateriVideo::create([
                    'materi_id' => $materi->id,
                    'video_path' => $video->store('materi_videos', 'public'),
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Materi berhasil ditambahkan!');
    }

    public function show(Materi $materi)
    {
        $materi->load('user', 'videos', 'files');
        return view('tampilanlihatdetail.index', compact('materi'));
    }

    public function edit(Materi $materi)
    {
        if ($materi->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }
        return view('materi.edit', compact('materi'));
    }

    public function update(Request $request, Materi $materi)
    {
        if ($materi->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'link_diskusi' => 'string|min:2',
            'link_kuis' => 'string|min:2',
            'deskripsi' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $materi->update($request->only('judul', 'deskripsi','link_kuis', 'link_diskusi'));

        if ($request->hasFile('thumbnail')) {
            if ($materi->thumbnail) {
                Storage::disk('public')->delete($materi->thumbnail);
            }
            $materi->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
            $materi->save();
        }

        return redirect()->route('dashboard')->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(Materi $materi)
    {
        if ($materi->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        if ($materi->thumbnail) {
            Storage::disk('public')->delete($materi->thumbnail);
        }
        $materi->videos->each(fn($video) => Storage::disk('public')->delete($video->video_path));
        $materi->files->each(fn($file) => Storage::disk('public')->delete($file->file_path));

        $materi->delete();

        return redirect()->route('dashboard')->with('success', 'Materi berhasil dihapus!');
    }

    public function indexWelcom()
    {
        $materis = Materi::with('user')->latest()->get();
        return view('welcome', compact('materis'));
    }

    public function showDiskusi(Materi $materi)
    {
        return view('tampilanlihatdetail.diskusi', compact('materi'));
    }


    public function showKuis(Materi $materi)
    {
        return view('tampilanlihatdetail.kuis', compact('materi'));
    }
}

