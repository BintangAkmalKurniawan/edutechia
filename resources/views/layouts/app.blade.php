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
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-white/5 py-8 text-center text-sm text-slate-500">© {{ date('Y') }} Edutechia · Belajar, bertumbuh, berkarya.</footer>
    @include('layouts.alerts')
</body>
</html>
