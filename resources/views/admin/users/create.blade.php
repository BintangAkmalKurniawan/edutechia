<x-app-layout>
    <x-slot name="header"><div><p class="eyebrow">Akun pengajar</p><h1 class="mt-2 text-2xl font-extrabold text-white">Tambahkan guru baru</h1><p class="mt-1 text-sm text-slate-500">Guru tidak dapat mendaftar sendiri. Admin membuat kredensial awal di sini.</p></div></x-slot>
    <div class="shell py-8"><div class="mx-auto max-w-2xl"><form method="POST" action="{{ route('admin.users.store') }}" class="card space-y-5 p-6 sm:p-8">@csrf
        <div><label class="label" for="name">Nama lengkap</label><input class="field" id="name" name="name" value="{{ old('name') }}" required autofocus></div>
        <div><label class="label" for="email">Alamat email</label><input class="field" id="email" type="email" name="email" value="{{ old('email') }}" required></div>
        <div class="grid gap-5 sm:grid-cols-2"><div><label class="label" for="institution_id">NIP / ID institusi</label><input class="field" id="institution_id" name="institution_id" value="{{ old('institution_id') }}"></div><div><label class="label" for="phone">Nomor telepon</label><input class="field" id="phone" name="phone" value="{{ old('phone') }}"></div></div>
        <div class="grid gap-5 sm:grid-cols-2"><div><label class="label" for="password">Kata sandi awal</label><input class="field" id="password" type="password" name="password" required autocomplete="new-password"></div><div><label class="label" for="password_confirmation">Konfirmasi sandi</label><input class="field" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div></div>
        <div class="rounded-xl border border-brand-400/15 bg-brand-400/5 p-4 text-xs leading-6 text-slate-400">Akun langsung aktif dan terverifikasi. Guru dapat masuk menggunakan email dan kata sandi awal tanpa OTP.</div>
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"><a href="{{ route('admin.users.index') }}" class="btn-secondary">Batalkan</a><button type="submit" class="btn-primary">Buat akun guru</button></div>
    </form></div></div>
</x-app-layout>
