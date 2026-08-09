<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  <h1>Sistem Absensi Magang Berbasis QR Code</h1>
  <p><strong>BPS Provinsi Sulawesi Tenggara</strong></p>
</div>

---

## 📖 Tentang Proyek

**Absensiku** adalah sistem informasi absensi modern berbasis QR Code yang dirancang khusus untuk memanajemen kehadiran peserta magang di Badan Pusat Statistik (BPS) Provinsi Sulawesi Tenggara. Sistem ini memungkinkan pemantauan kehadiran yang lebih akurat, cepat, dan transparan, baik bagi para mentor pembimbing maupun peserta magang itu sendiri.

Sistem ini dikembangkan dari nol menggunakan kerangka kerja Laravel dan teknologi web modern lainnya untuk memastikan kecepatan, keamanan, dan skalabilitas aplikasi.

## ✨ Fitur Utama

Aplikasi ini dibagi menjadi dua peran (role) pengguna utama:

### 👨‍💼 Administrator / Mentor
*   **Manajemen Akun (User Management):** Tambah, edit, dan hapus akun peserta magang.
*   **Generator QR Code Dinamis:** Membuat QR Code absensi yang memiliki batas waktu kedaluwarsa untuk mencegah kecurangan.
*   **Pemantauan Kehadiran Real-time:** Melihat siapa saja peserta yang telah melakukan check-in maupun check-out pada hari ini.
*   **Export Laporan:** Mengunduh dan mencetak rekapitulasi data absensi (Excel) untuk kebutuhan laporan akademik/kantor.

### 🎓 Peserta Magang (Intern)
*   **Profil Pengguna:** Melengkapi biodata diri (foto, universitas asal, no HP, dll).
*   **Scan QR Code:** Melakukan absen masuk (check-in) dan pulang (check-out) hanya dengan memindai QR code menggunakan kamera smartphone.
*   **Riwayat Absensi Pribadi:** Memantau history absensi setiap hari serta mencetak/download bukti kehadiran dalam format PDF.

## 🛠️ Teknologi yang Digunakan

Aplikasi ini dibangun dengan *Tech Stack* (tumpukan teknologi) terbaik di kelasnya:
*   **Backend:** [Laravel](https://laravel.com) (PHP Framework)
*   **Frontend:** Blade Templating, HTML5, Vanilla CSS / Tailwind CSS, JavaScript murni
*   **Database:** MySQL
*   **Fitur Spesifik:** HTML5 QR Code Scanner (untuk pemindaian via browser)

## 📂 Dokumentasi Lengkap

Penjelasan terperinci dan menyeluruh mengenai sistem, cara penggunaan, dan arsitektur dapat Anda temukan di dalam direktori `docs`. Kami membaginya satu per satu agar mudah dipahami:

1. [Panduan Penggunaan (Admin & Peserta)](docs/PANDUAN_PENGGUNAAN.md)
2. [Pengenalan Proyek](docs/01_Pengenalan.md)
3. [Arsitektur & Teknologi](docs/02_Arsitektur_Teknologi.md)
4. [Penjelasan Fitur Utama](docs/03_Fitur_Utama.md)
5. [Panduan Instalasi (Development)](docs/04_Panduan_Instalasi.md)

## 💻 Kredit & Pengembang

Sistem Absensiku ini dianalisis, dirancang, dan dikembangkan secara penuh (Full Stack) oleh:

**Ikhsanuddin Rezki**  
*Full Stack Web Programmer*  
Proyek ini dibuat sebagai bagian dari pemenuhan target program magang  **Badan Pusat Statistik (BPS) Provinsi Sulawesi Tenggara**.

---

<div align="center">
  Dibuat dengan ❤️ menggunakan Laravel dan didedikasikan untuk kemajuan BPS Provinsi Sulawesi Tenggara.
</div>
