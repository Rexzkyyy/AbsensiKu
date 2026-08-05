<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Tampilkan Halaman Profil / Data Peserta Magang.
     */
    public function index()
    {
        $id_user = Auth::id();
        $magang = Magang::where('id_user', $id_user)->first();

        return view('magang.peserta', [
            'magang' => $magang,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Simpan / Perbarui Data Peserta Magang.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'instansi' => 'required|string|max:150',
            'posisi_magang' => 'required|string|max:100',
            'pembimbing' => 'nullable|string|max:150',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'catatan' => 'nullable|string',
        ]);

        $id_user = Auth::id();

        Magang::updateOrCreate(
            ['id_user' => $id_user],
            [
                'nama_lengkap' => trim($request->input('nama_lengkap')),
                'instansi' => trim($request->input('instansi')),
                'posisi_magang' => trim($request->input('posisi_magang')),
                'pembimbing' => trim($request->input('pembimbing')),
                'tanggal_mulai' => $request->input('tanggal_mulai'),
                'tanggal_selesai' => $request->input('tanggal_selesai'),
                'catatan' => trim($request->input('catatan')),
                'status' => 'Aktif', // default status
            ]
        );

        return redirect()->route('magang.peserta')->with('success', 'Data magang Anda berhasil disimpan!');
    }
}
