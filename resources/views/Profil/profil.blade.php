@extends('layouts.public')
@section('title', 'Tim Kreator — Edutechia')
@section('content')
@php($members = [
    ['name'=>'Gilbran Shauma Aldebaran','email'=>'gilbranshauma@gmail.com','nim'=>'2300433','image'=>'agil.jpg'],
])
<main>
    <section class="border-b border-white/5 bg-white/[0.02]">
        <div class="shell py-16 text-center sm:py-20">
            <p class="eyebrow">Di balik Edutechia</p>
            <h1 class="heading mt-3">Dibangun oleh pembelajar, untuk pembelajar.</h1>
            <p class="mx-auto mt-5 max-w-2xl leading-7 text-slate-400">Tim Teknologi Pendidikan Universitas Pendidikan Indonesia yang percaya bahwa teknologi seharusnya membuat belajar terasa lebih dekat.</p>
            <a href="mailto:edutechia.id@gmail.com" class="mt-5 inline-block font-bold text-brand-400">edutechia.id@gmail.com</a>
        </div>
        </section>
    <section class="shell py-16">
    <div class="center flex justify-center flex-wrap gap-6">
        @foreach($members as $member) 
            <article class="card p-3"> 
                <div class="overflow-hidden rounded-xl"> 
                    <img src="{{ asset('images/creator/'.$member['image']) }}" alt="{{ $member['name'] }}" class="h-64 w-full object-cover transition duration-500 group-hover:scale-105">
                </div> 
                <div class="p-4">
                    <h2 class="text-lg font-extrabold text-white">{{ $member['name'] }}</h2> 
                    <p class="mt-1 text-sm text-brand-400">{{ $member['nim'] }}</p> 
                    <a class="mt-3 block truncate text-xs text-slate-500 hover:text-white" href="mailto:{{ $member['email'] }}">{{ $member['email'] }}</a> 
                </div>
            </article>
        @endforeach
    </div>
</section>

</main>
@endsection
