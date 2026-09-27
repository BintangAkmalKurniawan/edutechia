<x-app-layout>
    <x-slot name="header"><div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"><div><a href="{{ route('courses.show',$materi->course) }}" class="text-sm font-bold text-brand-400">← {{ $materi->course->title }}</a><h1 class="mt-2 text-2xl font-extrabold text-white">{{ $materi->judul }}</h1></div>@if($canManage)<div class="flex gap-2"><a href="{{ route('materi.edit',$materi) }}" class="btn-secondary">Edit materi</a><a href="{{ route('quiz.edit',$materi) }}" class="btn-primary">Kelola kuis</a></div>@endif</div></x-slot>
    @php
        $embedUrl = null;
        if ($materi->video_url) {
            if (Str::contains($materi->video_url, 'youtu.be/')) {
                $embedUrl = 'https://www.youtube.com/embed/'.Str::after($materi->video_url, 'youtu.be/');
            } elseif (Str::contains($materi->video_url, 'youtube.com/watch')) {
                parse_str(parse_url($materi->video_url, PHP_URL_QUERY) ?? '', $query);
                $embedUrl = isset($query['v']) ? 'https://www.youtube.com/embed/'.$query['v'] : null;
            } elseif (Str::contains($materi->video_url, 'drive.google.com')) {
                $embedUrl = str_replace('/view', '/preview', Str::before($materi->video_url, '?'));
            }
        }
    @endphp
    <div class="shell py-8">
        <div class="grid gap-7 xl:grid-cols-[1fr_300px]">
            <article class="min-w-0">
                @if($embedUrl)<div class="aspect-video overflow-hidden rounded-2xl border border-white/10 bg-black shadow-2xl"><iframe src="{{ $embedUrl }}" class="h-full w-full" allow="autoplay; fullscreen" allowfullscreen title="Video {{ $materi->judul }}"></iframe></div>
                @elseif($materi->videos->isNotEmpty())<div class="overflow-hidden rounded-2xl border border-white/10 bg-black"><video controls class="aspect-video w-full"><source src="{{ asset('storage/'.$materi->videos->first()->video_path) }}"></video></div>
                @endif

                <div class="card mt-6 p-6 sm:p-9"><div class="flex flex-wrap items-center gap-3 border-b border-white/5 pb-6"><span class="badge">Oleh {{ $materi->course->teacher->name }}</span>@if($materi->published_at)<span class="badge">{{ $materi->published_at->translatedFormat('d M Y') }}</span>@endif</div>@if($materi->ringkasan)<p class="mt-7 border-l-2 border-brand-400 pl-5 text-lg font-medium leading-8 text-slate-300">{{ $materi->ringkasan }}</p>@endif<div class="content-body mt-8 text-base leading-8">{!! nl2br(e($materi->deskripsi)) !!}</div></div>

                @if($materi->files->isNotEmpty())<section class="card mt-6 p-6 sm:p-8"><p class="eyebrow">Sumber belajar</p><h2 class="mt-2 text-xl font-extrabold text-white">Modul & berkas pendukung</h2><div class="mt-5 grid gap-3 sm:grid-cols-2">@foreach($materi->files as $file)<a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.035] p-4 transition hover:border-brand-400/30"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-brand-400/10 text-brand-400">↓</span><span class="min-w-0"><strong class="block truncate text-sm text-white">{{ $file->original_name }}</strong><span class="text-xs text-slate-500">Buka berkas</span></span></a>@endforeach</div></section>@endif
            </article>

            <aside class="space-y-5 xl:sticky xl:top-28 xl:self-start">
                @if(auth()->user()->isStudent())<form method="POST" action="{{ route('materi.progress',$materi) }}">@csrf @method('PATCH')<button class="{{ $progress?->completed_at ? 'btn-secondary' : 'btn-primary' }} w-full" type="submit">{{ $progress?->completed_at ? '✓ Materi selesai' : 'Tandai sudah selesai' }}</button></form>@endif
                <div class="card p-5"><p class="eyebrow">Lanjutkan</p><div class="mt-4 space-y-3"><a href="{{ route('quiz.show',$materi) }}" class="flex items-center justify-between rounded-xl border border-white/10 p-4 text-sm font-bold text-white transition hover:border-brand-400/30"><span>Kuis materi</span><span class="text-brand-400">→</span></a><a href="{{ route('materi.diskusi',$materi) }}" class="flex items-center justify-between rounded-xl border border-white/10 p-4 text-sm font-bold text-white transition hover:border-brand-400/30"><span>Forum diskusi</span><span class="text-brand-400">→</span></a>@if($materi->link_kuis)<a href="{{ $materi->link_kuis }}" target="_blank" class="flex items-center justify-between rounded-xl border border-white/10 p-4 text-sm font-bold text-white transition hover:border-brand-400/30"><span>Kuis eksternal</span><span class="text-brand-400">↗</span></a>@endif @if($materi->link_diskusi)<a href="{{ $materi->link_diskusi }}" target="_blank" class="flex items-center justify-between rounded-xl border border-white/10 p-4 text-sm font-bold text-white transition hover:border-brand-400/30"><span>Forum eksternal</span><span class="text-brand-400">↗</span></a>@endif</div></div>
                @if($nextMateri)<a href="{{ route('materi.show',$nextMateri) }}" class="block rounded-2xl border border-brand-400/20 bg-brand-400/10 p-5 transition hover:bg-brand-400/15"><p class="text-xs font-bold uppercase tracking-wider text-brand-400">Materi berikutnya</p><p class="mt-2 font-bold text-white">{{ $nextMateri->judul }}</p><p class="mt-3 text-sm font-bold text-brand-300">Lanjutkan →</p></a>@endif
                @if($canManage)<form method="POST" action="{{ route('materi.destroy',$materi) }}" data-confirm="Materi beserta kuis, diskusi, dan progresnya akan dihapus permanen." data-confirm-title="Hapus materi?" data-confirm-button="Ya, hapus materi" data-confirm-danger="true">@csrf @method('DELETE')<button class="btn-danger w-full" type="submit">Hapus materi</button></form>@endif
            </aside>
        </div>
    </div>
</x-app-layout>
