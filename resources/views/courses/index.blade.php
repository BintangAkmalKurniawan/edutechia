@extends('layouts.public')
@section('title', 'Katalog Kelas — Edutechia')
@section('content')
<main>
    <section class="border-b border-white/5 bg-white/[0.02]">
        <div class="shell py-16 sm:py-20">
            <p class="eyebrow">Katalog pembelajaran</p>
            <div class="mt-3 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between"><div><h1 class="heading">Temukan kelas yang menyalakan rasa ingin tahu.</h1><p class="mt-4 max-w-2xl leading-7 text-slate-400">Cari berdasarkan topik, deskripsi, atau tingkat pembelajaran.</p></div><form method="GET" class="flex w-full max-w-md gap-2"><label for="search" class="sr-only">Cari kelas</label><input id="search" name="search" value="{{ $search }}" class="field mt-0" placeholder="Cari kelas..."><button class="btn-primary" type="submit">Cari</button></form></div>
        </div>
    </section>
    <section class="shell py-14">
        @if ($courses->isEmpty())
            <div class="card p-12 text-center"><h2 class="text-xl font-bold text-white">Kelas tidak ditemukan</h2><p class="mt-2 text-slate-500">Coba kata kunci lain atau lihat kembali nanti.</p>@if($search)<a href="{{ route('courses.index') }}" class="btn-secondary mt-6">Hapus pencarian</a>@endif</div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">@foreach ($courses as $course)<x-course-card :course="$course" />@endforeach</div>
            <div class="mt-10">{{ $courses->links() }}</div>
        @endif
    </section>
</main>
@endsection
