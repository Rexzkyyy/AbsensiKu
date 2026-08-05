<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Qr;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Tampilkan Laporan Kehadiran Magang.
     */
    public function index(Request $request)
    {
        $kegiatanList = Qr::select('id_qr', 'nama_kegiatan')->orderBy('created_at', 'desc')->get();

        $search = $request->input('search');
        $selectedKegiatan = $request->input('kegiatan', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statusCekIn = $request->input('status_cek_in', 'all');
        $statusCekOut = $request->input('status_cek_out', 'all');

        // Build Query Kehadiran Magang
        $query = Absensi::with(['user.magang', 'qr']);

        // Filter: Pencarian nama
        if (!empty($search)) {
            $query->whereHas('user.magang', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        // Filter: Kegiatan
        if (!empty($selectedKegiatan) && $selectedKegiatan !== 'all') {
            $query->where('id_qr', $selectedKegiatan);
        }

        // Filter: Rentang Tanggal
        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Filter: Status Check-in
        if (!empty($statusCekIn) && $statusCekIn !== 'all') {
            $query->where('status_cek_in', $statusCekIn);
        }

        // Filter: Status Check-out
        if (!empty($statusCekOut) && $statusCekOut !== 'all') {
            $query->where('status_cek_out', $statusCekOut);
        }

        // Paginate data for neat display (e.g. 25 per page)
        $laporan = $query->orderBy('created_at', 'desc')
            ->orderBy('id_absensi', 'desc')
            ->paginate(25)
            ->withQueryString();

        // Format waktu kerja human-readable
        foreach ($laporan as $log) {
            $log->total_waktu_formatted = $this->formatTotalWaktu($log->total_waktu);
        }

        return view('admin.laporan', [
            'kegiatanList' => $kegiatanList,
            'search' => $search,
            'selectedKegiatan' => $selectedKegiatan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statusCekIn' => $statusCekIn,
            'statusCekOut' => $statusCekOut,
            'laporan' => $laporan,
        ]);
    }

    /**
     * Ekspor Laporan Kehadiran (Print view yang rapi).
     */
    public function export(Request $request)
    {
        $search = $request->input('search');
        $selectedKegiatan = $request->input('kegiatan', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statusCekIn = $request->input('status_cek_in', 'all');
        $statusCekOut = $request->input('status_cek_out', 'all');

        $query = Absensi::with(['user.magang', 'qr']);

        if (!empty($search)) {
            $query->whereHas('user.magang', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }
        if (!empty($selectedKegiatan) && $selectedKegiatan !== 'all') {
            $query->where('id_qr', $selectedKegiatan);
        }
        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        if (!empty($statusCekIn) && $statusCekIn !== 'all') {
            $query->where('status_cek_in', $statusCekIn);
        }
        if (!empty($statusCekOut) && $statusCekOut !== 'all') {
            $query->where('status_cek_out', $statusCekOut);
        }

        $laporan = $query->orderBy('created_at', 'desc')->get();

        foreach ($laporan as $log) {
            $log->total_waktu_formatted = $this->formatTotalWaktu($log->total_waktu);
        }

        return view('admin.laporan-print', [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
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
