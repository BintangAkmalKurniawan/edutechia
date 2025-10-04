<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>{{ $materi->judul }} - Edutechia</title>
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

            <!-- Judul Materi -->
            <h1 class="text-4xl font-bold text-white mb-2">{{ $materi->judul }}</h1>

            <!-- Info Guru -->
            <div class="mb-6">
                <p class="text-gray-400">
                    Dibuat oleh: <a href="#"
                        class="text-yellow-400 font-semibold hover:underline">{{ $materi->user->name }}</a>
                </p>
                <p class="text-sm text-gray-500">
                    Dipublikasikan pada: {{ $materi->created_at->format('d F Y') }}
                </p>
            </div>

            <!-- Video -->
            @if ($materi->videos->isNotEmpty())
                <div class="mb-8 w-full aspect-video">
                    <video controls class="w-full h-full rounded-lg shadow-md bg-black">
                        <source src="{{ asset('storage/' . $materi->videos->first()->video_path) }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            @elseif($materi->thumbnail)
                <div class="mb-8">
                    <img src="{{ asset('storage/' . $materi->thumbnail) }}" alt="Thumbnail"
                        class="w-full h-32 object-cover rounded-lg shadow-md">
                </div>
            @endif

            <!-- Deskripsi -->
            <div class="prose prose-invert max-w-none">
                <h2 class="text-2xl font-semibold text-gray-200 mb-4">Deskripsi Materi</h2>
                <p>
                    {{ $materi->deskripsi }}
                </p>
            </div>

            <!-- Kuis -->
            <div class="flex gap-4 prose prose-invert max-w-none">
                <a class="px-7 py-4 mt-4 bg-blue-400 font-bold rounded-lg"
                    href="{{ route('materi.kuis', $materi->id) }}">Kuis</a>
                <a class="px-7 py-4 mt-4 bg-yellow-400 text-black font-bold rounded-lg"
                    href="{{ route('materi.diskusi', $materi->id) }}">Forum Diskusi</a>
            </div>

            <!-- Modul Materi -->
            @if ($materi->files->isNotEmpty())
                <div class="mt-8 border-t border-gray-700 pt-6">
                    <h2 class="text-2xl font-semibold text-gray-200 mb-4">Modul & Berkas Pendukung</h2>
                    <ul class="space-y-3">
                        @foreach ($materi->files as $file)
                            <li>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                    class="flex items-center p-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition duration-300">
                                    <svg class="w-6 h-6 text-yellow-400 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span
                                        class="text-yellow-400 font-medium">{{ $file->original_name ?? basename($file->file_path) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </main>

    <footer class="text-center p-4 mt-10 text-sm text-gray-500 bg-gray-800 shadow">
        &copy; {{ date('Y') }} Edutechia. All Rights Reserved.
    </footer>
</body>

</html>
