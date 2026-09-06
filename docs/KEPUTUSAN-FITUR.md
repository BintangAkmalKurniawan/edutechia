# Keputusan fitur yang perlu dikonfirmasi

Fondasi LMS dan alur inti sudah berjalan. Beberapa fitur berikut ditunda karena masing-masing mengubah aturan bisnis, struktur data, atau beban operasional secara signifikan.

## Prioritas berikutnya

1. **Tugas dan penilaian manual**
    - Tentukan tipe pengumpulan: teks, berkas, tautan, atau kombinasi.
    - Tentukan apakah boleh terlambat, revisi, rubrik, dan siapa yang dapat mengubah nilai.

2. **Model pendaftaran kelas**
    - Saat ini tersedia kelas terbuka dan kelas dengan kode.
    - Opsi tambahan: persetujuan guru, undangan per siswa, sinkronisasi rombel, atau impor CSV.

3. **Manajemen institusi dan multi-sekolah**
    - Saat ini satu instalasi dianggap sebagai satu ekosistem Edutechia.
    - Perlu diputuskan apakah admin hanya satu tingkat atau ada super-admin, admin sekolah, jurusan, dan kelas/rombel.

4. **Kehadiran dan pembelajaran langsung**
    - Pilihan: presensi manual, QR, jadwal pertemuan, integrasi Google Meet/Zoom, atau tanpa modul sinkron.

5. **Sertifikat**
    - Tentukan syarat kelulusan, desain, nomor verifikasi, masa berlaku, dan penandatangan.

## Keputusan operasional

- Batas ukuran serta retensi video/berkas; penyimpanan lokal atau object storage (S3-compatible).
- Apakah guru boleh menghapus kelas yang sudah memiliki siswa, atau hanya mengarsipkan.
- Kebijakan moderasi forum, pelaporan konten, dan audit log admin.
- Notifikasi tambahan selain email: pengumuman dalam aplikasi, WhatsApp, atau push notification.
- Bank soal, pengacakan, batas percobaan, jadwal buka-tutup, serta esai yang dinilai guru.
- Analitik yang dibutuhkan: durasi belajar, aktivitas terakhir, ekspor nilai, atau laporan per rombel.
- Kebijakan privasi, masa simpan data, persetujuan pengguna, dan prosedur penghapusan data.
- Branding institusi, domain, bahasa tambahan, serta kebutuhan aksesibilitas khusus.

## Rekomendasi urutan keputusan

Mulai dari tugas/penilaian, model rombel, dan aturan arsip kelas. Ketiganya paling memengaruhi skema database dan navigasi setiap role. Setelah itu tentukan laporan serta sertifikat berdasarkan data yang benar-benar dikumpulkan.
