<x-guest-layout>
    <div class="mb-8">
        <p class="eyebrow">Selamat datang kembali</p>
        <h1 class="mt-3 font-display text-3xl font-extrabold text-white">Masuk ke Edutechia</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Masukkan email dan kata sandi Anda. OTP hanya diminta jika registrasi akun belum selesai diverifikasi.</p>
    </div>

    @if (session('status'))<div class="mb-5 rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-300">{{ session('status') }}</div>@endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <label class="label" for="email">Alamat email</label>
            <input class="field" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
            @error('email')<p class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror
        </div>
        <div>
            <div class="flex items-center justify-between"><label class="label" for="password">Kata sandi</label>@if (Route::has('password.request'))<a class="text-xs font-bold text-brand-400 hover:text-brand-300" href="{{ route('password.request') }}">Lupa kata sandi?</a>@endif</div>
            <input class="field" id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')<p class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror
        </div>
        <label class="flex items-center gap-3 text-sm text-slate-400"><input type="checkbox" name="remember" class="rounded border-white/20 bg-white/5 text-brand-400 focus:ring-brand-400"> Ingat perangkat ini</label>
        <button type="submit" class="btn-primary w-full py-3.5">Masuk</button>
    </form>

    <div class="my-7 flex items-center gap-4"><span class="h-px flex-1 bg-white/10"></span><span class="text-xs text-slate-600">BELUM PUNYA AKUN?</span><span class="h-px flex-1 bg-white/10"></span></div>
    <a href="{{ route('register') }}" class="btn-secondary w-full">Daftar sebagai siswa</a>
    <p class="mt-5 text-center text-xs leading-5 text-slate-500">Akun guru dibuat oleh administrator. Hubungi admin institusi Anda bila memerlukan akses guru.</p>
</x-guest-layout>
