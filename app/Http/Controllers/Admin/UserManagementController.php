<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Tampilkan Pengelolaan User.
     */
    public function index()
    {
        // Ambil semua user dengan pagination
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.kelola-user', [
            'users' => $users,
        ]);
    }

    /**
     * Simpan User Baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|string|email|max:150|unique:users,email',
            'password' => 'required|string|min:4',
            'role' => 'required|in:mentor,admin,magang',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // Hashing password baru di Laravel, tapi kita biarkan support text plain untuk integrasi data lama
            $password = trim($request->input('password'));

            // Buat User Baru
            $user = User::create([
                'username' => trim($request->input('username')),
                'email' => trim($request->input('email')),
                'password' => $password, // Plain text compatibility, or Hash::make($password) jika murni baru
                'role' => $request->input('role'),
                'keterangan' => trim($request->input('keterangan')),
            ]);

            // Jika role = magang, buat placeholder kosong di tabel magang agar langsung tercatat
            if ($user->role === 'magang') {
                Magang::create([
                    'id_user' => $user->id_user,
                    'nama_lengkap' => $user->username,
                    'instansi' => '-',
                    'posisi_magang' => '-',
                    'pembimbing' => 'La Ode Haerul Saleh',
                    'tanggal_mulai' => now()->toDateString(),
                    'tanggal_selesai' => now()->addMonths(6)->toDateString(),
                    'status' => 'Aktif',
                ]);
            }
        });

        return redirect()->route('admin.users')->with('success', 'User baru berhasil didaftarkan!');
    }

    /**
     * Ambil Detail User untuk AJAX/Edit Form.
     */
    public function getUserDetails($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Perbarui Data User.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $id . ',id_user',
            'email' => 'required|string|email|max:150|unique:users,email,' . $id . ',id_user',
            'password' => 'nullable|string|min:4',
            'role' => 'required|in:mentor,admin,magang',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->username = trim($request->input('username'));
            $user->email = trim($request->input('email'));
            $user->role = $request->input('role');
            $user->keterangan = trim($request->input('keterangan'));

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $user->password = trim($request->input('password'));
            }

            $user->save();

            // Jika diubah menjadi magang dan belum ada di tabel magang, buat placeholder
            if ($user->role === 'magang') {
                $exists = Magang::where('id_user', $user->id_user)->exists();
                if (!$exists) {
                    Magang::create([
                        'id_user' => $user->id_user,
                        'nama_lengkap' => $user->username,
                        'instansi' => '-',
                        'posisi_magang' => '-',
                        'pembimbing' => 'La Ode Haerul Saleh',
                        'tanggal_mulai' => now()->toDateString(),
                        'tanggal_selesai' => now()->addMonths(6)->toDateString(),
                        'status' => 'Aktif',
                    ]);
                }
            } else {
                // Jika diubah dari magang ke role lain, opsional: hapus data magang
                // Di sistem legacy kita biarkan saja atau disesuaikan
            }
        });

        return redirect()->route('admin.users')->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Hapus User secara Permanen.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Hapus user (cascade akan otomatis menghapus profil magang & absensi terkait karena foreign key diset cascade)
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus secara permanen beserta seluruh riwayat terkait!');
    }
}
