<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Qr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrGeneratorController extends Controller
{
    /**
     * Tampilkan Generator QR Code.
     */
    public function index()
    {
        // Ambil QR Code terakhir dengan pagination
        $recentQrs = Qr::orderBy('created_at', 'desc')->paginate(5);

        return view('admin.buat-qr', [
            'recentQrs' => $recentQrs,
            'editMode' => false,
            'editData' => null,
            'generatedData' => session('generatedData') ?? null,
        ]);
    }

    /**
     * Simpan QR Code Baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'cek_in' => 'required',
            'cek_out' => 'required',
            'cek_out_jumat' => 'nullable',
            'expired_at' => 'required|date',
        ]);

        // Generate Kode Acak 8 Karakter
        $kodeUnik = 'QR-' . strtoupper(Str::random(8));
        $now = Carbon::now('Asia/Makassar');

        $qr = Qr::create([
            'nama_kegiatan' => trim($request->input('nama_kegiatan')),
            'kode_qr' => $kodeUnik,
            'cek_in' => $request->input('cek_in'),
            'cek_out' => $request->input('cek_out'),
            'cek_out_jumat' => $request->input('cek_out_jumat') ?: '16:00:00',
            'cek_in_minggu' => '06:30:00', // Default Minggu check-in
            'expired_at' => Carbon::parse($request->input('expired_at'), 'Asia/Makassar')->toDateTimeString(),
            'created_at' => $now->toDateTimeString(),
        ]);

        $generatedData = [
            'kode' => $kodeUnik,
            'nama_kegiatan' => $qr->nama_kegiatan,
            'created' => $qr->created_at,
            'expired' => $qr->expired_at,
            'cek_in' => $qr->cek_in,
            'cek_out' => $qr->cek_out,
            'cek_out_jumat' => $qr->cek_out_jumat,
            'url_img' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $kodeUnik,
        ];

        return redirect()->route('admin.buat_qr')->with('success', 'QR Code berhasil dibuat!')->with('generatedData', $generatedData);
    }

    /**
     * Tampilkan Edit Form QR Code.
     */
    public function edit($id)
    {
        $qr = Qr::findOrFail($id);
        $recentQrs = Qr::orderBy('created_at', 'desc')->paginate(5);

        return view('admin.buat-qr', [
            'recentQrs' => $recentQrs,
            'editMode' => true,
            'editData' => $qr,
            'generatedData' => null,
        ]);
    }

    /**
     * Perbarui QR Code.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'cek_in' => 'required',
            'cek_out' => 'required',
            'cek_out_jumat' => 'nullable',
            'expired_at' => 'required|date',
        ]);

        $qr = Qr::findOrFail($id);
        
        // Buat Kode Unik Baru Demi Keamanan (sesuai logika legacy)
        $newKodeUnik = 'QR-' . strtoupper(Str::random(8));

        $qr->update([
            'nama_kegiatan' => trim($request->input('nama_kegiatan')),
            'kode_qr' => $newKodeUnik,
            'cek_in' => $request->input('cek_in'),
            'cek_out' => $request->input('cek_out'),
            'cek_out_jumat' => $request->input('cek_out_jumat') ?: '16:00:00',
            'expired_at' => Carbon::parse($request->input('expired_at'), 'Asia/Makassar')->toDateTimeString(),
        ]);

        $generatedData = [
            'kode' => $newKodeUnik,
            'nama_kegiatan' => $qr->nama_kegiatan,
            'created' => $qr->created_at,
            'expired' => $qr->expired_at,
            'cek_in' => $qr->cek_in,
            'cek_out' => $qr->cek_out,
            'cek_out_jumat' => $qr->cek_out_jumat,
            'url_img' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $newKodeUnik,
        ];

        return redirect()->route('admin.buat_qr')->with('success', 'QR Code berhasil diperbarui!')->with('generatedData', $generatedData);
    }

    /**
     * Hapus QR Code.
     */
    public function destroy($id)
    {
        $qr = Qr::findOrFail($id);
        
        // Hapus (otomatis cascade absensi karena diset cascade pada migration)
        $qr->delete();

        return redirect()->route('admin.buat_qr')->with('success', 'QR Code dan semua data terkait berhasil dihapus!');
    }
}
