# Edutechia LMS

Edutechia adalah LMS berbasis Laravel 12 dan Blade yang mengubah website statis `D:\edutechia-upi` menjadi aplikasi dinamis. Identitas visual gelap–kuning, katalog materi, video, kuis, diskusi, dan profil kreator tetap dipertahankan; data serta otorisasinya sekarang dikelola melalui PostgreSQL.

## Fitur yang sudah tersedia

- Tampilan responsif 100% Blade + Tailwind CSS + Alpine.js ringan.
- Tiga role: `admin`, `teacher` (guru), dan `student` (siswa).
- Registrasi publik hanya untuk siswa.
- Guru dibuat oleh admin; status setiap akun dapat diaktifkan/dinonaktifkan.
- OTP email hanya wajib untuk menyelesaikan registrasi siswa.
- Login akun terverifikasi berlangsung langsung tanpa OTP; login akun yang registrasinya belum terverifikasi akan mengirim OTP baru.
- OTP di-hash, berlaku 10 menit, satu kali pakai, maksimal lima percobaan, dan memiliki rate limit pengiriman ulang.
- Katalog kelas publik, kelas terbuka atau berkode, materi berurutan, video/berkas, progres siswa, forum diskusi internal, serta dukungan Padlet.
- Kuis pilihan ganda internal dengan penilaian otomatis dan riwayat percobaan, plus kompatibilitas Wordwall.
- Dashboard khusus admin, guru, dan siswa.
- Reset password melalui email SMTP.

## Kebutuhan sistem

- PHP 8.2+ dengan ekstensi `pdo_pgsql`, `mbstring`, dan `openssl`.
- PostgreSQL.
- Composer 2.
- Node.js dan npm.

## Instalasi

```powershell
composer install
npm ci
Copy-Item .env.example .env
php artisan key:generate
```

Isi koneksi PostgreSQL di `.env`:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=edutechia
DB_USERNAME=postgres
DB_PASSWORD=kata_sandi_postgres
```

Database `edutechia` harus sudah dibuat dan user PostgreSQL harus memiliki hak untuk membuat/mengubah tabel. Kemudian jalankan:

```powershell
php artisan migrate
php artisan storage:link
npm run build
php artisan edutechia:create-admin
php artisan serve
```

Perintah `edutechia:create-admin` meminta nama, email, dan password secara interaktif. Akun administrator yang dibuat melalui perintah ini langsung berstatus terverifikasi.

Untuk data demo lokal:

```powershell
php artisan migrate:fresh --seed
```

Seeder demo membuat akun berikut. Gunakan hanya pada lingkungan lokal dengan `MAIL_MAILER=log`:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@edutechia.test` | `Admin123!` |
| Guru | `guru@edutechia.test` | `Guru123!` |
| Siswa | `siswa@edutechia.test` | `Siswa123!` |

Kode OTP registrasi pada mode `log` dapat dilihat di `storage/logs/laravel.log`.

## Konfigurasi SMTP

Gunakan kredensial SMTP dari penyedia email Anda:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.penyedia-email.com
MAIL_PORT=587
MAIL_USERNAME=akun-smtp
MAIL_PASSWORD=password-smtp
MAIL_FROM_ADDRESS=noreply@domain-anda.id
MAIL_FROM_NAME="${APP_NAME}"
AUTH_OTP_EXPIRATION=10
```

Setelah mengubah `.env`, bersihkan cache konfigurasi:

```powershell
php artisan optimize:clear
```

Lakukan uji kirim pada staging sebelum produksi. Jangan menyimpan password SMTP atau kredensial database ke Git.

## Matriks akses

| Kemampuan | Admin | Guru | Siswa |
|---|:---:|:---:|:---:|
| Kelola pengguna dan tambah guru | ✓ | — | — |
| Buat/ubah/terbitkan kelas | ✓ | ✓ (milik sendiri) | — |
| Buat materi dan kuis | ✓ | ✓ (milik sendiri) | — |
| Daftar ke kelas | — | — | ✓ |
| Baca materi, catat progres, kerjakan kuis | Pratinjau | Pratinjau | ✓ |
| Forum diskusi | Moderasi | Moderasi kelas sendiri | Berpartisipasi |

## Pengujian

Test memakai SQLite in-memory agar cepat dan tidak menyentuh database pengembangan, sedangkan konfigurasi runtime tetap PostgreSQL.

```powershell
php artisan test
npm run build
npm audit
```

## Keputusan produk yang ditunda

Fitur yang perlu keputusan sebelum diimplementasikan dicatat di [docs/KEPUTUSAN-FITUR.md](docs/KEPUTUSAN-FITUR.md). Daftar itu sengaja tidak diasumsikan agar alur admin, guru, dan siswa tidak berkembang ke arah yang berbeda dari kebutuhan institusi.
