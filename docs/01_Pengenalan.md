# 01. Pengenalan Proyek

## Latar Belakang

Manajemen kehadiran merupakan salah satu aspek terpenting dalam memantau kedisiplinan dan keaktifan para peserta magang (intern) yang ditugaskan di suatu instansi, dalam hal ini **Badan Pusat Statistik (BPS) Provinsi Sulawesi Tenggara**. Sebelumnya, pencatatan kehadiran sering kali mengandalkan metode manual menggunakan kertas, atau platform umum seperti Google Forms yang rentan terhadap manipulasi (seperti absen titipan/absen dari rumah) dan sulit direkapitulasi dengan cepat.

Melihat kebutuhan akan sistem yang lebih efisien, transparan, dan dapat mencegah manipulasi (seperti absen palsu tanpa datang ke lokasi magang), diinisiasi lah sebuah **Sistem Absensi Berbasis QR Code**. 

## Tujuan Aplikasi

Aplikasi "Absensiku" dikembangkan dengan beberapa tujuan utama:
1. **Digitalisasi Penuh:** Mengubah sistem absen manual (kertas/paraf) menjadi digital penuh.
2. **Kecepatan dan Kemudahan:** Peserta magang cukup membuka smartphone mereka, memindai QR code, dan absensi akan otomatis tersimpan dalam hitungan detik.
3. **Mencegah Kecurangan:** QR Code yang dihasilkan oleh admin bersifat dinamis dan memiliki batas waktu (expired time). Artinya, QR Code tersebut tidak bisa disebarluaskan di grup WhatsApp lalu dipindai dari rumah, karena hanya valid dalam jangka waktu tertentu saat peserta secara fisik berada di depan monitor/layar yang menampilkan QR Code.
4. **Sentralisasi Data:** Seluruh data profil peserta, jam kedatangan, jam pulang, terpusat dalam satu database untuk kemudahan pembuatan rekapitulasi laporan bulanan.

## Ruang Lingkup (Scope)

Ruang lingkup proyek ini difokuskan pada:
- Pendaftaran akun peserta dan manajemen akun oleh administrator (Mentor).
- Modul pembuatan QR Code untuk check-in dan check-out.
- Modul pemindai (scanner) menggunakan kamera web di perangkat mobile.
- Modul rekapitulasi data (pelaporan) dengan fitur export ke format dokumen (PDF dan Excel).

## Pengembang Sistem

Sistem ini diinisiasi, dirancang, dan dikembangkan secara *Full Stack* oleh **Ikhsanuddin Rezki**. Pengembangan ini merupakan implementasi nyata dalam menyelesaikan program magang dan memberikan dampak positif dari sisi teknologi bagi lingkungan operasional BPS Provinsi Sulawesi Tenggara.
