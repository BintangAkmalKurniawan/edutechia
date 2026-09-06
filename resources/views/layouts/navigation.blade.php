<nav x-data="{ open: false, profile: false }" class="sticky top-0 z-50 border-b border-white/5 bg-ink/90 backdrop-blur-xl">
    <div class="shell flex h-20 items-center justify-between gap-6">
        <div class="flex items-center gap-8">
            <a href="{{ route('dashboard') }}" class="shrink-0"><img src="{{ asset('images/logo.png') }}" class="h-9 w-auto" alt="Edutechia"></a>
            <div class="hidden items-center gap-1 lg:flex">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('courses.index') ? 'nav-link-active' : '' }}" href="{{ route('courses.index') }}">Katalog kelas</a>
                @if (auth()->user()->isTeacher() || auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('courses.create') ? 'nav-link-active' : '' }}" href="{{ route('courses.create') }}">Buat kelas</a>
                @endif
                @if (auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : '' }}" href="{{ route('admin.users.index') }}">Pengguna</a>
                @endif
            </div>
        </div>

        <div class="hidden items-center gap-3 md:flex">
            <span class="badge capitalize">{{ auth()->user()->role }}</span>
            <div class="relative">
                <button @click="profile = !profile" class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.045] px-3 py-2 text-left transition hover:bg-white/[0.08]">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-400 font-extrabold text-ink">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="hidden xl:block"><span class="block max-w-36 truncate text-sm font-bold text-white">{{ auth()->user()->name }}</span><span class="block max-w-36 truncate text-xs text-slate-500">{{ auth()->user()->email }}</span></span>
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                </button>
                <div x-cloak x-show="profile" @click.outside="profile = false" class="absolute right-0 mt-2 w-52 rounded-xl border border-white/10 bg-panel p-2 shadow-2xl">
                    <a href="{{ route('profile.edit') }}" class="nav-link block">Pengaturan profil</a>
                    <a href="{{ route('welcome') }}" class="nav-link block">Halaman publik</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-link block w-full text-left text-red-300" type="submit">Keluar</button></form>
                </div>
            </div>
        </div>

        <button @click="open = !open" class="rounded-xl border border-white/10 p-2 text-white md:hidden" aria-label="Buka navigasi">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    <div x-cloak x-show="open" class="border-t border-white/5 px-4 py-4 md:hidden">
        <div class="flex flex-col gap-1">
            <p class="mb-2 px-3 text-sm font-bold text-white">{{ auth()->user()->name }} <span class="text-slate-500">· {{ auth()->user()->role }}</span></p>
            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="nav-link" href="{{ route('courses.index') }}">Katalog kelas</a>
            @if (auth()->user()->isTeacher() || auth()->user()->isAdmin())<a class="nav-link" href="{{ route('courses.create') }}">Buat kelas</a>@endif
            @if (auth()->user()->isAdmin())<a class="nav-link" href="{{ route('admin.users.index') }}">Kelola pengguna</a>@endif
            <a class="nav-link" href="{{ route('profile.edit') }}">Pengaturan profil</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-link w-full text-left text-red-300" type="submit">Keluar</button></form>
        </div>
    </div>
</nav>
