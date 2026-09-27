<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">Ruang pengajar</p>
                <h1 class="mt-2 text-2xl font-extrabold text-white">Selamat berkarya,
                    {{ Str::before(auth()->user()->name, ' ') }}.</h1>
                <p class="mt-1 text-sm text-slate-500">Bangun pengalaman belajar dan lihat jangkauannya.</p>
            </div><a href="{{ route('courses.create') }}" class="btn-primary">+ Buat kelas baru</a>
        </div>
    </x-slot>

    <div class="shell py-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Kelas dikelola" :value="$stats['courses']" />
            <x-stat-card label="Sudah diterbitkan" :value="$stats['published']" />
            <x-stat-card label="Siswa unik" :value="$stats['students']" />
            <x-stat-card label="Total materi" :value="$stats['materials']" />
        </div>

        <section class="mt-9">
            <div class="flex items-end justify-between">
                <div>
                    <p class="eyebrow">Kelas Anda</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-white">Kelola pembelajaran</h2>
                </div>
            </div>
            @if ($courses->isEmpty())
                <div class="card mt-6 p-12 text-center">
                    <div
                        class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-brand-400/10 text-2xl text-brand-400">
                        +</div>
                    <h3 class="mt-5 text-lg font-bold text-white">Kelas pertama menunggu dibuat</h3>
                    <p class="mt-2 text-sm text-slate-500">Tambahkan kelas, susun materi, lalu terbitkan saat siap.</p>
                    <a href="{{ route('courses.create') }}" class="btn-primary mt-6">Buat kelas</a>
                </div>
            @else
                <div class="mt-6 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($courses as $course)
                        <article class="card overflow-hidden">
                            <div class="h-2 {{ $course->status === 'published' ? 'bg-emerald-400' : 'bg-amber-400' }}">
                            </div>
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4"><span
                                        class="badge">{{ $course->level }}</span><span
                                        class="text-xs font-bold capitalize {{ $course->status === 'published' ? 'text-emerald-300' : 'text-amber-300' }}">{{ $course->status }}</span>
                                </div>
                                <h3 class="mt-4 text-xl font-extrabold text-white">{{ $course->title }}</h3>
                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $course->description }}
                                </p>
                                <div class="mt-5 grid grid-cols-2 gap-3 rounded-xl bg-white/[0.035] p-4 text-center">
                                    <div>
                                        <p class="font-bold text-white">{{ $course->materis_count }}</p>
                                        <p class="text-xs text-slate-500">Materi</p>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white">{{ $course->enrollments_count }}</p>
                                        <p class="text-xs text-slate-500">Siswa</p>
                                    </div>
                                </div>
                                <div class="mt-5 flex gap-2"><a href="{{ route('courses.show', $course) }}"
                                        class="btn-primary flex-1">Kelola</a><a
                                        href="{{ route('courses.edit', $course) }}" class="btn-secondary px-4">Edit</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
