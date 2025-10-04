<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Materi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('materi.update', $materi) }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Judul -->
                        <div>
                            <x-input-label for="judul" :value="__('Judul Materi')" />
                            <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul"
                                :value="old('judul', $materi->judul)" required autofocus />
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi Materi')" />
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                class="block mt-1 w-full text-black border-gray-300 dark:border-gray-700 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                        </div>

                        <!-- link kuis -->
                        <div>
                            <x-input-label for="link_kuis" :value="__('Link Kuis')" />
                            <x-text-input id="link_kuis" class="block mt-1 w-full" type="text" name="link_kuis"
                                :value="old('link_kuis', $materi->link_kuis)" required autofocus />
                        </div>

                        <!-- link diskusi -->
                        <div>
                            <x-input-label for="link_diskusi" :value="__('Link Diskusi')" />
                            <x-text-input id="link_diskusi" class="block mt-1 w-full" type="text" name="link_diskusi"
                                :value="old('link_diskusi', $materi->link_diskusi)" autofocus />
                        </div>

                        <!-- Thumbnail -->
                        <div>
                            <x-input-label for="thumbnail" :value="__('Ganti Thumbnail (Opsional)')" />
                            @if ($materi->thumbnail)
                                <img src="{{ asset('storage/' . $materi->thumbnail) }}" alt="Current Thumbnail"
                                    class="w-48 h-auto rounded-md my-2">
                            @endif
                            <input id="thumbnail" type="file" name="thumbnail" accept="image/*"
                                class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update Materi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
