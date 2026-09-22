@extends('layouts.public')

@section('title', 'Edutechia — Belajar Tanpa Batas')

@section('content')
<main>
    <section class="relative overflow-hidden border-b border-white/5">
        <div class="hero-grid absolute inset-0"></div>

        <div class="absolute left-1/2 top-10 h-[28rem] w-[28rem] -translate-x-1/2 rounded-full bg-brand-400/10 blur-3xl"></div>

        <div class="px-10 relative grid min-h-[600px] items-center py-10">
            <div class="flex gap-20">
                <div class="grid grid-column max-w-2xl">

                <div class="inline-flex items-center gap-2 rounded-full border border-brand-400/20 bg-brand-400/10 px-4 py-2 text-xs font-bold uppercase tracking-[.16em] text-brand-300">
                    <span class="h-2 w-2 rounded-full bg-brand-400 shadow-[0_0_16px_#ffd449]"></span>
                    Belajar hari ini, memimpin esok hari
                </div>

                <h1 class="mt-7 font-display text-5xl font-extrabold leading-[1.04] tracking-tight text-white sm:text-6xl lg:text-7xl">
                    Pengetahuan yang 
                    <span class="text-brand-400">bergerak</span> 
                    bersama Anda.
                </h1>

                <p class="mt-7 max-w-2xl text-lg leading-8 text-slate-400 sm:text-xl">
                    Edutechia menghadirkan materi terstruktur, ruang diskusi, kuis, dan pemantauan progres dalam satu pengalaman belajar yang jernih.
                </p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('courses.index') }}" class="btn-primary px-7 py-4">
                        Jelajahi kelas <span class="ml-2">→</span>
                    </a>

                    @guest
                        <a href="{{ route('register') }}" class="btn-secondary px-7 py-4">
                            Mulai sebagai siswa
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-secondary px-7 py-4">
                            Lanjutkan belajar
                        </a>
                    @endguest
                </div>

                <div class="mt-12 grid max-w-xl grid-cols-3 gap-4 border-t border-white/10 pt-7">
                    <div>
                        <p class="text-2xl font-extrabold text-white">
                            {{ $stats['courses'] }}+
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Kelas aktif</p>
                    </div>

                    <div>
                        <p class="text-2xl font-extrabold text-white">
                            {{ $stats['materials'] }}+
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Materi belajar</p>
                    </div>

                    <div>
                        <p class="text-2xl font-extrabold text-white">
                            {{ $stats['learners'] }}+
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Pembelajar</p>
                    </div>
                </div>
            </div>
            <div class="pt-6 lg:pt-8 py-10">
                <div class="w-[700px] h-screen">
                    <div class="glass rounded-2xl p-2 shadow-xl shadow-black/30">
                        <img 
                            src="{{ asset('images/logo_awal.jpeg') }}" 
                            alt="Tim Edutechia"
                            class="h-40 w-full rounded-xl object-cover opacity-90 sm:h-64"
                        >
                    </div>
            </div>
            </div>
            
        </div>
        </div>
    </section>

    {{-- KELAS PILIHAN --}}
    <section class="shell py-24">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow">Kelas pilihan</p>
                <h2 class="heading mt-3">Mulai dari rasa ingin tahu.</h2>
                <p class="mt-4 max-w-2xl text-slate-400">
                    Materi dirancang guru, dilengkapi latihan dan forum agar pembelajaran tidak berhenti pada menonton.
                </p>
            </div>

            <a href="{{ route('courses.index') }}" class="text-sm font-bold text-brand-400 hover:text-brand-300">
                Lihat seluruh kelas →
            </a>
        </div>

        @if ($courses->isEmpty())
            <div class="card mt-10 p-12 text-center">
                <p class="text-lg font-bold text-white">Kelas sedang dipersiapkan.</p>
                <p class="mt-2 text-slate-500">
                    Silakan kembali lagi setelah guru menerbitkan materi pertama.
                </p>
            </div>
        @else
            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
                    <x-course-card :course="$course" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- EKOSISTEM --}}
    <section class="border-y border-white/5 bg-white/[0.025]">
        <div class="shell grid gap-10 py-24 lg:grid-cols-[.8fr_1.2fr] lg:items-center">

            <div>
                <p class="eyebrow">Satu ekosistem</p>
                <h2 class="heading mt-3">
                    Dari materi sampai refleksi, semuanya terhubung.
                </h2>
                <p class="mt-5 leading-7 text-slate-400">
                    Setiap peran memiliki ruang kerja yang fokus. Admin menjaga ekosistem,
                    guru menyusun pengalaman belajar, dan siswa melihat langkah berikutnya dengan jelas.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">

                <article class="card p-6">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-400/10 font-extrabold text-brand-400">
                        01
                    </span>
                    <h3 class="mt-5 font-bold text-white">Materi terstruktur</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Video, bacaan, dan berkas dalam urutan yang mudah diikuti.
                    </p>
                </article>

                <article class="card p-6">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-cyan-400/10 font-extrabold text-cyan-300">
                        02
                    </span>
                    <h3 class="mt-5 font-bold text-white">Diskusi kontekstual</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Pertanyaan dan balasan menempel langsung pada setiap materi.
                    </p>
                </article>

                <article class="card p-6">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-400/10 font-extrabold text-emerald-300">
                        03
                    </span>
                    <h3 class="mt-5 font-bold text-white">Progres nyata</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Kuis dan status materi memberi gambaran perjalanan belajar.
                    </p>
                </article>

            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="shell py-24">
        <div class="overflow-hidden rounded-3xl border border-brand-400/20 bg-gradient-to-r from-brand-400/15 via-panel to-cyan-400/10 p-8 sm:p-12">

            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <p class="eyebrow">Siap belajar?</p>
                    <h2 class="mt-3 font-display text-3xl font-extrabold text-white sm:text-4xl">
                        Buat ruang untuk satu pengetahuan baru hari ini.
                    </h2>
                    <p class="mt-3 text-slate-400">
                        Registrasi siswa gratis. Guru bergabung melalui undangan administrator.
                    </p>
                </div>

                <a href="{{ route('register') }}" class="btn-primary shrink-0 px-7 py-4">
                    Daftar sekarang
                </a>

            </div>
        </div>
    </section>

</main>
@endsection