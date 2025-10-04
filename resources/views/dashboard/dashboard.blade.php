<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Dashboard Materi Anda') }}
            </h2>
            <a href="{{ route('materi.create') }}"
                class="px-4 py-2 bg-yellow-500 text-gray-900 rounded-md hover:bg-yellow-600 font-semibold transition">
                + Tambah Materi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded relative"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <h3 class="text-2xl font-bold text-black mb-6">Daftar Materi Anda</h3>

                    @if ($materis->isEmpty())
                        <p class="text-center text-gray-400 py-10">
                            Anda belum menambahkan materi apapun. <br>
                            <a href="{{ route('materi.create') }}" class="text-yellow-400 hover:underline">Mulai buat
                                materi pertama Anda!</a>
                        </p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($materis as $materi)
                                <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                                    <a href="{{ route('materi.show', $materi) }}" class="block group">
                                        <div class="relative">
                                            @if ($materi->thumbnail)
                                                <img src="{{ asset('storage/' . $materi->thumbnail) }}" alt="Thumbnail"
                                                    class="w-full h-48 object-cover">
                                            @else
                                                <div class="w-full h-48 bg-gray-600 flex items-center justify-center">
                                                    <span class="text-black">Tidak ada thumbnail</span>
                                                </div>
                                            @endif
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300">
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <h4 class="font-bold text-lg text-black truncate">{{ $materi->judul }}</h4>
                                            <p class="text-sm text-black mt-1 h-10">
                                                {{ Str::limit($materi->deskripsi, 80) }}
                                            </p>
                                        </div>
                                    </a>
                                    <div class="mt-auto p-4 border-gray-600 flex justify-end space-x-2">
                                        <a href="{{ route('materi.edit', $materi) }}"
                                            class="text-sm text-blue-400 hover:text-blue-300 font-semibold p-2">Edit</a>
                                        <form method="POST" action="{{ route('materi.destroy', $materi) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm text-red-400 hover:text-red-300 font-semibold p-2">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
