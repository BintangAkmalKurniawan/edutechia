<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Edutechia') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ink">
    <div class="relative isolate min-h-screen overflow-hidden">
        <div class="hero-grid pointer-events-none absolute inset-0 opacity-70"></div>
        <div class="pointer-events-none absolute -left-24 top-20 h-80 w-80 rounded-full bg-brand-400/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-12 h-96 w-96 rounded-full bg-cyan-400/10 blur-3xl"></div>
        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-10 sm:px-6">
            <div class="grid w-full overflow-hidden rounded-3xl border border-white/10 bg-[#0b151e]/90 shadow-2xl shadow-black/40 lg:grid-cols-[0.9fr_1.1fr]">
                <aside class="hidden min-h-[680px] flex-col justify-between border-r border-white/10 bg-gradient-to-br from-brand-400/15 via-white/[0.03] to-cyan-400/10 p-10 lg:flex">
                    <a href="{{ route('welcome') }}"><img src="{{ asset('images/logo.png') }}" alt="Edutechia" class="h-11 w-auto"></a>
                    <div>
                        <span class="eyebrow">Ruang belajar digital</span>
                        <h1 class="mt-4 font-display text-4xl font-extrabold leading-tight text-white">Satu langkah kecil menuju pemahaman yang lebih besar.</h1>
                        <p class="mt-5 leading-7 text-slate-400">Materi terstruktur, forum yang hidup, kuis interaktif, dan progres yang selalu tercatat.</p>
                    </div>
                    <p class="text-xs text-slate-500">Registrasi dilindungi verifikasi OTP melalui email.</p>
                </aside>
                <main class="flex items-center p-6 sm:p-10 lg:p-14">
                    <div class="w-full">
                        <a href="{{ route('welcome') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-brand-400 lg:hidden">← Kembali ke beranda</a>
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>
    @include('layouts.alerts')
</body>
</html>
