<x-guest-layout>
    <div class="mx-auto max-w-md text-center">
        <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl border border-brand-400/20 bg-brand-400/10 text-brand-400">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.52a2 2 0 0 1-2.12 0L2.25 6.75"/></svg>
        </span>
        <p class="eyebrow mt-6">Verifikasi registrasi</p>
        <h1 class="mt-3 font-display text-3xl font-extrabold text-white">Selesaikan registrasi Anda</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Kami mengirim enam digit kode registrasi baru ke <strong class="text-slate-200">{{ $maskedEmail }}</strong>. Kode berlaku selama {{ config('auth.otp_expiration', 10) }} menit.</p>
    </div>

    @if (session('status'))<div class="mt-6 rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-center text-sm text-emerald-300">{{ session('status') }}</div>@endif

    <form method="POST" action="{{ route('otp.verify') }}" class="mt-7">
        @csrf
        <label for="code" class="sr-only">Kode OTP</label>
        <input id="code" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus autocomplete="one-time-code" class="field text-center font-mono text-3xl font-extrabold tracking-[.45em]" placeholder="000000">
        @error('code')<p class="mt-3 text-center text-sm text-red-300">{{ $message }}</p>@enderror
        <button type="submit" class="btn-primary mt-5 w-full py-3.5">Verifikasi registrasi & masuk</button>
    </form>

    <div class="mt-6 flex items-center justify-center gap-4 text-sm">
        <form method="POST" action="{{ route('otp.resend') }}">@csrf<button type="submit" class="font-bold text-brand-400 hover:text-brand-300">Kirim ulang kode</button></form>
        <span class="text-slate-700">•</span>
        <form method="POST" action="{{ route('otp.cancel') }}">@csrf @method('DELETE')<button type="submit" class="font-semibold text-slate-500 hover:text-white">Batalkan</button></form>
    </div>
    <p class="mt-8 text-center text-xs leading-5 text-slate-600">Jangan pernah membagikan kode OTP kepada siapa pun, termasuk pihak yang mengaku sebagai administrator.</p>
</x-guest-layout>
