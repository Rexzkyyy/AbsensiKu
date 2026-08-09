# 02. Arsitektur & Teknologi

Proyek ini menggunakan pendekatan arsitektur **Model-View-Controller (MVC)** yang umum digunakan pada aplikasi berbasis web modern. Framework yang dipilih untuk mengimplementasikan arsitektur ini adalah **Laravel**.

## Tumpukan Teknologi (Tech Stack)

### 1. Backend (Logika & Server)
*   **Bahasa Pemrograman:** PHP (versi 8.x ke atas direkomendasikan).
*   **Kerangka Kerja (Framework):** Laravel. Dipilih karena keamanan terjamin (perlindungan dari CSRF, SQL Injection), fitur autentikasi bawaan yang tangguh, ORM (Eloquent) yang memudahkan pengelolaan database, serta routing yang elegan.
*   **Autentikasi:** Menggunakan sistem sesi bawaan Laravel yang difokuskan pada pemisahan otorisasi berbasis peran (Role-based Authentication: Admin/Mentor vs Magang).

### 2. Frontend (Antarmuka Pengguna)
*   **View Engine:** Laravel Blade Templates. Blade sangat kuat untuk membuat tata letak dinamis dan memecah komponen-komponen visual (navbar, sidebar, footer).
*   **Styling:** HTML5 dan CSS. Tampilan didesain agar responsif pada semua ukuran layar (Mobile-First Design) mengingat fitur utama (scan QR) dilakukan melalui ponsel genggam peserta magang.
*   **Interaktivitas (JavaScript):** 
    *   Menggunakan *Vanilla JavaScript* untuk pengelolaan event (klik tombol, menu pop-up).
    *   Pustaka eksternal pihak ketiga (seperti `html5-qrcode.min.js`) digunakan khusus untuk mengakses kamera pada browser dan menerjemahkan pola matriks QR Code menjadi data teks/URL yang dapat diproses oleh backend.

### 3. Database
*   **RDBMS:** MySQL.
*   **Skema Utama:** 
    *   Tabel `users` (Menyimpan data otentikasi: nama, email, password, role).
    *   Tabel `user_profiles` / `biodatas` (Menyimpan foto, asal instansi, no hp, alamat).
    *   Tabel `qr_codes` (Menyimpan data QR Code yang digenerate oleh admin, beserta *expired time*).
    *   Tabel `attendances` / `absensi` (Menyimpan rekaman waktu check-in, check-out, dan status kehadiran setiap user berdasarkan ID).

## Alur Kerja (Workflow) Arsitektur Scanner

1. **Pembuatan QR (Admin):** Admin masuk ke halaman pembuat QR. Sistem membuatkan string unik secara acak, menyimpannya di database dengan tanggal/waktu kadaluwarsa, lalu menggunakan librari (misalnya `simplesoftwareio/simple-qrcode`) untuk mengubah string unik itu menjadi gambar visual QR Code 2D.
2. **Pemindaian (Peserta):** Peserta magang menggunakan browser di ponselnya, masuk ke menu "Scan". Skrip JavaScript meminta akses kamera (WebRTC API).
3. **Verifikasi:** Kamera menangkap gambar QR layar Admin. Kode QR diterjemahkan menjadi teks dan dikirim via AJAX / form POST ke controller Laravel (backend).
4. **Validasi:** Controller Laravel mengecek apakah teks/string unik dari QR tersebut valid di database dan belum *expired*. Jika valid, rekaman jam masuk/pulang di-insert/update ke dalam tabel `attendances`. 
5. **Respons:** Backend mengembalikan sinyal sukses, UI ponsel peserta menampilkan centang/pesan berhasil.
