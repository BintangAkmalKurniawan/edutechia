<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"><div><p class="eyebrow">Ruang belajar</p><h1 class="mt-2 text-2xl font-extrabold text-white">Halo, {{ Str::before(auth()->user()->name, ' ') }}. Siap melanjutkan?</h1><p class="mt-1 text-sm text-slate-500">Satu materi selesai tetap sebuah kemajuan.</p></div><a href="{{ route('courses.index') }}" class="btn-primary">Temukan kelas</a></div>
    </x-slot>

    <div class="shell py-8">
        <div class="grid gap-4 sm:grid-cols-3"><x-stat-card label="Kelas diikuti" :value="$stats['courses']" /><x-stat-card label="Materi selesai" :value="$stats['completedLessons']" /><x-stat-card label="Percobaan kuis" :value="$stats['quizAttempts']" /></div>

        <section class="mt-10"><p class="eyebrow">Kelas saya</p><h2 class="mt-2 text-2xl font-extrabold text-white">Lanjutkan pembelajaran</h2>
            @if ($courses->isEmpty())
                <div class="card mt-6 p-10 text-center"><h3 class="text-lg font-bold text-white">Belum ada kelas yang diikuti</h3><p class="mt-2 text-sm text-slate-500">Jelajahi katalog dan pilih kelas pertama Anda.</p><a href="{{ route('courses.index') }}" class="btn-primary mt-6">Jelajahi katalog</a></div>
            @else
                <div class="mt-6 grid gap-5 lg:grid-cols-2">
                    @foreach ($courses as $course)
                        <a href="{{ route('courses.show', $course) }}" class="card group flex flex-col gap-5 p-5 transition hover:border-brand-400/30 sm:flex-row"><div class="h-36 w-full shrink-0 overflow-hidden rounded-xl bg-slate-800 sm:w-48">@if($course->thumbnail)<img src="{{ asset('storage/'.$course->thumbnail) }}" class="h-full w-full object-cover transition group-hover:scale-105" alt="">@else<img src="{{ asset('images/gambar_template.jpg') }}" class="h-full w-full object-cover opacity-70" alt="">@endif</div><div class="min-w-0 flex-1"><p class="text-xs font-bold uppercase tracking-wider text-brand-400">{{ $course->teacher->name }}</p><h3 class="mt-2 truncate text-xl font-extrabold text-white">{{ $course->title }}</h3><p class="mt-3 text-xs text-slate-500">{{ $course->materis->count() }} materi · {{ $course->level }}</p><div class="mt-5"><div class="mb-2 flex justify-between text-xs font-bold"><span class="text-slate-400">Progres</span><span class="text-brand-400">{{ $course->progress_percentage }}%</span></div><div class="h-2 overflow-hidden rounded-full bg-white/5"><div class="h-full rounded-full bg-brand-400" style="width: {{ $course->progress_percentage }}%"></div></div></div></div></a>
                    @endforeach
                </div>
            @endif
        </section>

        @if ($recommended->isNotEmpty())
            <section class="mt-14"><div class="flex items-end justify-between"><div><p class="eyebrow">Rekomendasi</p><h2 class="mt-2 text-2xl font-extrabold text-white">Kelas lain untuk Anda</h2></div><a class="text-sm font-bold text-brand-400" href="{{ route('courses.index') }}">Lihat katalog →</a></div><div class="mt-6 grid gap-6 md:grid-cols-3">@foreach($recommended as $course)<x-course-card :course="$course" />@endforeach</div></section>
        @endif
    </div>
</x-app-layout>
