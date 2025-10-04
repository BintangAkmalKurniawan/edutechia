<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Creator Profile - Edutechia</title>
    @vite('resources/css/app.css')
</head>

<body class="m-0 font-sans bg-gradient-to-br from-[#103b61] to-[#000b0c] text-white min-h-screen flex flex-col">
    <div class="container ml-20 flex-1 px-5 py-10 flex flex-col justify-start items-center text-center">
        <h1 class="mb-1 text-3xl font-bold"><i>Edutechia Team</i></h1>
        <p>
            <a href="mailto:eduniverse.id@gmail.com" class="text-[#ffdd57]">edutechia.id@gmail.com</a>
        </p>
        <h3 class="font-normal mb-8">Teknologi Pendidikan – Universitas Pendidikan Indonesia</h3>

        <!-- isi -->
        <ul class="flex flex-wrap justify-center gap-5 list-none p-0 m-0 mb-8 mx-32">
            <li
                class="bg-white/10 rounded-xl p-5 w-64 text-center transition duration-300 break-words hover:bg-white/20 hover:-translate-y-1">
                <img src="images/creator/agil.jpg" alt="Gilbran Aldebaran"
                    class="w-full h-72 object-cover rounded-lg mb-2" />
                <p>Gilbran Aldebaran</p>
                <p class="text-sm">
                    gilbranshauma@gmail.com <br />
                    2300433
                </p>
            </li>
            <li
                class="bg-white/10 rounded-xl p-5 w-64 text-center transition duration-300 break-words hover:bg-white/20 hover:-translate-y-1">
                <img src="images/creator/elma.jpg" alt="Elma Mukhsinah"
                    class="w-full h-72 object-cover rounded-lg mb-2" />
                <p>Elma Mukhsinah</p>
                <p class="text-sm">
                    elmamukhsinah0805@gmail.com <br />
                    2310711
                </p>
            </li>
            <li
                class="bg-white/10 rounded-xl p-5 w-64 text-center transition duration-300 break-words hover:bg-white/20 hover:-translate-y-1">
                <img src="images/creator/yasmin.jpg" alt="Elma Mukhsinah"
                    class="w-full h-72 object-cover rounded-lg mb-2" />
                <p>Yasmin Hadiyya Fatin Hana
                </p>
                <p class="text-sm">
                    yasminhadiyya@gmail.com
                    <br />
                    2300730
                </p>
            </li>
            <li
                class="bg-white/10 rounded-xl p-5 w-64 text-center transition duration-300 break-words hover:bg-white/20 hover:-translate-y-1">
                <img src="images/creator/illiyyine.jpg" alt="Elma Mukhsinah"
                    class="w-full h-72 object-cover rounded-lg mb-2" />
                <p>Illiyyine Zulkarnaen
                </p>
                <p class="text-sm">
                    illiyyinezill@gmail.com
                    <br />
                    2304179
                </p>
            </li>
            <li
                class="bg-white/10 rounded-xl p-5 w-64 text-center transition duration-300 break-words hover:bg-white/20 hover:-translate-y-1">
                <img src="images/creator/rafa.jpg" alt="Elma Mukhsinah"
                    class="w-full h-72 object-cover rounded-lg mb-2" />
                <p>Rafa Anindita Azzahra</p>
                <p class="text-sm">
                    rafaaninditazazhraa@gmail.com
                    <br />
                    2300188
                </p>
            </li>
            <li
                class="bg-white/10 rounded-xl p-5 w-64 text-center transition duration-300 break-words hover:bg-white/20 hover:-translate-y-1">
                <img src="images/creator/gisca.jpg" alt="Elma Mukhsinah"
                    class="w-full h-72 object-cover rounded-lg mb-2" />
                <p>Gisca Anugrah Yuhendra
                </p>
                <p class="text-sm">
                    giscaanugrah@gmail.com
                    <br />
                    2305235
                </p>
            </li>
        </ul>

        <a href="/"
            class="inline-block px-6 py-3 bg-[#ffdd57] text-black rounded-lg font-bold no-underline transition duration-300 hover:bg-white hover:scale-105">Kembali</a>
    </div>

    <footer class="text-center p-4 text-sm bg-white/5">
        &copy; {{ date('Y') }} Edutechia. All Rights Reserved.
    </footer>
</body>

</html>
