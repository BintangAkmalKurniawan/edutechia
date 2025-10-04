<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edutechia - Belajar Apapun, Kapanpun</title>
    @vite('resources/css/app.css')
</head>

<body class="antialiased bg-gray-900 text-gray-200">
    <div class="relative min-h-screen">
        <header class="absolute top-0 left-0 right-0 z-10 p-6 flex justify-between items-center">
            <div>
                <img src="{{ asset('images/logo.png') }}" class="w-32" alt="Edutechia Logo">
            </div>
            <nav class="flex items-center space-x-6">
                <a href="{{ route('profil.creator') }}"
                    class="font-semibold text-white hover:text-yellow-400 transition">Profil Kreator</a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="font-semibold text-white hover:text-yellow-400 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-white hover:text-yellow-400 transition">Log
                            in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="font-semibold text-white hover:text-yellow-400 transition">Register</a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <!-- Hero Section -->
        <div class="relative h-96 flex items-center justify-center text-center bg-cover bg-center"
            style="background-image: url('{{ asset('images/creator/all.jpg') }}');">
            <div class="absolute inset-0 bg-black opacity-60"></div>
            <div class="relative z-10 px-4">
                <h1 class="text-5xl font-extrabold text-white">Selamat Datang di Edutechia</h1>
                <p class="mt-4 text-xl text-gray-300">Platform pembelajaran online untuk masa depan Anda.</p>
            </div>
        </div>


        <!-- Content Section -->
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            @if ($materis->isEmpty())
                <p class="text-center text-gray-400">Belum ada materi yang tersedia saat ini.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($materis as $materi)
                        <a href="{{ route('materi.show', $materi) }}"
                            class="flex flex-col bg-gray-800 rounded-lg shadow-lg hover:shadow-yellow-400/20 hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
                            <div class="relative">
                                @if ($materi->thumbnail)
                                    <img src="{{ asset('storage/' . $materi->thumbnail) }}" alt="Thumbnail"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-500">Tidak ada thumbnail</span>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300">
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex-grow">
                                    <h3 class="font-bold text-xl text-white truncate">{{ $materi->judul }}</h3>
                                    <p class="text-sm text-gray-400 mt-1">Oleh: {{ $materi->user->name }}</p>
                                    <p class="text-gray-300 mt-2">
                                        {{ Str::limit($materi->deskripsi, 100) }}
                                    </p>
                                </div>

                                <div class="text-right mt-4">
                                    <span class="font-semibold text-yellow-400">Pelajari Selengkapnya &rarr;</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>

</html>
