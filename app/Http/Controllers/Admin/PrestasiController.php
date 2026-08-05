<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Qr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestasiController extends Controller
{
    /**
     * Tampilkan Halaman Prestasi / Ranking.
     */
    public function index(Request $request)
    {
        $kegiatanList = Qr::select('id_qr', 'nama_kegiatan')->orderBy('created_at', 'desc')->get();

        $selectedKegiatan = $request->input('kegiatan', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Tarik data untuk kelima kategori ranking
        $rankingHadir = $this->getRankingData('hadir', $selectedKegiatan, $startDate, $endDate);
        $rankingJamKerja = $this->getRankingData('jam_kerja', $selectedKegiatan, $startDate, $endDate);
        $rankingTerlambat = $this->getRankingData('terlambat', $selectedKegiatan, $startDate, $endDate);
        $rankingPulangCepat = $this->getRankingData('pulang_cepat', $selectedKegiatan, $startDate, $endDate);
        $rankingDatangCepat = $this->getRankingData('datang_cepat', $selectedKegiatan, $startDate, $endDate);

        return view('admin.prestasi', [
            'kegiatanList' => $kegiatanList,
            'selectedKegiatan' => $selectedKegiatan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'rankingHadir' => $rankingHadir,
            'rankingJamKerja' => $rankingJamKerja,
            'rankingTerlambat' => $rankingTerlambat,
            'rankingPulangCepat' => $rankingPulangCepat,
            'rankingDatangCepat' => $rankingDatangCepat,
        ]);
    }

    /**
     * Handle AJAX Filter Request.
     */
    public function ajaxFilter(Request $request)
    {
        $selectedKegiatan = $request->input('kegiatan', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Tarik data untuk kelima kategori ranking
        $rankingHadir = $this->getRankingData('hadir', $selectedKegiatan, $startDate, $endDate);
        $rankingJamKerja = $this->getRankingData('jam_kerja', $selectedKegiatan, $startDate, $endDate);
        $rankingTerlambat = $this->getRankingData('terlambat', $selectedKegiatan, $startDate, $endDate);
        $rankingPulangCepat = $this->getRankingData('pulang_cepat', $selectedKegiatan, $startDate, $endDate);
        $rankingDatangCepat = $this->getRankingData('datang_cepat', $selectedKegiatan, $startDate, $endDate);

        return response()->json([
            'hadir' => $rankingHadir,
            'jam_kerja' => $rankingJamKerja,
            'terlambat' => $rankingTerlambat,
            'pulang_cepat' => $rankingPulangCepat,
            'datang_cepat' => $rankingDatangCepat,
        ]);
    }

    /**
     * Core logic getRankingData matching SQL query exactly.
     */
    private function getRankingData($kategori, $idKegiatan = null, $startDate = null, $endDate = null)
    {
        $whereClause = "WHERE 1=1";
        $bindings = [];

        // Filter berdasarkan kegiatan
        if (!empty($idKegiatan) && $idKegiatan !== 'all') {
            $whereClause .= " AND a.id_qr = ?";
            $bindings[] = $idKegiatan;
        }

        // Filter berdasarkan tanggal
        if (!empty($startDate)) {
            $whereClause .= " AND DATE(a.created_at) >= ?";
            $bindings[] = $startDate;
        }

        if (!empty($endDate)) {
            $whereClause .= " AND DATE(a.created_at) <= ?";
            $bindings[] = $endDate;
        }

        switch ($kategori) {
            case 'hadir':
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
                break;

            case 'jam_kerja':
                $query = "
                    SELECT 
                        m.nama_lengkap,
                        m.posisi_magang,
                        m.instansi,
                        COUNT(a.id_absensi) as total_absensi,
                        SUM(CASE WHEN a.absen_cek_in IS NOT NULL AND a.absen_cek_out IS NOT NULL THEN 1 ELSE 0 END) as total_hadir,
                        SUM(CASE WHEN a.status_cek_in = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat,
                        SUM(CASE WHEN a.status_cek_out = 'pulang_cepat' THEN 1 ELSE 0 END) as total_pulang_cepat,
                        SEC_TO_TIME(SUM(
                            CASE 
                                WHEN a.absen_cek_in IS NOT NULL AND a.absen_cek_out IS NOT NULL 
                                THEN TIME_TO_SEC(TIMEDIFF(a.absen_cek_out, a.absen_cek_in))
                                ELSE 0 
                            END
                        )) as total_jam_kerja
                    FROM absensi a
                    LEFT JOIN magang m ON a.id_user = m.id_user
                    $whereClause
                    GROUP BY m.id_user, m.nama_lengkap, m.posisi_magang, m.instansi
                    HAVING total_jam_kerja > 0
                    ORDER BY total_jam_kerja DESC
                    LIMIT 3
                ";
                break;

            case 'terlambat':
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
                    HAVING total_terlambat > 0
                    ORDER BY total_terlambat DESC, total_absensi DESC
                    LIMIT 3
                ";
                break;

            case 'pulang_cepat':
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
                    HAVING total_pulang_cepat > 0
                    ORDER BY total_pulang_cepat DESC, total_absensi DESC
                    LIMIT 3
                ";
                break;

            case 'datang_cepat':
                $query = "
                    SELECT 
                        m.nama_lengkap,
                        m.posisi_magang,
                        m.instansi,
                        COUNT(a.id_absensi) as total_absensi,
                        SUM(CASE WHEN a.absen_cek_in IS NOT NULL AND a.absen_cek_out IS NOT NULL THEN 1 ELSE 0 END) as total_hadir,
                        SUM(CASE WHEN a.status_cek_in = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat,
                        SUM(CASE WHEN a.status_cek_out = 'pulang_cepat' THEN 1 ELSE 0 END) as total_pulang_cepat,
                        SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(a.absen_cek_in)))) as rata_rata_datang
                    FROM absensi a
                    LEFT JOIN magang m ON a.id_user = m.id_user
                    $whereClause AND a.absen_cek_in IS NOT NULL
                    GROUP BY m.id_user, m.nama_lengkap, m.posisi_magang, m.instansi
                    HAVING rata_rata_datang IS NOT NULL AND total_absensi >= 3
                    ORDER BY rata_rata_datang ASC
                    LIMIT 3
                ";
                break;

            default:
                return [];
        }

        $results = DB::select($query, $bindings);

        // Format hasil
        $ranking = [];
        foreach ($results as $row) {
            $data = (array)$row;
            if ($kategori === 'jam_kerja' && isset($data['total_jam_kerja'])) {
                $data['total_jam_kerja_formatted'] = $this->formatTotalWaktu($data['total_jam_kerja']);
            }
            if ($kategori === 'datang_cepat' && isset($data['rata_rata_datang'])) {
                $data['rata_rata_datang_formatted'] = $this->formatJam($data['rata_rata_datang']);
            }
            $ranking[] = $data;
        }

        return $ranking;
    }

    /**
     * Helper Format Total Waktu.
     */
    private function formatTotalWaktu($totalWaktu)
    {
        if (empty($totalWaktu) || $totalWaktu === '00:00:00') {
            return '0 jam';
        }
        $totalWaktu = preg_replace('/\.\d+$/', '', $totalWaktu);
        if (preg_match('/^(\d{1,3}):(\d{2}):(\d{2})$/', $totalWaktu, $matches)) {
            $jam = intval($matches[1]);
            $menit = intval($matches[2]);
            
            $totalJam = $jam + floor($menit / 60);
            $sisaMenit = $menit % 60;
            
            if ($totalJam > 0) {
                return $sisaMenit > 0 ? $totalJam . ' jam ' . $sisaMenit . ' menit' : $totalJam . ' jam';
            }
            return $sisaMenit . ' menit';
        }
        return $totalWaktu;
    }

    /**
     * Helper Format Jam.
     */
    private function formatJam($jam)
    {
        if (empty($jam) || $jam === '00:00:00') {
            return '--:--';
        }
        $jam = preg_replace('/\.\d+$/', '', $jam);
        if (preg_match('/^(\d{1,2}):(\d{2}):(\d{2})$/', $jam, $matches)) {
            return sprintf('%02d:%02d', intval($matches[1]), intval($matches[2]));
        }
        return $jam;
    }
}
