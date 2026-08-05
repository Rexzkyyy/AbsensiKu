<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$whereClause = "WHERE 1=1";
$bindings = [];

$query = "
    SELECT 
        m.nama_lengkap,
        m.posisi_magang,
        m.instansi,
        COUNT(a.id_absensi) as total_absensi,
        SUM(CASE WHEN a.absen_cek_in IS NOT NULL AND a.absen_cek_out IS NOT NULL THEN 1 ELSE 0 END) as total_hadir,
        SUM(CASE WHEN a.status_cek_in = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat,
        SUM(CASE WHEN a.status_cek_in = 'Tidak Hadir' THEN 1 ELSE 0 END) as total_tidak_hadir,
        SUM(CASE WHEN a.status_cek_out = 'pulang_cepat' THEN 1 ELSE 0 END) as total_pulang_cepat
    FROM absensi a
    LEFT JOIN magang m ON a.id_user = m.id_user
    $whereClause
    GROUP BY m.id_user, m.nama_lengkap, m.posisi_magang, m.instansi
    HAVING total_hadir > 0
    ORDER BY total_hadir DESC, total_absensi DESC
    LIMIT 3
";
try {
    $results = \Illuminate\Support\Facades\DB::select($query, $bindings);
    print_r($results);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
