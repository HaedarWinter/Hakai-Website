# Hakai - Manajemen Tugas

Aplikasi manajemen tugas karyawan berbasis Laravel 12. Admin membuat tugas untuk
karyawan, karyawan mengunggah hasil kerja, admin menyetujui atau menolak dengan
catatan. Tersedia dashboard statistik, komentar per tugas, notifikasi, trash,
audit log, serta ekspor PDF/Excel.

## Stack

- PHP 8.2+, Laravel 12, MySQL (SQLite untuk test)
- Blade + SB Admin 2, jQuery, DataTables, SweetAlert2, Chart.js
- Laravel Sanctum (sesi web + bearer token)
- DomPDF dan Maatwebsite Excel untuk laporan

Tidak ada build frontend. Semua aset ada di `public/` dan dimuat langsung.

## Struktur

- `routes/web.php` - hanya halaman (view), tanpa logika bisnis
- `routes/api.php` - seluruh operasi data (JSON)
- `app/Http/Controllers/Api/` - controller API
- `app/Services/` - logika tugas dan statistik
- `app/Policies/TugasPolicy.php` - aturan akses per tugas
- `public/js/page/`, `public/css/` - aset halaman
- `public/js/page/tugas/shared.js` - tabel tugas, detail, komentar, pratinjau
- `public/sw.js` - service worker PWA

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

Isi kredensial database di `.env`. Jangan pernah commit `.env`.

## Environment penting

| Variabel | Fungsi |
|---|---|
| `APP_URL` | URL aplikasi; dipakai CORS dan tautan reset password |
| `DB_*` | Koneksi database |
| `SESSION_ENCRYPT` | Default `true`; enkripsi payload sesi |
| `SANCTUM_EXPIRATION` | Umur token API dalam menit, default `480` |
| `CORS_ALLOWED_ORIGINS` | Daftar origin tambahan dipisah koma |
| `QUEUE_CONNECTION` | Notifikasi email memakai queue; `sync` cukup untuk dev |

## Perintah

```bash
php artisan test                 # seluruh test
php artisan test --filter=TaskTest
vendor/bin/pint --dirty          # format file yang berubah
php artisan tugas:prune-trash    # hapus permanen trash > 30 hari (terjadwal harian)
php artisan optimize:clear       # setelah mengubah config/route
```

## Aturan keamanan yang berlaku

- Upload hanya menerima dokumen/gambar tertentu (lihat validasi `mimes` di
  `TugasApiController` dan `SettingsApiController`). SVG dan PHP ditolak.
- File unggahan disimpan di disk `public`, tetapi Nginx memblokir eksekusi PHP
  di `/storage/`.
- Semua endpoint komentar dan detail tugas memakai `TugasPolicy`.
- Reset password selalu memberi pesan generik dan dibatasi 5 permintaan/menit/IP.
- Login dibatasi 5 percobaan per kombinasi identitas + IP.
- Mengganti atau mereset password mencabut token dan sesi lain.
- API yang memakai cookie sesi tunduk pada CSRF (`statefulApi()`).

## Catatan operasional

- Endpoint daftar memakai `per_page` dengan batas 1000. Halaman admin meminta
  1000 baris; bila data melewati batas itu, UI perlu dipindah ke paginasi server.
- Tabel `notifications` sudah dihapus lewat migration; semua notifikasi memakai
  kanal `mail`.
- Logika bersama halaman tugas (tabel, detail, komentar, pratinjau file) ada di
  `public/js/page/tugas/shared.js` dan dipakai halaman admin serta karyawan.
