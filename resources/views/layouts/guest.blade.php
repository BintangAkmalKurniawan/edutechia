<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Edutechia') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0d1b2a] to-[#000814] font-[Poppins] text-white">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md rounded-2xl shadow-lg p-8">
        {{ $slot }}
    </div>
</body>

</html>
