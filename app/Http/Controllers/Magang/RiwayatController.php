<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Tampilkan Riwayat Absensi Pribadi.
     */
    public function index()
    {
        $id_user = Auth::id();
        $username = Auth::user()->username;

        // Ambil data absensi user yang sedang login dengan pagination
        $riwayat = Absensi::where('id_user', $id_user)
            ->with('qr')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Format durasi jam kerja untuk setiap record
        foreach ($riwayat as $r) {
            $r->total_waktu_formatted = $this->formatTotalWaktu($r->total_waktu);
        }

        return view('magang.riwayat', [
            'riwayat' => $riwayat,
            'username' => $username,
        ]);
    }

    /**
     * Format total waktu kerja.
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
