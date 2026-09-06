@extends('layouts.public')
@section('title', $course->title.' — Edutechia')
@section('content')
<main>
    <section class="relative overflow-hidden border-b border-white/5 bg-white/[0.02]">
        <div class="absolute inset-0 opacity-[0.08]">@if($course->thumbnail)<img class="h-full w-full object-cover blur-xl" src="{{ asset('storage/'.$course->thumbnail) }}" alt="">@endif</div>
        <div class="shell relative grid gap-10 py-14 lg:grid-cols-[1.2fr_.8fr] lg:items-center lg:py-20">
            <div><div class="flex flex-wrap gap-2"><span class="badge border-brand-400/20 text-brand-300">{{ $course->level }}</span><span class="badge capitalize {{ $course->status === 'published' ? 'text-emerald-300' : 'text-amber-300' }}">{{ $course->status }}</span></div><h1 class="mt-5 font-display text-4xl font-extrabold leading-tight text-white sm:text-5xl">{{ $course->title }}</h1><p class="mt-5 max-w-3xl text-lg leading-8 text-slate-400">{{ $course->description }}</p><div class="mt-7 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-500"><span>Pengajar <strong class="text-slate-300">{{ $course->teacher->name }}</strong></span><span>{{ $course->materis->count() }} materi</span><span>{{ $course->enrollments_count }} siswa</span></div>
                @if($canManage)<div class="mt-8 flex flex-wrap gap-3"><a href="{{ route('courses.edit', $course) }}" class="btn-secondary">Edit kelas</a><a href="{{ route('materi.create', $course) }}" class="btn-primary">+ Tambah materi</a><form method="POST" action="{{ route('courses.publish', $course) }}">@csrf @method('PATCH')<button class="btn-secondary" type="submit">{{ $course->status === 'published' ? 'Jadikan draf' : 'Terbitkan kelas' }}</button></form></div>@endif
            </div>
            <div class="overflow-hidden rounded-2xl border border-white/10 bg-panel p-3 shadow-2xl">@if($course->thumbnail)<img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="aspect-video w-full rounded-xl object-cover">@else<img src="{{ asset('images/gambar_template.jpg') }}" alt="{{ $course->title }}" class="aspect-video w-full rounded-xl object-cover opacity-70">@endif</div>
        </div>
    </section>

    <section class="shell py-14">
        @if (session('success'))<div class="mb-6 rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm font-semibold text-emerald-300">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="mb-6 rounded-xl border border-red-400/20 bg-red-400/10 px-4 py-3 text-sm text-red-200">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif

        <div class="grid gap-8 lg:grid-cols-[1fr_320px]">
            <div><div class="flex items-end justify-between"><div><p class="eyebrow">Kurikulum kelas</p><h2 class="mt-2 text-2xl font-extrabold text-white">Materi pembelajaran</h2></div>@if($isEnrolled)<span class="badge text-emerald-300">Sudah bergabung</span>@endif</div>
                <div class="mt-6 space-y-3">
                    @forelse ($course->materis as $materi)
                        @php($canOpen = $canManage || $isEnrolled)
                        <{{ $canOpen ? 'a' : 'div' }} @if($canOpen) href="{{ route('materi.show', $materi) }}" @endif class="group flex items-center gap-4 rounded-2xl border border-white/10 bg-panel p-5 transition {{ $canOpen ? 'hover:border-brand-400/30' : 'opacity-65' }}">
                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $completedIds->contains($materi->id) ? 'bg-emerald-400/10 text-emerald-300' : 'bg-white/[0.05] text-slate-400' }} font-bold">{{ $completedIds->contains($materi->id) ? '✓' : str_pad($materi->position, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="min-w-0 flex-1"><div class="flex items-center gap-2"><h3 class="truncate font-bold text-white">{{ $materi->judul }}</h3>@unless($materi->is_published)<span class="badge text-amber-300">Draf</span>@endunless</div><p class="mt-1 line-clamp-1 text-sm text-slate-500">{{ $materi->ringkasan ?: Str::limit($materi->deskripsi, 100) }}</p></div>
                            <div class="hidden text-right text-xs text-slate-500 sm:block"><p>{{ $materi->duration_minutes }} menit</p>@if($materi->quiz)<p class="mt-1 text-brand-400">Ada kuis</p>@endif</div>
                            <span class="text-brand-400">{{ $canOpen ? '→' : '🔒' }}</span>
                        </{{ $canOpen ? 'a' : 'div' }}>
                    @empty<div class="card p-10 text-center text-sm text-slate-500">Belum ada materi pada kelas ini.</div>@endforelse
                </div>
            </div>

            <aside class="space-y-5">
                @guest
                    <div class="card p-6"><h3 class="text-lg font-bold text-white">Masuk untuk belajar</h3><p class="mt-2 text-sm leading-6 text-slate-500">Akses materi, kuis, diskusi, dan catatan progres setelah masuk.</p><a href="{{ route('login') }}" class="btn-primary mt-5 w-full">Masuk</a><a href="{{ route('register') }}" class="btn-secondary mt-3 w-full">Daftar siswa</a></div>
                @elseif(auth()->user()->isStudent() && !$isEnrolled)
                    <div class="card p-6"><h3 class="text-lg font-bold text-white">Gabung kelas ini</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $course->is_open_enrollment ? 'Kelas terbuka dan dapat diikuti langsung.' : 'Masukkan kode akses yang diberikan guru.' }}</p><form method="POST" action="{{ route('courses.enroll', $course) }}" class="mt-5">@csrf @unless($course->is_open_enrollment)<label class="label" for="access_code">Kode akses</label><input class="field uppercase" id="access_code" name="access_code" required placeholder="CONTOH12">@endunless<button class="btn-primary mt-4 w-full" type="submit">Gabung kelas</button></form></div>
                @endguest
                <div class="card p-6"><p class="eyebrow">Informasi kelas</p><dl class="mt-5 space-y-4 text-sm"><div class="flex justify-between gap-4"><dt class="text-slate-500">Tingkat</dt><dd class="font-bold text-white">{{ $course->level }}</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Materi</dt><dd class="font-bold text-white">{{ $course->materis->count() }}</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Akses</dt><dd class="font-bold text-white">{{ $course->is_open_enrollment ? 'Terbuka' : 'Dengan kode' }}</dd></div>@if($canManage && $course->access_code)<div class="border-t border-white/5 pt-4"><dt class="text-slate-500">Kode kelas</dt><dd class="mt-2 rounded-lg bg-brand-400/10 p-3 text-center font-mono text-lg font-bold tracking-wider text-brand-300">{{ $course->access_code }}</dd></div>@endif</dl></div>
            </aside>
        </div>
    </section>
</main>
@endsection
