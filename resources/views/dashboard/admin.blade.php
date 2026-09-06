<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div><p class="eyebrow">Pusat kendali</p><h1 class="mt-2 text-2xl font-extrabold text-white">Dashboard administrator</h1><p class="mt-1 text-sm text-slate-500">Pantau ekosistem dan kelola akses pengguna Edutechia.</p></div>
            <div class="flex gap-3"><a href="{{ route('admin.users.create') }}" class="btn-primary">+ Tambah guru</a><a href="{{ route('courses.create') }}" class="btn-secondary">Buat kelas</a></div>
        </div>
    </x-slot>

    <div class="shell py-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Guru terdaftar" :value="$stats['teachers']" hint="Dibuat oleh administrator" />
            <x-stat-card label="Siswa terdaftar" :value="$stats['students']" hint="Registrasi mandiri" />
            <x-stat-card label="Seluruh kelas" :value="$stats['courses']" />
            <x-stat-card label="Kelas terbit" :value="$stats['published']" />
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.1fr_.9fr]">
            <section class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-white/5 px-6 py-5"><div><h2 class="font-bold text-white">Pengguna terbaru</h2><p class="mt-1 text-xs text-slate-500">Akun yang baru bergabung</p></div><a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-brand-400">Kelola semua →</a></div>
                <div class="divide-y divide-white/5">
                    @forelse ($latestUsers as $item)
                        <div class="flex items-center gap-4 px-6 py-4"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/[0.06] font-bold text-white">{{ mb_strtoupper(mb_substr($item->name, 0, 1)) }}</span><div class="min-w-0 flex-1"><p class="truncate text-sm font-bold text-white">{{ $item->name }}</p><p class="truncate text-xs text-slate-500">{{ $item->email }}</p></div><span class="badge capitalize">{{ $item->role }}</span><span class="h-2 w-2 rounded-full {{ $item->status === 'active' ? 'bg-emerald-400' : 'bg-red-400' }}"></span></div>
                    @empty<p class="p-6 text-sm text-slate-500">Belum ada pengguna.</p>@endforelse
                </div>
            </section>

            <section class="card overflow-hidden">
                <div class="border-b border-white/5 px-6 py-5"><h2 class="font-bold text-white">Kelas terbaru</h2><p class="mt-1 text-xs text-slate-500">Aktivitas publikasi konten</p></div>
                <div class="divide-y divide-white/5">
                    @forelse ($latestCourses as $course)
                        <a href="{{ route('courses.show', $course) }}" class="flex items-center gap-4 px-6 py-4 transition hover:bg-white/[0.025]"><div class="min-w-0 flex-1"><p class="truncate text-sm font-bold text-white">{{ $course->title }}</p><p class="truncate text-xs text-slate-500">{{ $course->teacher->name }}</p></div><span class="badge {{ $course->status === 'published' ? 'text-emerald-300' : 'text-amber-300' }}">{{ $course->status }}</span></a>
                    @empty<p class="p-6 text-sm text-slate-500">Belum ada kelas.</p>@endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
