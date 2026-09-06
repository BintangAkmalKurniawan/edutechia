@props(['course'])
<a href="{{ route('courses.show', $course) }}" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-white/10 bg-panel transition duration-300 hover:-translate-y-1 hover:border-brand-400/30 hover:shadow-glow">
    <div class="relative aspect-[16/9] overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900">
        @if ($course->thumbnail)
            <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <img src="{{ asset('images/gambar_template.jpg') }}" alt="{{ $course->title }}" class="h-full w-full object-cover opacity-70 transition duration-500 group-hover:scale-105">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-transparent to-transparent"></div>
        <span class="absolute left-4 top-4 badge border-brand-400/20 bg-ink/70 text-brand-300">{{ $course->level }}</span>
    </div>
    <div class="flex flex-1 flex-col p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-brand-400">{{ $course->teacher->name }}</p>
        <h3 class="mt-2 line-clamp-2 text-xl font-extrabold text-white transition group-hover:text-brand-300">{{ $course->title }}</h3>
        <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-400">{{ $course->description }}</p>
        <div class="mt-5 flex items-center justify-between border-t border-white/5 pt-4 text-xs font-semibold text-slate-500">
            <span>{{ $course->materis_count ?? $course->materis->count() }} materi</span>
            <span>{{ $course->enrollments_count ?? 0 }} siswa</span>
            <span class="text-brand-400">Lihat kelas →</span>
        </div>
    </div>
</a>
