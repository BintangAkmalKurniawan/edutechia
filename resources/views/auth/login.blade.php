<!-- Tombol Back di luar container login -->
<div class="absolute top-32 left-64">
    <a href="{{ url('/') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
        ← Back
    </a>
</div>

<x-guest-layout>
    <h1 class="text-2xl font-bold text-center mb-6 text-[#4cc9f0]">Login Eduniverse</h1>

    <!-- Status -->
    <x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-white" />
            <x-text-input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username"
                class="block mt-1 w-full rounded-lg border-0 focus:ring-2 focus:ring-[#4cc9f0] p-3 text-black" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-white" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                class="block mt-1 w-full rounded-lg border-0 focus:ring-2 focus:ring-[#4cc9f0] p-3 text-black" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                class="rounded border-gray-300 text-[#4cc9f0] focus:ring-[#4cc9f0]">
            <label for="remember_me" class="ml-2 text-sm text-gray-300">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Actions -->
        <div class="flex flex-col space-y-3 mt-6">
            <button type="submit"
                class="w-full py-3 bg-white text-black font-semibold rounded-full shadow-md hover:bg-[#ffdd57] hover:text-black transition">
                {{ __('Log in') }}
            </button>

            @if (Route::has('password.request'))
                <a class="text-sm text-[#ffdd57] hover:underline text-center" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <a href="{{ route('register') }}" class="text-sm text-[#4cc9f0] hover:underline text-center">
                {{ __("Don't have an account? Register") }}
            </a>
        </div>
    </form>
</x-guest-layout>
