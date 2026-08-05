<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Qr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScannerController extends Controller
{
    /**
     * Tampilkan Halaman Scan QR Code.
     */
    public function index()
    {
        $user = Auth::user();
        $avatarInitials = strtoupper(substr($user->username, 0, 2));

        return view('magang.scan', [
            'username' => $user->username,
            'role' => $user->role,
            'avatarInitials' => $avatarInitials,
            'scanResult' => session('scanResult') ?? null,
        ]);
    }

    /**
     * Proses Validasi Scan QR Code (Check-in / Check-out).
     */
    public function process(Request $request)
    {
        try {
            $request->validate([
                'qr_code' => 'required|string',
                'absensi_type' => 'required|in:check_in,check_out',
            ]);

            $qrCode = trim($request->input('qr_code'));
            $absensiType = $request->input('absensi_type');
            $userId = Auth::id();

            // Variabel waktu WITA
            $now = Carbon::now('Asia/Makassar');
            $currentTime = $now->format('H:i:s');
            $currentDate = $now->toDateString(); // Y-m-d
            $hariAbsen = $this->getHariIndonesia((int)$now->format('N'));

            // Cari QR code yang aktif
            $qr = Qr::where('kode_qr', $qrCode)
                ->where('expired_at', '>', $now->toDateTimeString())
                ->first();

            if (!$qr) {
                return back()->with('error', 'QR Code tidak valid atau sudah kadaluarsa!')->withInput();
            }

            // Cek apakah sudah absen hari ini
            $existingAttendance = Absensi::where('id_qr', $qr->id_qr)
                ->where('id_user', $userId)
                ->whereDate('created_at', $currentDate)
                ->first();

            if ($absensiType === 'check_in') {
                // === PROSES CHECK-IN ===
                if ($existingAttendance && $existingAttendance->absen_cek_in !== null) {
                    return back()->with('error', 'Anda sudah melakukan check-in untuk kegiatan ini hari ini!')->withInput();
                }

                // Tentukan waktu batas check-in
                if ($hariAbsen === 'Minggu' && !empty($qr->cek_in_minggu)) {
                    $waktuBatasCekIn = $qr->cek_in_minggu;
                    $waktuKhususHari = 'Minggu';
                } else {
                    $waktuBatasCekIn = $qr->cek_in;
                    $waktuKhususHari = null;
                }

                // Tentukan status check-in
                $statusCekIn = ($currentTime <= $waktuBatasCekIn) ? 'hadir' : 'terlambat';

                if ($existingAttendance) {
                    // Update check-in jika data record hari ini sudah dibuat (misal karena case lain)
                    $existingAttendance->update([
                        'absen_cek_in' => $currentTime,
                        'status_cek_in' => $statusCekIn,
                        'hari_absen' => $hariAbsen,
                        'created_at' => $currentDate,
                    ]);
                } else {
                    // Buat check-in baru
                    Absensi::create([
                        'id_qr' => $qr->id_qr,
                        'id_user' => $userId,
                        'absen_cek_in' => $currentTime,
                        'status_cek_in' => $statusCekIn,
                        'hari_absen' => $hariAbsen,
                        'total_waktu' => '00:00:00',
                        'created_at' => $currentDate,
                    ]);
                }

                $scanResult = [
                    'status' => 'success',
                    'type' => 'check_in',
                    'kode_qr' => $qr->kode_qr,
                    'nama_kegiatan' => $qr->nama_kegiatan,
                    'waktu_absen_formatted' => $this->formatTanggalIndonesia($now),
                    'status_absen' => $statusCekIn,
                    'hari_absen' => $hariAbsen,
                    'waktu_batas' => $waktuBatasCekIn,
                    'waktu_khusus_hari' => $waktuKhususHari,
                ];

                return redirect()->route('magang.scan')->with('success', 'Check-in berhasil! Status: ' . ($statusCekIn === 'hadir' ? 'Hadir' : 'Terlambat'))->with('scanResult', $scanResult);

            } else {
                // === PROSES CHECK-OUT ===
                if (!$existingAttendance) {
                    return back()->with('error', 'Anda belum melakukan check-in! Silakan check-in terlebih dahulu.')->withInput();
                }

                if ($existingAttendance->absen_cek_out !== null) {
                    return back()->with('error', 'Anda sudah melakukan check-out untuk kegiatan ini hari ini!')->withInput();
                }

                // Tentukan waktu batas check-out
                if ($hariAbsen === 'Jumat' && !empty($qr->cek_out_jumat)) {
                    $batasWaktuJam = $qr->cek_out_jumat;
                    $waktuKhususHari = 'Jumat';
                } else {
                    $batasWaktuJam = $qr->cek_out;
                    $waktuKhususHari = null;
                }

                // Tentukan status check-out
                $statusCekOut = ($currentTime >= $batasWaktuJam) ? 'hadir' : 'pulang_cepat';

                // Hitung total waktu
                $totalWaktu = $this->hitungTotalWaktu($existingAttendance->absen_cek_in, $currentTime);

                $existingAttendance->update([
                    'absen_cek_out' => $currentTime,
                    'status_cek_out' => $statusCekOut,
                    'total_waktu' => $totalWaktu,
                ]);

                $scanResult = [
                    'status' => 'success',
                    'type' => 'check_out',
                    'kode_qr' => $qr->kode_qr,
                    'nama_kegiatan' => $qr->nama_kegiatan,
                    'waktu_absen_formatted' => $this->formatTanggalIndonesia($now),
                    'status_absen' => $statusCekOut,
                    'hari_absen' => $hariAbsen,
                    'total_waktu_formatted' => $this->formatTotalWaktu($totalWaktu),
                    'total_waktu' => $totalWaktu,
                    'waktu_check_in' => $existingAttendance->absen_cek_in,
                    'waktu_batas' => $batasWaktuJam,
                    'waktu_khusus_hari' => $waktuKhususHari,
                ];

                return redirect()->route('magang.scan')->with('success', 'Check-out berhasil! Status: ' . ($statusCekOut === 'hadir' ? 'Hadir' : 'Pulang Cepat'))->with('scanResult', $scanResult);
            }
        } catch (\Throwable $e) {
            // Tangkap semua error dan kembalikan pesan error yang informatif ke user
            return back()->with('error', 'Gagal memproses absensi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper Hari Indonesia.
     */
    private function getHariIndonesia($dayNumber)
    {
        $hari = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        return $hari[$dayNumber] ?? '';
    }

    /**
     * Helper Format Tanggal Indonesia.
     */
    private function formatTanggalIndonesia(Carbon $date)
    {
        $hari = $this->getHariIndonesia((int)$date->format('N'));
        $tanggal = $date->day;
        $bulanList = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $bulan = $bulanList[$date->month];
        $tahun = $date->year;
        $jam = $date->format('H:i');

        return "$hari, $tanggal $bulan $tahun - $jam WITA";
    }

    /**
     * Hitung total waktu antara check-in dan check-out.
     */
    private function hitungTotalWaktu($waktuMulai, $waktuSelesai)
    {
        if (empty($waktuMulai) || empty($waktuSelesai)) {
            return '00:00:00';
        }
        try {
            $start = Carbon::parse($waktuMulai);
            $end = Carbon::parse($waktuSelesai);
            $diff = $start->diff($end);
            return sprintf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);
        } catch (\Throwable $e) {
            return '00:00:00';
        }
    }

    /**
     * Format total waktu ke text human-readable.
     */
    private function formatTotalWaktu($totalWaktu)
    {
        if ($totalWaktu === '00:00:00' || empty($totalWaktu)) {
            return '0 jam';
        }
        $parts = explode(':', $totalWaktu);
        $jam = (int)$parts[0];
        $menit = (int)$parts[1];
        $detik = (int)$parts[2];
        
        $result = '';
        if ($jam > 0) $result .= $jam . ' jam ';
        if ($menit > 0) $result .= $menit . ' menit ';
        if ($detik > 0 && $jam == 0) $result .= $detik . ' detik';
        
        return trim($result);
    }
}
