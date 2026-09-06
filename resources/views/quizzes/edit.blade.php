<x-app-layout>
    <x-slot name="header"><div><a href="{{ route('materi.show',$materi) }}" class="text-sm font-bold text-brand-400">← Kembali ke materi</a><h1 class="mt-2 text-2xl font-extrabold text-white">Kelola kuis: {{ $materi->judul }}</h1><p class="mt-1 text-sm text-slate-500">Saat ini penilaian otomatis mendukung pilihan ganda satu jawaban benar.</p></div></x-slot>
    @php
        $questionData = old('questions') ?? $quiz->questions->map(fn($question) => [
            'question' => $question->question,
            'explanation' => $question->explanation ?? '',
            'points' => $question->points,
            'correct_index' => max(0, $question->options->search(fn($option) => $option->is_correct)),
            'options' => $question->options->pluck('option_text')->values()->all(),
        ])->values()->all();
        if (empty($questionData)) $questionData = [['question'=>'','explanation'=>'','points'=>1,'correct_index'=>0,'options'=>['','','','']]];
    @endphp
    <div class="shell py-8">
        <form method="POST" action="{{ route('quiz.update',$materi) }}" x-data="{ questions: @js($questionData), addQuestion() { this.questions.push({ question: '', explanation: '', points: 1, correct_index: 0, options: ['', '', '', ''] }) }, removeQuestion(index) { if (this.questions.length > 1) this.questions.splice(index, 1) }, addOption(index) { if (this.questions[index].options.length < 6) this.questions[index].options.push('') }, removeOption(q, o) { if (this.questions[q].options.length > 2) { this.questions[q].options.splice(o, 1); if (this.questions[q].correct_index >= this.questions[q].options.length) this.questions[q].correct_index = 0 } } }" class="grid gap-6 xl:grid-cols-[1fr_320px]">
            @csrf @method('PUT')
            <section>
                <div class="card space-y-5 p-6 sm:p-8"><div><label class="label" for="title">Judul kuis</label><input class="field" id="title" name="title" value="{{ old('title',$quiz->title) }}" required></div><div><label class="label" for="instructions">Petunjuk pengerjaan</label><textarea class="field" id="instructions" name="instructions" rows="4" placeholder="Jelaskan cara mengerjakan kuis...">{{ old('instructions',$quiz->instructions) }}</textarea></div><div class="grid gap-5 sm:grid-cols-2"><div><label class="label" for="passing_score">Nilai kelulusan (%)</label><input class="field" id="passing_score" type="number" min="0" max="100" name="passing_score" value="{{ old('passing_score',$quiz->passing_score) }}" required></div><div><label class="label" for="duration_minutes">Durasi (menit)</label><input class="field" id="duration_minutes" type="number" min="1" max="300" name="duration_minutes" value="{{ old('duration_minutes',$quiz->duration_minutes) }}" placeholder="Tanpa batas"></div></div></div>
                <div class="mt-6 space-y-5">
                    <template x-for="(question, qIndex) in questions" :key="qIndex">
                        <article class="card p-6 sm:p-8"><div class="flex items-center justify-between"><h2 class="font-bold text-white">Pertanyaan <span x-text="qIndex + 1"></span></h2><button @click="removeQuestion(qIndex)" type="button" class="text-xs font-bold text-red-300" x-show="questions.length > 1">Hapus pertanyaan</button></div>
                            <div class="mt-5"><label class="label">Pertanyaan</label><textarea class="field" :name="`questions[${qIndex}][question]`" x-model="question.question" rows="3" required></textarea></div>
                            <div class="mt-5 space-y-3"><template x-for="(option, oIndex) in question.options" :key="oIndex"><div class="flex items-center gap-3"><input type="radio" :name="`questions[${qIndex}][correct_index]`" :value="oIndex" x-model.number="question.correct_index" class="text-brand-400 focus:ring-brand-400" required><input class="field mt-0 flex-1" :name="`questions[${qIndex}][options][${oIndex}]`" x-model="question.options[oIndex]" :placeholder="`Pilihan ${String.fromCharCode(65 + oIndex)}`" required><button @click="removeOption(qIndex,oIndex)" type="button" class="rounded-lg p-2 text-red-300" x-show="question.options.length > 2" aria-label="Hapus pilihan">×</button></div></template></div>
                            <button @click="addOption(qIndex)" x-show="question.options.length < 6" type="button" class="mt-4 text-xs font-bold text-brand-400">+ Tambah pilihan</button>
                            <div class="mt-5 grid gap-5 sm:grid-cols-[120px_1fr]"><div><label class="label">Poin</label><input class="field" type="number" min="1" max="100" :name="`questions[${qIndex}][points]`" x-model="question.points" required></div><div><label class="label">Penjelasan jawaban <span class="font-normal text-slate-500">(opsional)</span></label><textarea class="field" rows="2" :name="`questions[${qIndex}][explanation]`" x-model="question.explanation"></textarea></div></div>
                        </article>
                    </template>
                </div>
                <button @click="addQuestion" type="button" class="btn-secondary mt-6 w-full border-dashed">+ Tambah pertanyaan</button>
            </section>
            <aside class="space-y-5 xl:sticky xl:top-28 xl:self-start"><section class="card p-6"><label class="flex items-start gap-3"><input type="checkbox" name="is_published" value="1" class="mt-1 rounded border-white/20 bg-white/5 text-brand-400 focus:ring-brand-400" @checked(old('is_published',$quiz->is_published))><span><strong class="block text-sm text-white">Terbitkan kuis</strong><span class="mt-1 block text-xs leading-5 text-slate-500">Siswa dapat mengerjakan setelah perubahan disimpan.</span></span></label></section><section class="card p-6"><p class="text-sm font-bold text-white">Petunjuk</p><ul class="mt-3 space-y-2 text-xs leading-5 text-slate-500"><li>• Pilih lingkaran di kiri jawaban yang benar.</li><li>• Setiap pertanyaan harus memiliki 2–6 pilihan.</li><li>• Perubahan pertanyaan menggantikan struktur kuis lama.</li></ul></section><button class="btn-primary w-full" type="submit">Simpan kuis</button><a href="{{ route('quiz.show',$materi) }}" class="btn-secondary w-full">Pratinjau</a></aside>
        </form>
    </div>
</x-app-layout>
