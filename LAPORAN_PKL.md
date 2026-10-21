# CATATAN LAPORAN PRAKTIK KERJA LAPANGAN (PKL)
## Sistem Manajemen Tugas Hakai - PT Zahir Accounting - Divisi Software Developer

---

## 1. ALASAN KENAPA PKL

Penulis sebagai siswa SMK Yadika Soreang melaksanakan Praktik Kerja Lapangan (PKL) di PT. Zahir Accounting pada Divisi *Software Developer*. Tempat PKL ini dipilih berdasarkan arahan guru pembimbing, namun bagi penulis hal tersebut merupakan kesempatan berharga untuk memperoleh pengalaman nyata di dunia kerja, serta memahami secara langsung bagaimana sebuah perusahaan teknologi mengelola sumber daya dan proyek pengembangan perangkat lunak.

---

## 2. ALASAN KENAPA MASUK ZAHIR

PT Zahir Accounting merupakan perusahaan yang bergerak di bidang teknologi akuntansi dan manajemen bisnis. Pemilihan PT Zahir Accounting sebagai tempat PKL didasari oleh beberapa pertimbangan:

1. **Reputasi Perusahaan**: PT Zahir Accounting merupakan perusahaan teknologi yang telah dikenal luas di Indonesia dalam bidang software akuntansi dan manajemen bisnis.

2. **Kesempatan Belajar Teknologi Modern**: Divisi Sysforeach di PT Zahir Accounting menggunakan teknologi *fullstack* modern yang menjadi standar industri saat ini, memberikan kesempatan bagi penulis untuk belajar dan menerapkan *best practices* dalam pengembangan aplikasi web.

3. **Lingkungan Profesional**: Bekerja di lingkungan profesional membantu penulis memahami kultur kerja di industri teknologi, termasuk kolaborasi tim, *project management*, dan standar kualitas kode.

4. **Mentor Berpengalaman**: Keberadaan guru pembimbing dan mentor di perusahaan yang berpengalaman dalam pengembangan *software* membantu penulis untuk berkembang lebih cepat.

---

## 3. ALASAN KENAPA BUAT APLIKASI

### 3.1 Latar Belakang Pembuatan Aplikasi

Selama kegiatan PKL, penulis mendapat kesempatan untuk pembuatan aplikasi pengelolaan tugas/ manajemen penugasan berbasis *website*. *Website* ini dirancangkan untuk membantu pihak sekolah maupun lembaga pelatihan dalam mengatur penugasan secara lebih efektif dan efisien.

### 3.2 Permasalahan yang Dihadapi

Sistem penugasan yang masih dilakukan secara manual seringkali menimbulkan kendala, seperti:

- **Bentroknya Jadwal**: Kesulitan dalam mengkoordinasikan jadwal antar tim atau individu
- **Pembagian Mendadak**: Informasi tugas yang terlambat sampai ke pihak terkait
- **Distribusi Informasi**: Kesulitan dalam mendistribusikan informasi jadwal kepada Instruktur dan Peserta pelatihan
- **Pelacakan Progress**: Sulit untuk memantau kemajuan penyelesaian tugas secara real-time
- **Dokumentasi**: Minimnya dokumentasi dan riwayat penugasan yang terstruktur

### 3.3 Solusi yang Ditawarkan

Oleh karena itu, dibutuhkan sebuah aplikasi berbasis web yang dapat mengelola jadwal kelas dan tugas secara terstruktur, cepat, dan mudah diakses.

**Aplikasi Hakai Task Management System** hadir sebagai solusi dengan fitur-fitur:

1. **Manajemen Tugas Terpusat**: Semua tugas dapat dikelola dalam satu platform yang mudah diakses
2. **Notifikasi Real-time**: Pemberitahuan otomatis untuk tugas baru, deadline, dan update
3. **Role-Based Access**: Sistem berbasis peran (Admin, Supervisor, Karyawan) untuk keamanan dan efisiensi
4. **Dashboard Analytics**: Visualisasi data untuk memantau produktivitas dan progress
5. **Collaboration Tools**: Fitur komentar dan diskusi untuk kolaborasi tim
6. **Multi-Status Tracking**: Pelacakan status tugas dari "Todo", "In Progress", hingga "Done"

---

## 4. TUJUAN DAN MANFAAT

### 4.1 Tujuan Pembuatan Aplikasi

Dengan adanya aplikasi pengelolaan jadwal kelas ini, diharapkan proses administrasi pendidikan dapat berjalan lebih lancar, meminimalisir kesalahan penjadwalan, serta meningkatkan efisiensi kerja. Selain itu, penulis juga dapat mengasah keterampilan dalam pengembangan aplikasi *fullstack* dengan memanfaatkan teknologi *website* modern.

### 4.2 Manfaat bagi Penulis

Penulis dapat mengasah keterampilan dalam pengembangan aplikasi *fullstack* dengan memanfaatkan teknologi *website* modern, termasuk:

- **Backend Development**: Laravel PHP Framework, Database Oracle, REST API
- **Frontend Development**: Blade Templates, JavaScript, Bootstrap, AOS Animations
- **DevOps**: XAMPP, Git Version Control
- **Soft Skills**: Problem solving, komunikasi dengan tim, project management

---

## 5. TEKNOLOGI YANG DIGUNAKAN

### 5.1 Backend
- **Framework**: Laravel 11.x
- **Database**: Oracle Database (melalui OCI8)
- **Authentication**: Laravel Breeze dengan modifikasi custom
- **API**: RESTful API untuk komunikasi client-server

### 5.2 Frontend
- **Template Engine**: Blade (Laravel)
- **CSS Framework**: Bootstrap 5, Custom CSS
- **JavaScript Libraries**: 
  - AOS (Animate On Scroll)
  - Chart.js untuk visualisasi data
  - jQuery untuk interaktivitas
- **Icons**: Bootstrap Icons

### 5.3 Development Tools
- **Server**: XAMPP (Apache, PHP, Oracle)
- **Version Control**: Git
- **Code Editor**: Visual Studio Code / PHPStorm
- **Database Management**: DBeaver / SQL Developer

---

## 6. FITUR UTAMA APLIKASI HAKAI

### 6.1 Untuk Admin/Supervisor
- Dashboard dengan statistik lengkap
- Manajemen pengguna (CRUD karyawan)
- Manajemen tugas lengkap (create, assign, update, delete)
- Monitoring real-time semua tugas
- Export laporan

### 6.2 Untuk Karyawan
- Dashboard personal dengan tugas yang ditugaskan
- Update status tugas (Todo → In Progress → Done)
- Sistem komentar untuk komunikasi
- Notifikasi deadline dan tugas baru
- Riwayat tugas yang telah dikerjakan

### 6.3 Fitur Umum
- Dark mode / Light mode
- Responsive design (mobile-friendly)
- Search dan filter tugas
- Halaman welcome yang informatif
- Keamanan dengan authentication dan authorization

---

## 7. KESIMPULAN

Pengembangan **Sistem Manajemen Tugas Hakai** selama masa PKL di PT Zahir Accounting Divisi *Software Developer* merupakan pengalaman yang sangat berharga. Aplikasi ini tidak hanya memecahkan masalah nyata dalam manajemen tugas di sekolah atau lembaga pelatihan, tetapi juga memberikan kesempatan bagi penulis untuk:

1. Menerapkan ilmu yang dipelajari di sekolah dalam konteks dunia kerja nyata
2. Mempelajari teknologi dan *best practices* industri
3. Mengembangkan *soft skills* seperti komunikasi, kolaborasi, dan problem-solving
4. Memahami siklus pengembangan *software* dari analisis kebutuhan hingga implementasi

Aplikasi berbasis web modern ini membuktikan bahwa teknologi dapat meningkatkan efisiensi dan produktivitas dalam pengelolaan sumber daya manusia dan proyek.

---

## 8. DOKUMENTASI TAMBAHAN

### 8.1 Struktur Database
- **Users**: Menyimpan data pengguna (admin, supervisor, karyawan)
- **Tasks**: Menyimpan data tugas
- **Task_Assignments**: Relasi many-to-many antara tugas dan karyawan
- **Comments**: Sistem komentar pada tugas
- **Password_Reset_Tokens**: Untuk fitur reset password

### 8.2 Arsitektur Aplikasi
- **MVC Pattern**: Model-View-Controller untuk struktur kode yang terorganisir
- **Repository Pattern**: Untuk abstraksi data access layer
- **Service Layer**: Business logic terpisah dari controller
- **Middleware**: Untuk authentication dan role-based access control

---

**Catatan**: Dokumen ini dapat digunakan sebagai referensi untuk penyusunan laporan PKL. Sesuaikan dengan format dan ketentuan laporan yang diminta oleh sekolah/institusi.

**Penulis**: [Nama Anda]  
**Sekolah**: SMK Yadika Soreang  
**Tempat PKL**: PT Zahir Accounting - Divisi Software Developer  
**Periode PKL**: [Sesuaikan dengan periode PKL Anda]  
**Pembimbing Sekolah**: [Nama Pembimbing]  
**Pembimbing Industri**: [Nama Pembimbing di Perusahaan]
