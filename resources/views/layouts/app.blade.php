<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Edutechia') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ink text-slate-200">
    @include('layouts.navigation')

    @isset($header)
        <header class="border-b border-white/5 bg-white/[0.025]">
            <div class="shell py-7">{{ $header }}</div>
        </header>
    @endisset

    <main class="min-h-[calc(100vh-13rem)]">
        <div class="shell pt-6">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm font-semibold text-emerald-300" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-400/20 bg-red-400/10 px-4 py-3 text-sm text-red-200" role="alert">
                    <p class="font-bold">Ada data yang perlu diperbaiki:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
        </div>
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-white/5 py-8 text-center text-sm text-slate-500">© {{ date('Y') }} Edutechia · Belajar, bertumbuh, berkarya.</footer>
</body>
</html>
