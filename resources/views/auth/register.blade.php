<x-guest-layout>
    <div class="mb-8">
        <p class="eyebrow">Akun siswa</p>
        <h1 class="mt-3 font-display text-3xl font-extrabold text-white">Mulai perjalanan belajar</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Registrasi publik tersedia untuk siswa. Email akan diverifikasi menggunakan OTP.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div><label class="label" for="name">Nama lengkap</label><input class="field" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Anda">@error('name')<p class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror</div>
        <div><label class="label" for="institution_id">NIS / NIM <span class="font-normal text-slate-500">(opsional)</span></label><input class="field" id="institution_id" name="institution_id" value="{{ old('institution_id') }}" placeholder="Nomor identitas institusi">@error('institution_id')<p class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror</div>
        <div><label class="label" for="email">Alamat email</label><input class="field" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com">@error('email')<p class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror</div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div><label class="label" for="password">Kata sandi</label><input class="field" id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter">@error('password')<p class="mt-2 text-sm text-red-300">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="password_confirmation">Ulangi sandi</label><input class="field" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi sandi"></div>
        </div>
        <button type="submit" class="btn-primary mt-2 w-full py-3.5">Buat akun & kirim OTP</button>
    </form>

    <p class="mt-7 text-center text-sm text-slate-500">Sudah terdaftar? <a href="{{ route('login') }}" class="font-bold text-brand-400 hover:text-brand-300">Masuk di sini</a></p>
</x-guest-layout>
