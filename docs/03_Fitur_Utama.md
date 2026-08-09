# 03. Penjelasan Fitur Utama Terperinci

Aplikasi "Absensiku" memiliki fungsi yang dirancang spesifik untuk kebutuhan magang. Berikut adalah rincian fungsionalitas dari setiap fitur utama.

## A. Modul Administrator (Mentor)

### 1. Dashboard Eksekutif (Beranda)
Halaman depan khusus Admin yang berisi statistik secara sekilas. Menampilkan informasi seperti:
*   Total peserta magang yang aktif.
*   Jumlah peserta yang sudah absensi pada hari ini (Hadir / Belum Hadir).

### 2. Kelola User (Manajemen Akun)
Administrator memiliki kontrol penuh (CRUD) terhadap akun peserta.
*   **Create:** Mendaftarkan peserta magang baru (email, username, password default).
*   **Read:** Melihat daftar seluruh akun yang ada beserta perannya.
*   **Update:** Mereset password peserta jika mereka lupa.
*   **Delete:** Menghapus akun peserta yang sudah selesai masa magangnya atau dinonaktifkan.

### 3. Generator QR Code Absensi
Ini merupakan **Core Feature** (Fitur Inti) untuk admin.
*   Admin dapat membuat QR Code baru untuk sesi Check-in (Pagi) atau Check-out (Sore).
*   Dilengkapi dengan sistem *Expiration Time*. Contoh: Admin men-generate QR Code pada jam 08:00 dan diset berakhir pada 09:00. Jika peserta memindainya pada jam 09:05, sistem akan menolak dan memberi peringatan bahwa QR Code sudah kadaluwarsa (terlambat).
*   Dilengkapi fitur "Tampilkan/Print Layar Penuh" sehingga QR dapat ditampilkan besar di monitor komputer lobi/ruang magang untuk discan bergantian.

### 4. Pelaporan dan Export Data (Reporting)
*   Admin dapat memfilter riwayat kehadiran seluruh peserta berdasarkan rentang tanggal tertentu (Misal: 1 Juli - 31 Juli).
*   Data kehadiran dapat diekspor langsung dalam format **Microsoft Excel (.xlsx/.csv)**, sehingga Mentor/Admin dapat memprosesnya untuk keperluan penilaian administrasi, dilampirkan sebagai laporan, atau diproses lebih lanjut secara eksternal.

---

## B. Modul Peserta Magang (Intern)

### 1. Profil Biodata Diri
*   Setelah login pertama kali (menggunakan akun yang dibuatkan Admin), peserta diwajibkan melengkapi profil.
*   Informasi yang dimasukkan meliputi: Foto formal, Alamat Domisili, Nomor WhatsApp/HP yang dapat dihubungi, dan Nama Universitas/Sekolah asal.

### 2. Web-based Scanner (Kamera In-Browser)
*   Fitur utama untuk peserta magang. Memungkinkan peserta mengubah smartphone mereka menjadi mesin pemindai (scanner) tanpa perlu menginstal aplikasi tambahan di Play Store/App Store.
*   Menggunakan API kamera bawaan browser.
*   Proses: Pilih mode (Check In atau Check Out) -> Arahkan Kamera -> Notifikasi Sukses/Gagal muncul seketika di layar ponsel.

### 3. Riwayat (History) Absensi Pribadi
*   Peserta berhak melihat transparansi data mereka sendiri.
*   Mereka bisa memantau pada tanggal berapa saja mereka hadir, jam berapa mereka melakukan scan masuk, dan jam berapa mereka pulang.
*   Fitur unduh bukti kehadiran individu berformat **PDF** (opsional), jika kampus/sekolah peserta meminta bukti kehadiran/logbook harian fisik.
