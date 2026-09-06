<x-app-layout>
    <x-slot name="header"><div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"><div><a href="{{ route('materi.show',$materi) }}" class="text-sm font-bold text-brand-400">← Kembali ke materi</a><h1 class="mt-2 text-2xl font-extrabold text-white">{{ $materi->quiz?->title ?? 'Kuis: '.$materi->judul }}</h1></div>@if($canManage)<a href="{{ route('quiz.edit',$materi) }}" class="btn-primary">Kelola kuis</a>@endif</div></x-slot>
    <div class="shell py-8">
        @if(!$materi->quiz || $materi->quiz->questions->isEmpty())
            <div class="card mx-auto max-w-5xl p-6 sm:p-10"><div class="text-center"><span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-brand-400/10 text-2xl text-brand-400">?</span><h2 class="mt-5 text-xl font-bold text-white">Kuis internal belum tersedia</h2><p class="mt-2 text-sm leading-6 text-slate-500">{{ $materi->link_kuis ? 'Guru menyediakan kuis eksternal untuk materi ini.' : 'Guru belum menambahkan latihan untuk materi ini.' }}</p></div>@if($materi->link_kuis)@if(Str::contains($materi->link_kuis,'wordwall.net'))<div class="mt-7 aspect-video overflow-hidden rounded-xl border border-white/10 bg-black"><iframe src="{{ $materi->link_kuis }}" class="h-full w-full" allowfullscreen title="Kuis eksternal"></iframe></div>@endif<div class="text-center"><a href="{{ $materi->link_kuis }}" target="_blank" class="btn-primary mt-6">Buka kuis di tab baru ↗</a></div>@endif</div>
        @else
            <div class="mx-auto max-w-3xl">
                <div class="card p-6"><div class="flex flex-wrap gap-3"><span class="badge">{{ $materi->quiz->questions->count() }} pertanyaan</span><span class="badge">Lulus ≥ {{ $materi->quiz->passing_score }}%</span>@if($materi->quiz->duration_minutes)<span class="badge">{{ $materi->quiz->duration_minutes }} menit</span>@endif @unless($materi->quiz->is_published)<span class="badge text-amber-300">Pratinjau draf</span>@endunless</div>@if($materi->quiz->instructions)<p class="mt-5 text-sm leading-7 text-slate-400">{{ $materi->quiz->instructions }}</p>@endif</div>
                @if(auth()->user()->isStudent())
                    <form method="POST" action="{{ route('quiz.submit',$materi) }}" class="mt-5 space-y-5">@csrf
                        @foreach($materi->quiz->questions as $question)<fieldset class="card p-6"><legend class="text-base font-bold text-white"><span class="mr-2 text-brand-400">{{ $loop->iteration }}.</span>{{ $question->question }}</legend><div class="mt-5 space-y-3">@foreach($question->options as $option)<label class="flex cursor-pointer gap-3 rounded-xl border border-white/10 bg-white/[0.025] p-4 transition hover:border-brand-400/30"><input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="mt-1 text-brand-400 focus:ring-brand-400" required><span class="text-sm text-slate-300">{{ $option->option_text }}</span></label>@endforeach</div></fieldset>@endforeach
                        <button type="submit" class="btn-primary w-full py-4" onclick="return confirm('Kirim jawaban dan lihat hasil?')">Kirim jawaban</button>
                    </form>
                    @if($attempts->isNotEmpty())<section class="card mt-8 p-6"><h2 class="font-bold text-white">Riwayat percobaan</h2><div class="mt-4 divide-y divide-white/5">@foreach($attempts as $attempt)<a href="{{ route('quiz.result',[$materi,$attempt]) }}" class="flex items-center justify-between py-3 text-sm"><span class="text-slate-500">{{ $attempt->submitted_at?->translatedFormat('d M Y, H:i') }}</span><span class="font-bold {{ $attempt->score >= $materi->quiz->passing_score ? 'text-emerald-300' : 'text-amber-300' }}">{{ $attempt->score }}%</span></a>@endforeach</div></section>@endif
                @else
                    <div class="mt-5 rounded-xl border border-brand-400/20 bg-brand-400/10 p-4 text-sm text-brand-200">Ini adalah tampilan pratinjau. Hanya siswa yang dapat mengirim jawaban.</div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
