<?php

/**
 * Peringatan InfinityFree: "No index file was found for your website!"
 * File ini dibuat khusus untuk memuaskan sistem scanner InfinityFree yang mencari index.php di luar folder public.
 * 
 * Secara standar, trafik masuk akan diarahkan ke public/index.php oleh file .htaccess.
 * Namun jika .htaccess gagal, file ini akan mem-forward koneksi ke public/index.php.
 */

if (file_exists(__DIR__.'/public/index.php')) {
    require_once __DIR__.'/public/index.php';
} else {
    echo "Sistem Laravel belum terinstall dengan benar (public/index.php hilang).";
}
