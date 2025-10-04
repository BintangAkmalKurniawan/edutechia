<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Materi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('materi.store') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Judul -->
                        <div>
                            <x-input-label for="judul" :value="__('Judul Materi')" />
                            <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul"
                                :value="old('judul')" required autofocus />
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi Materi')" />
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Link Kuis -->
                        <div>
                            <x-input-label for="link_kuis" :value="__('Link Kuis')" />
                            <x-text-input id="link_kuis" class="block mt-1 w-full" type="text" name="link_kuis"
                                :value="old('link_kuis')" required autofocus />
                        </div>

                        <!-- Link Diskusi -->
                        <div>
                            <x-input-label for="link_diskusi" :value="__('Link Diskusi')" />
                            <x-text-input id="link_diskusi" class="block mt-1 w-full" type="text" name="link_diskusi"
                                :value="old('link_diskusi')" required autofocus />
                        </div>

                        <!-- Thumbnail -->
                        <div>
                            <x-input-label for="thumbnail" :value="__('Thumbnail (Gambar)')" />
                            <input id="thumbnail" type="file" name="thumbnail" accept="image/*"
                                class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                        </div>

                        <!-- Modul Materi -->
                        <div>
                            <x-input-label for="files" :value="__('Modul Materi (Format file: PDF, PPT, DOC)')" />
                            <input id="files" type="file" name="files[]" multiple
                                accept=".pdf,.doc,.docx,.ppt,.pptx"
                                class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                        </div>

                        <!-- Video -->
                        <div>
                            <x-input-label for="videos" :value="__('Video Pembelajaran (Maksimal 5GB)')" />
                            <input id="videos" type="file" name="videos[]" multiple accept="video/*"
                                class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan Materi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
