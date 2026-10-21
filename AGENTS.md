# AGENTS.md

Panduan untuk agen otomatis yang bekerja di repo ini.

## Aturan wajib

- Jangan membuka atau menyalin `.env`. Gunakan `.env.example` sebagai referensi.
- Jangan commit `.env`, kredensial, token, atau dump database.
- Jangan menambah build step frontend. Aset dimuat langsung dari `public/`.
- Jangan mengubah perilaku akses tanpa memperbarui `TugasPolicy` dan test.
- Jalankan test sebelum commit: `php artisan test`.
- Format file yang diubah: `vendor/bin/pint --dirty`.
- Setelah mengubah config atau route, jalankan `php artisan optimize:clear`.

## Arsitektur

- `routes/web.php`: halaman Blade saja.
- `routes/api.php`: logika data, diautentikasi `auth:sanctum,web`.
- Controller API ada di `app/Http/Controllers/Api/`.
- Logika bersama ada di `app/Services/` (`TugasService`, `StatisticsService`).
- Validasi upload wajib memakai whitelist `mimes`; jangan pernah memakai `image`
  saja atau membiarkan ekstensi bebas.
- Perubahan status tugas (approve/reject/upload) harus lewat `TugasService` dan
  tetap memakai transaksi + `lockForUpdate`.

## Gaya kode

- Bahasa komentar dan pesan UI: Indonesia, singkat.
- Ikuti gaya file sekitarnya; jangan menambah komentar dekoratif.
- Respons API: `{ success, message?, data?, meta? }`.

## Perintah

```bash
php artisan test
php artisan test --filter=TaskTest
vendor/bin/pint --dirty
php artisan optimize:clear
```
