# Hakai Pro - Manajemen Tugas

Aplikasi web manajemen tugas karyawan. Admin membuat tugas untuk karyawan,
karyawan mengunggah hasil kerja, lalu admin menyetujui atau menolak dengan
catatan. Proyek ini mencakup dashboard statistik, komentar bertingkat, notifikasi,
trash, audit log, ekspor PDF/Excel, dan PWA.

- Repo: <https://github.com/HaedarWinter/Hakai-Website>
- Laporan proyek: `LAPORAN_PKL.md`
- Panduan agen otomatis: `AGENTS.md`

## Daftar Isi

1. [Fitur](#fitur)
2. [Peran dan Hak Akses](#peran-dan-hak-akses)
3. [Alur Status Tugas](#alur-status-tugas)
4. [Teknologi](#teknologi)
5. [Struktur Proyek](#struktur-proyek)
6. [Persyaratan](#persyaratan)
7. [Instalasi](#instalasi)
8. [Konfigurasi](#konfigurasi)
9. [Akun Demo](#akun-demo)
10. [Perintah](#perintah)
11. [API](#api)
12. [Keamanan](#keamanan)
13. [Testing](#testing)
14. [Deployment](#deployment)
15. [Batasan yang Diketahui](#batasan-yang-diketahui)

## Fitur

### Publik dan Autentikasi

- Landing page, login, logout.
- Login memakai email **atau** nama pengguna.
- Lupa password lewat email atau tautan WhatsApp (disalin manual oleh pengguna).
- Reset password dengan token sekali pakai.
- Throttle login 5 percobaan per identitas + IP, lockout 5 menit.
- Reset password dibatasi 5 permintaan per menit per IP dan selalu memberi pesan
  generik agar akun tidak bisa ditebak.

### Dashboard

- Kartu statistik: total tugas, total user (admin), selesai, pending.
- Daftar deadline 3 hari ke depan dan tugas terlambat 7 hari terakhir.
- Ring completion rate dan rincian status.
- Chart status tugas dan trend 7 hari.
- Aktivitas terbaru dan 5 tugas terbaru.
- Scope `global` dan `personal` untuk admin. Karyawan selalu melihat datanya sendiri.
- Tombol refresh manual; header memantau notifikasi/pesan tiap 60 detik.
- Pencarian global: tugas (admin: semua, karyawan: miliknya) dan direktori user
  (admin saja).

### Tugas - Admin

- Buat, ubah, hapus (soft delete), dan pulihkan tugas.
- Tugas hanya bisa diberikan ke user berjabatan `Karyawan`.
- Unggah file instruksi, prioritas `low` / `medium` / `high`.
- Setujui atau tolak tugas yang berstatus `submitted`, dengan catatan penolakan.
- Filter status, karyawan, dan prioritas.
- Mode seleksi dan hapus massal.
- Ekspor Excel dan PDF sesuai cakupan data.
- Badge jumlah tugas menunggu persetujuan.
- Daftar admin hanya menampilkan tugas yang ia buat sendiri.

### Tugas - Karyawan

- Melihat tugas yang diberikan kepadanya.
- Mengunggah file hasil kerja; unggah ulang setelah ditolak.
- Melihat detail tugas, file instruksi, pratinjau gambar/PDF, dan catatan penolakan.
- Menghapus tugas miliknya ke trash dan melihat isi trash miliknya.
- Ekspor Excel dan PDF miliknya.
- Statistik ringkas di halaman tugas.

### Komentar

- Komentar per tugas dengan balasan bertingkat maksimal 2 level.
- Penulis dapat menghapus komentarnya sendiri.
- Akses komentar mengikuti `TugasPolicy` (hanya pihak yang terlibat).
- Notifikasi pesan dari komentar orang lain pada 7 hari terakhir.

### Manajemen User - Admin

- CRUD user dengan jabatan `Admin` atau `Karyawan`.
- Pencarian, filter, dan ekspor Excel/PDF.
- Super Admin dapat memberikan atau mencabut status Super Admin milik admin lain.
- Super Admin tidak dapat mencabut statusnya sendiri.
- Admin tidak dapat menghapus akunnya sendiri.
- Menghapus admin lain hanya boleh dilakukan Super Admin.
- Email user yang dihapus diganti menjadi `email.deleted.{id}` agar bisa dipakai lagi.
- Admin dapat membuat tautan reset password untuk user.

### Trash dan Audit Log - Admin

- Trash tugas: lihat, pulihkan, atau hapus permanen.
- Perintah terjadwal menghapus permanen tugas di trash setelah 30 hari.
- Audit log mencatat `created`, `updated`, `trashed`, `restored`, `deleted`,
  `approved`, `rejected`, dan `submitted`, lengkap dengan pelaku, IP, dan user agent.
- Audit log bisa difilter berdasarkan aksi dan user, 20 entri per halaman.

### Profil dan Pengaturan

- Semua user dapat melihat profil sendiri.
- Admin dapat melihat semua profil; karyawan hanya profil karyawan lain.
- Edit profil sendiri: nama, email, No. HP, alamat, bio, tanggal bergabung, foto.
- Ganti password dengan verifikasi password lama; sesi dan token lain dicabut.
- Foto profil maksimal 2 MB (`jpg`, `jpeg`, `png`, `webp`).

### Notifikasi

- Notifikasi dalam aplikasi diturunkan dari status tugas:
  - Admin: tugas `submitted` yang ia buat.
  - Karyawan: tugas `pending` atau `rejected` dalam 3 hari terakhir.
- Pesan: komentar baru pada tugas atau balasan ke komentar sendiri.
- Tanda sudah dibaca disimpan per user (`notifications_read_at`, `messages_read_at`).
- Email tugas (queue): tugas baru, submit, disetujui, ditolak.
- Tombol suara notifikasi dapat dimatikan, tersimpan di `localStorage`.

### PWA dan Aset

- `public/manifest.json`, ikon, dan `public/sw.js` (cache shell, network-first HTML).
- Tidak ada build step. Semua CSS/JS dimuat langsung dari `public/`.
- DataTables, SweetAlert2, Chart.js, dan SB Admin 2 tersedia lokal.

## Peran dan Hak Akses

| Kemampuan | Karyawan | Admin | Super Admin |
|---|---|---|---|
| Lihat tugas sendiri | Ya | - | - |
| Lihat tugas yang ia buat | - | Ya | Ya |
| Buat / ubah / hapus tugas | - | Ya, miliknya | Ya, miliknya |
| Setujui / tolak tugas | - | Ya, miliknya | Ya, miliknya |
| Unggah hasil kerja | Ya, miliknya | - | - |
| Kelola user | - | Ya | Ya |
| Hapus akun admin lain | - | Tidak | Ya |
| Beri / cabut Super Admin | - | Tidak | Ya |
| Lihat trash sendiri | Ya | - | - |
| Pulihkan/hapus permanen trash, audit log | - | Ya | Ya |
| Ekspor user | - | Ya | Ya |
| Lihat profil admin lain | Tidak | Ya | Ya |

Catatan: dashboard admin memakai scope `global` yang menghitung seluruh tugas,
sedangkan daftar tugas admin hanya menampilkan tugas yang dibuat olehnya.
Super Admin adalah flag (`is_super_admin`) pada user berjabatan `Admin`.

## Alur Status Tugas

```text
Admin membuat tugas
        |
        v
     pending
        |
        | Karyawan upload hasil
        v
    submitted
     /       \
approve      reject (+ catatan)
    |           |
    v           v
 approved     rejected
                |
                | Karyawan upload ulang
                v
            submitted
```

- Transisi `approve`, `reject`, dan `upload` berjalan dalam transaksi database
  dengan `lockForUpdate()`.
- Aksi pada status yang tidak sesuai ditolak dengan HTTP 409.
- File hasil lama dihapus saat karyawan mengunggah ulang.

## Teknologi

| Lapisan | Teknologi |
|---|---|
| Bahasa | PHP ^8.2 |
| Framework | Laravel ^12.0 |
| Database | PostgreSQL (produksi), SQLite in-memory (test) |
| Autentikasi | Session web + Laravel Sanctum ^4.2 |
| Tampilan | Blade, SB Admin 2, jQuery, Bootstrap 4 |
| Tabel | DataTables (client-side) |
| Grafik | Chart.js |
| Dialog | SweetAlert2 (lokal) |
| PDF | barryvdh/laravel-dompdf ^3.1 |
| Excel | maatwebsite/excel ^3.1 |
| Format kode | Laravel Pint |
| Test | PHPUnit ^11.5 |

## Struktur Proyek

```text
app/
  Console/Commands/       CheckSanctum, CleanupTugas, PruneTrashedTasks
  Exports/                TugasExport, UserExport
  Http/Controllers/       halaman web (Auth, Dashboard, Tugas, Profile, User)
  Http/Controllers/Api/   seluruh operasi data JSON
  Http/Middleware/        admin, auth.check, login.throttle, no.cache, last seen
  Models/                 User, Tugas, TaskComment, ActivityLog
  Notifications/          notifikasi email tugas
  Policies/               TugasPolicy
  Services/               TugasService, StatisticsService
config/                   konfigurasi Laravel, cors, sanctum, session
database/migrations/      skema database
database/seeders/         data demo
public/
  js/page/                skrip halaman (admin, karyawan, dashboard, shared)
  css/                    enhancements, dark-mode, sidebar
  sw.js, manifest.json    PWA
resources/views/          Blade (admin, karyawan, auth, profile, errors, emails)
routes/web.php            navigasi halaman
routes/api.php            endpoint JSON
tests/Feature/            test autentikasi, tugas, pencarian, reset password
nginx-optimized.conf      contoh konfigurasi Nginx produksi
php.ini                   batas upload contoh (50 MB)
```

## Persyaratan

- PHP 8.2 atau lebih baru.
- Ekstensi PHP: `pdo_pgsql` (atau `pdo_sqlite` untuk test), `mbstring`,
  `openssl`, `fileinfo`, `zip`, `gd`, `xml`, `curl`.
- Composer 2.
- PostgreSQL 13+.
- Tidak perlu Node.js atau npm.

## Instalasi

```bash
git clone https://github.com/HaedarWinter/Hakai-Website.git
cd Hakai-Website

composer install
cp .env.example .env
php artisan key:generate

# sesuaikan DB_* di .env lebih dulu
php artisan migrate
php artisan db:seed          # opsional, data demo
php artisan storage:link
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Untuk mengulang dari nol:

```bash
php artisan migrate:fresh --seed
```

## Konfigurasi

Variabel penting di `.env` (lihat `.env.example`):

| Variabel | Default | Keterangan |
|---|---|---|
| `APP_ENV` | `local` | Gunakan `production` di server |
| `APP_DEBUG` | `true` | Wajib `false` di produksi |
| `APP_URL` | `http://localhost` | Dipakai tautan reset dan CORS |
| `DB_CONNECTION` | `pgsql` | Driver database |
| `SESSION_DRIVER` | `database` | Sesi disimpan di tabel `sessions` |
| `SESSION_ENCRYPT` | `true` | Payload sesi terenkripsi |
| `SANCTUM_EXPIRATION` | `480` | Umur token API dalam menit |
| `SANCTUM_STATEFUL_DOMAINS` | `localhost,127.0.0.1` | Domain yang memakai cookie sesi |
| `CORS_ALLOWED_ORIGINS` | `APP_URL` | Origin tambahan, pisahkan dengan koma |
| `QUEUE_CONNECTION` | `database` | Notifikasi email memakai queue |
| `MAIL_*` | `log` | Konfigurasi pengiriman email |
| `FILESYSTEM_DISK` | `local` | Disk unggahan memakai disk `public` |

Batas unggah:

- File tugas: maksimal 50 MB, tipe `pdf, doc, docx, xls, xlsx, ppt, pptx, txt,
  zip, jpg, jpeg, png`.
- Foto profil: maksimal 2 MB, tipe `jpg, jpeg, png, webp`.
- File disimpan di `storage/app/public/tugas_files` dan
  `storage/app/public/profile-photos`.
- `php.ini` contoh menyetel `upload_max_filesize=50M` dan `post_max_size=55M`.

## Akun Demo

Dibuat oleh `php artisan db:seed`:

| Email | Password | Peran |
|---|---|---|
| `superadmin@hakai.com` | `superadmin123` | Super Admin |
| `andita@hakai.com` | `anditacantik123` | Super Admin |
| `admin@hakai.com` | `admin123` | Admin |
| `budi.santoso@hakai.com` | `karyawan123` | Karyawan |
| `siti.nurhaliza@hakai.com` | `karyawan123` | Karyawan |
| `ahmad.fauzi@hakai.com` | `karyawan123` | Karyawan |
| `dewi.lestari@hakai.com` | `karyawan123` | Karyawan |
| `rizki.pratama@hakai.com` | `karyawan123` | Karyawan |

Ganti semua password sebelum server dibuka untuk umum. Seeder memakai `create`,
jadi jalankan `migrate:fresh --seed` bila perlu data bersih.

## Perintah

```bash
php artisan serve                     # server lokal
php artisan test                      # seluruh test
php artisan test --filter=TaskTest    # satu kelas test
vendor/bin/pint --dirty               # format file yang berubah
php artisan optimize                  # cache config, route, view (produksi)
php artisan optimize:clear            # bersihkan cache

php artisan tugas:prune-trash         # hapus permanen trash > 30 hari
php artisan tugas:prune-trash 7       # ubah ambang hari
php artisan tugas:cleanup             # hapus tugas tanpa user
php artisan check:sanctum             # cek instalasi Sanctum
```

Scheduler (`routes/console.php`) menjalankan `tugas:prune-trash` setiap hari.

## API

Semua endpoint ada di prefix `/api`, mengembalikan JSON dengan bentuk umum:

```json
{ "success": true, "message": "opsional", "data": {}, "meta": {} }
```

Autentikasi memakai `auth:sanctum,web`. Request dari panel memakai cookie sesi dan
wajib menyertakan `X-CSRF-TOKEN` untuk metode `POST`, `PUT`, `PATCH`, `DELETE`.

Daftar endpoint:

| Metode | Endpoint | Akses | Keterangan |
|---|---|---|---|
| POST | `/api/login` | publik | Login JSON, mengembalikan token |
| POST | `/api/logout` | auth | Cabut seluruh token user |
| GET | `/api/global-search?query=` | auth | Cari tugas/user, minimal 2 karakter |
| GET | `/api/dashboard/stats` | auth | Statistik (`scope=global|personal`) |
| GET | `/api/dashboard/recent-tasks` | auth | 5 tugas terbaru |
| GET | `/api/dashboard/trend` | auth | Trend 7 hari |
| GET | `/api/dashboard/summary` | auth | Gabungan stats, recent, trend |
| POST | `/api/settings/profile` | auth | Ubah nama/email |
| POST | `/api/settings/photo` | auth | Unggah foto profil (multipart) |
| PUT | `/api/settings/password` | auth | Ganti password |
| GET | `/api/tugas` | auth | Daftar tugas, `per_page` 1-1000 |
| POST | `/api/tugas` | admin | Buat tugas (multipart opsional) |
| GET | `/api/tugas/karyawan` | auth | Pilihan karyawan untuk assignment |
| GET | `/api/tugas/trash/list` | auth | Daftar tugas di trash |
| GET | `/api/tugas/{id}` | auth | Detail tugas |
| PUT | `/api/tugas/{id}` | admin | Ubah tugas |
| DELETE | `/api/tugas/{id}` | auth | Soft delete ke trash |
| POST | `/api/tugas/{id}/upload` | karyawan | Unggah hasil kerja |
| POST | `/api/tugas/{id}/approve` | admin | Setujui tugas |
| POST | `/api/tugas/{id}/reject` | admin | Tolak tugas + `catatan` |
| POST | `/api/tugas/{id}/restore` | admin | Pulihkan dari trash |
| DELETE | `/api/tugas/{id}/force` | admin | Hapus permanen |
| GET | `/api/tugas/{id}/comments` | auth | Komentar + balasan |
| POST | `/api/tugas/{id}/comments` | auth | Tambah komentar/balasan |
| DELETE | `/api/tugas/comments/{id}` | auth | Hapus komentar sendiri |
| GET | `/api/notifications` | auth | Notifikasi tugas |
| POST | `/api/notifications/mark-read` | auth | Tandai notifikasi dibaca |
| GET | `/api/messages` | auth | Notifikasi komentar |
| POST | `/api/messages/mark-read` | auth | Tandai pesan dibaca |
| GET | `/api/users` | admin | Daftar user, `per_page` 1-1000 |
| POST | `/api/users` | admin | Buat user |
| GET | `/api/users/{id}` | admin | Detail user |
| PUT | `/api/users/{id}` | admin | Ubah user |
| DELETE | `/api/users/{id}` | admin | Hapus user |
| POST | `/api/users/{id}/toggle-super-admin` | super admin | Beri/cabut Super Admin |
| POST | `/api/users/{id}/generate-reset-token` | admin | Buat tautan reset password |
| GET | `/api/trash` | admin | Daftar semua item trash |
| POST | `/api/trash/{id}/restore` | admin | Pulihkan tugas |
| DELETE | `/api/trash/{id}` | admin | Hapus permanen |
| GET | `/api/audit-logs` | admin | Audit log, filter `action`, `user_id` |

Respons daftar menyertakan `meta`:

```json
{
  "current_page": 1,
  "per_page": 200,
  "total": 12,
  "last_page": 1,
  "has_more": false
}
```

### Halaman Web

| Path | Akses | Keterangan |
|---|---|---|
| `/` | publik | Landing page |
| `/login` | guest | Form login |
| `/forgot-password` | guest | Minta tautan reset |
| `/reset-password/{token}` | guest | Form reset |
| `/dashboard` | auth | Dashboard |
| `/tugas` | auth | Tugas admin atau karyawan |
| `/tugas/trash` | auth | Halaman trash tugas |
| `/tugas/{id}` | auth | Halaman detail tugas |
| `/tugas/export/excel`, `/tugas/export/pdf` | auth | Ekspor tugas |
| `/profile`, `/profile/{id}` | auth | Profil sendiri / user lain |
| `/user` | admin | Manajemen user |
| `/users/excel`, `/users/pdf` | admin | Ekspor user |
| `/trash`, `/audit-log` | admin | Trash dan audit log |
| `/settings` | auth | Redirect ke `/profile` |
| `/up` | publik | Health check Laravel |

## Keamanan

- Otentikasi dua jalur: sesi web dan Sanctum; token lama dicabut setiap login.
- `statefulApi()` mengaktifkan CSRF untuk API yang memakai cookie sesi.
- Kebijakan akses terpusat di `TugasPolicy` (lihat, ubah, hapus, unggah,
  setujui, tolak).
- Validasi unggahan memakai whitelist `mimes`; `image` saja atau ekstensi bebas
  tidak dipakai.
- Nginx contoh memblokir eksekusi PHP di bawah `/storage/`.
- Reset password: pesan generik, rate limit, token di-hash, dan seluruh sesi/token
  dicabut setelah reset.
- Ganti password mencabut token Sanctum dan sesi lain
  (`Auth::logoutOtherDevices`).
- Sesi dienkripsi (`SESSION_ENCRYPT=true`).
- CORS hanya mengizinkan `APP_URL` dan origin tambahan yang didaftarkan.
- Middleware `admin` memeriksa jabatan dan mendukung JSON maupun redirect.
- `NoCache` dipasang pada seluruh route API.
- `.env`, dump database, dan kredensial tidak boleh di-commit.

## Testing

Test memakai SQLite `:memory:`, cache/session array, dan mail array
(lihat `phpunit.xml`), jadi tidak menyentuh database asli.

```bash
php artisan test
php artisan test --filter=AuthTest
```

Cakupan saat ini:

- `AuthTest` - login web/API, throttle, key throttle.
- `TaskTest` - pembuatan, persetujuan, penolakan, unggah file, isolasi karyawan.
- `GlobalSearchTest` - visibilitas tugas dan direktori user.
- `PasswordResetTest` - pesan generik dan pencabutan token.

## Deployment

Langkah minimum di server:

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Checklist produksi:

1. `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-anda`.
2. Document root web server diarahkan ke `public/`, bukan root proyek.
3. HTTPS aktif dan cookie sesi `Secure` bila memungkinkan.
4. Jalankan worker queue bila `QUEUE_CONNECTION` bukan `sync`:
   `php artisan queue:work --tries=3`.
5. Tambahkan cron scheduler:
   `* * * * * cd /path/proyek && php artisan schedule:run >> /dev/null 2>&1`.
6. Gunakan `nginx-optimized.conf` sebagai acuan; sesuaikan socket PHP-FPM.
7. Pastikan `storage/` dan `bootstrap/cache/` dapat ditulis web server.
8. Ganti password akun demo atau jangan jalankan seeder di produksi.

Opsi gratis untuk demo portofolio:

- **alwaysdata free**: PHP, PostgreSQL, SSH, dan subdomain
  `username.alwaysdata.net`. Cocok untuk proyek non-komersial.
- **Render free**: perlu Docker; service tidur setelah 15 menit dan filesystem
  hilang saat restart, sehingga unggahan tidak persisten.
- **Cloudflare Tunnel / ngrok**: cepat untuk demo, tetapi hanya hidup selama
  komputer lokal menyala.

## Batasan yang Diketahui

- UI daftar tugas meminta `per_page=1000`. Batas server 1000 baris; paginasi
  server-side belum dipakai di UI.
- Filter prioritas berjalan di sisi klien.
- Reset password WhatsApp hanya membuat tautan dan membuka `wa.me`; tidak ada
  integrasi API WhatsApp.
- Notifikasi dalam aplikasi memakai polling 60 detik, bukan WebSocket.
- Penyimpanan file memakai disk lokal; belum ada object storage.
- Kolom `is_tugas` dan tabel `settings` adalah sisa skema lama dan tidak dipakai
  oleh fitur aktif.
- Belum ada verifikasi email.
