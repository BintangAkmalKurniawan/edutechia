<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Edutechia — platform pembelajaran digital untuk belajar, bertumbuh, dan berkarya.">
    <title>@yield('title', 'Edutechia — Belajar Tanpa Batas')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-ink">
    <header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-white/5 bg-ink/85 backdrop-blur-xl">
        <div class="shell flex h-20 items-center justify-between">
            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3" aria-label="Edutechia beranda">
                <img src="{{ asset('images/logo.png') }}" alt="Edutechia" class="h-10 w-auto">
            </a>
            <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                <a class="nav-link {{ request()->routeIs('welcome') ? 'nav-link-active' : '' }}" href="{{ route('welcome') }}">Beranda</a>
                <a class="nav-link {{ request()->routeIs('courses.*') ? 'nav-link-active' : '' }}" href="{{ route('courses.index') }}">Jelajahi kelas</a>
                <a class="nav-link {{ request()->routeIs('profil.creator') ? 'nav-link-active' : '' }}" href="{{ route('profil.creator') }}">Profil kreator</a>
            </nav>
            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">Buka dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar siswa</a>
                @endauth
            </div>
            <button @click="open = !open" class="rounded-xl border border-white/10 p-2 text-white md:hidden" aria-label="Buka menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div x-cloak x-show="open" class="border-t border-white/5 px-4 py-4 md:hidden">
            <div class="flex flex-col gap-2">
                <a class="nav-link" href="{{ route('welcome') }}">Beranda</a>
                <a class="nav-link" href="{{ route('courses.index') }}">Jelajahi kelas</a>
                <a class="nav-link" href="{{ route('profil.creator') }}">Profil kreator</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary mt-2">Buka dashboard</a>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar siswa</a>
                @endauth
            </div>
        </div>
    </header>

    @yield('content')

    <footer class="border-t border-white/5 bg-[#050c12]">
        <div class="shell flex flex-col gap-6 py-10 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="Edutechia" class="mb-3 h-8 w-auto opacity-90">
                <p>Teknologi Pendidikan · Universitas Pendidikan Indonesia</p>
            </div>
            <div class="flex flex-wrap gap-5">
                <a class="hover:text-brand-400" href="{{ route('courses.index') }}">Katalog</a>
                <a class="hover:text-brand-400" href="{{ route('profil.creator') }}">Tim</a>
                <a class="hover:text-brand-400" href="mailto:edutechia.id@gmail.com">Kontak</a>
            </div>
            <p>© {{ date('Y') }} Edutechia.</p>
        </div>
    </footer>
    @include('layouts.alerts')
</body>
</html>
