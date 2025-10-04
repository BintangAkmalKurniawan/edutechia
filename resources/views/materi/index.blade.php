<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10">
        <h1 class="text-2xl font-bold text-[#4cc9f0] mb-6">Daftar Materi</h1>

        <a href="{{ route('materi.create') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg mb-4 inline-block">
            + Tambah Materi
        </a>

        <div class="space-y-4">
            @foreach ($materis as $materi)
                <div class="p-4 bg-white shadow rounded-lg">
                    <h2 class="font-bold text-lg">{{ $materi->judul }}</h2>

                    @if ($materi->thumbnail)
                        <img src="{{ asset('storage/' . $materi->thumbnail) }}" class="w-40 mt-2 rounded">
                    @endif

                    @if ($materi->file_materi)
                        <p class="mt-2">
                            <a href="{{ asset('storage/' . $materi->file_materi) }}" class="text-blue-500 underline"
                                target="_blank">
                                Lihat File Materi
                            </a>
                        </p>
                    @endif

                    @if ($materi->videos->count())
                        <div class="mt-2 space-y-2">
                            @foreach ($materi->videos as $video)
                                <video controls class="w-full max-w-md rounded">
                                    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                                    Browser anda tidak mendukung video.
                                </video>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
