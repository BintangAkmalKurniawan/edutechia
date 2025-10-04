<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Navbar Example</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav x-data="{ open: false }" class="bg-white py-5 border-b border-gray-200 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0">
            <div class="flex justify-between h-16">
                <!-- Left side -->
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <img src="images/logo2.png" alt="Logo" class="h-64 w-auto" />
                        </a>
                    </div>

                    <!-- Links -->
                    <div class="hidden space-x-8 pt-4 sm:flex sm:ml-10">
                        <a href="dashboard.html" class="text-gray-700 hover:text-indigo-600 font-medium">Dashboard</a>
                        <a href="tambah-materi.html" class="text-gray-700 hover:text-indigo-600 font-medium">Tambah
                            Materi</a>
                    </div>
                </div>

                <!-- Right side -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Dropdown -->
                    <div class="relative">
                        <button
                            class="flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-gray-800 focus:outline-none">
                            <span class="mr-2">Nama User</span>
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 111.06 1.061l-4.24 4.25a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg hidden group-hover:block">
                            <a href="profile.html" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="logout.html" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                </div>

                <!-- Hamburger menu (mobile) -->
                <div class="flex items-center sm:hidden">
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                        class="p-2 rounded-md text-gray-600 hover:bg-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden sm:hidden px-4 pt-2 pb-3 space-y-1">
            <a href="dashboard.html" class="block text-gray-700 hover:text-indigo-600">Dashboard</a>
            <a href="tambah-materi.html" class="block text-gray-700 hover:text-indigo-600">Tambah Materi</a>
            <a href="profile.html" class="block text-gray-700 hover:text-indigo-600">Profile</a>
            <a href="logout.html" class="block text-gray-700 hover:text-indigo-600">Logout</a>
        </div>
    </nav>

    <!-- Content -->
    <main class="p-6 sm:pl-64 pl-20">
        <h1 class="text-2xl font-bold text-gray-800">Welcome to Dashboard</h1>
        <p class="mt-2 text-gray-600">Berikut daftar materi.</p>
    </main>

</body>

</html>
