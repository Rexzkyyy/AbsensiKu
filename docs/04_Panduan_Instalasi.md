# 04. Panduan Instalasi (Development)

Bagi pengembang sistem atau admin IT yang ingin melakukan instalasi proyek "Absensiku" pada server lokal maupun server production (VPS / Shared Hosting), ikuti langkah-langkah standar instalasi framework Laravel di bawah ini.

## Persyaratan Server (Requirements)
*   PHP >= 8.1
*   Composer (PHP Package Manager)
*   MySQL Database / MariaDB
*   Node.js & NPM (Opsional untuk kompilasi aset Frontend, jika menggunakan Vite/Mix)
*   Web Server (Apache/Nginx/Laragon/XAMPP)

## Langkah Instalasi

1. **Persiapkan Folder Proyek**
   Jika menggunakan Laragon, pastikan folder proyek berada di dalam `C:\laragon\www\Absensiku`.

2. **Install Dependensi PHP**
   Buka terminal atau Command Prompt pada direktori proyek (`Absensiku`) dan jalankan:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (.env)**
   * Salin file `.env.example` dan ubah namanya menjadi `.env`.
   * Pada file `.env`, atur bagian konfigurasi database, misalnya:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=nama_database_absensiku
     DB_USERNAME=root
     DB_PASSWORD=
     ```

4. **Generate Application Key**
   Jalankan perintah ini di terminal untuk membuat kunci rahasia aplikasi Laravel:
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database**
   Jalankan migrasi untuk membangun semua tabel di dalam database yang telah ditentukan:
   ```bash
   php artisan migrate
   ```
   *(Opsional)* Jika tersedia class Seeder (Data palsu awal untuk Admin):
   ```bash
   php artisan db:seed
   ```

6. **Storage Link (Penting untuk Foto/File Upload)**
   Agar file gambar/profil peserta yang diupload bisa diakses oleh publik, jalankan:
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Aplikasi (Local Development)**
   Jika tidak menggunakan auto-vhost (seperti Laragon), Anda bisa menjalankan server development bawaan PHP dengan:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser pada alamat `http://localhost:8000`.

---
**Catatan untuk Produksi (Shared Hosting/InfinityFree):**
Pada shared hosting standar di mana domain diarahkan langsung ke direktori utama, sistem bergantung pada file `.htaccess` dan/atau `index.php` pada folder root yang me- *require* `public/index.php` secara otomatis. Pastikan versi PHP di panel hosting mendukung versi Laravel yang digunakan.
