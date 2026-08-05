<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Magang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Magang.
     */
    public function index()
    {
        $user = Auth::user();
        $id_user = $user->id_user;

        // Ambil riwayat absensi 8 data terakhir
        $myRecentAttendance = Absensi::where('id_user', $id_user)
            ->with('qr')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Cek status absen hari ini (WITA)
        $today = Carbon::now('Asia/Makassar')->toDateString();
        $todayAttendance = Absensi::where('id_user', $id_user)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->first();

        // Cek apakah user sudah mengisi data magang
        $sudahIsiDataMagang = Magang::where('id_user', $id_user)->exists();

        // Hitung total jam kerja format helper
        foreach ($myRecentAttendance as $attendance) {
            $attendance->total_waktu_formatted = $this->formatTotalWaktu($attendance->total_waktu);
        }

        return view('magang.dashboard', [
            'username' => $user->username,
            'myRecentAttendance' => $myRecentAttendance,
            'todayAttendance' => $todayAttendance,
            'sudahIsiDataMagang' => $sudahIsiDataMagang,
        ]);
    }

    /**
     * Helper untuk memformat total waktu kerja
     */
    private function formatTotalWaktu($total_waktu)
    {
        if (empty($total_waktu) || $total_waktu === '00:00:00') {
            return '-';
        }
        
        $parts = explode(':', $total_waktu);
        $jam = (int)$parts[0];
        $menit = (int)$parts[1];
        
        $result = '';
        if ($jam > 0) $result .= $jam . ' jam ';
        if ($menit > 0) $result .= $menit . ' menit';
        
        return empty(trim($result)) ? '0 menit' : trim($result);
    }
}
