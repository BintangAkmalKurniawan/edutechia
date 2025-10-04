<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Kuis: {{ $materi->judul }} - Edutechia</title>
</head>

<body class="bg-gray-900 text-gray-300 font-sans leading-normal tracking-normal">
    <header class="bg-gray-800 shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <a href="{{ route('welcome') }}">
                <img src="{{ asset('images/logo.png') }}" class="w-32" alt="Edutechia Logo">
            </a>
            <nav class="space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-200 hover:text-yellow-400">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-gray-200 hover:text-yellow-400">
                            Log Out
                        </a>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-200 hover:text-yellow-400">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-200 hover:text-yellow-400">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container mx-auto mt-10 px-6">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
            <a href="{{ url()->previous() }}" class="text-yellow-400 hover:underline mb-6 inline-block">&larr; Kembali
                ke Materi</a>
            <h1 class="text-4xl font-bold text-white mb-6">Kuis: {{ $materi->judul }}</h1>

            <div class="w-full h-auto overflow-hidden rounded-lg shadow-md">
                @if ($materi->link_kuis && Str::contains($materi->link_kuis, 'wordwall.net'))
                    <iframe src="{{ $materi->link_kuis }}" frameborder="0"
                        allow="camera; microphone; geolocation; display-capture; clipboard-write"
                        style="width: 100%; height: 608px; border: none; margin: 0; padding: 0;"
                        allowfullscreen></iframe>
                    <p class="text-center text-gray-300 text-sm mt-2">
                        Jika kuis tidak muncul, <a href="{{ $materi->link_kuis }}" target="_blank"
                            class="text-blue-400 underline">klik di sini untuk membuka di tab baru</a>.
                    </p>
                @else
                    <h3 class="text-2xl font-bold text-white mb-20 mt-20 text-center">
                        Kreator tidak menyediakan kuis
                    </h3>
                @endif
            </div>

        </div>
    </main>

    <footer class="text-center p-4 mt-10 text-sm text-gray-500 bg-gray-800 shadow">
        &copy; {{ date('Y') }} Edutechia. All Rights Reserved.
    </footer>
</body>

</html>
