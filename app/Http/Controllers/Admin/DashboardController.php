<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Qr;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Admin/Mentor.
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Makassar')->toDateString();
        $nowString = Carbon::now('Asia/Makassar')->toDateTimeString();

        // 1. Ambil data metrik statistik
        $stats = [
            'total_users' => User::count(),
            'total_qr' => Qr::count(),
            'today_attendance' => Absensi::whereDate('created_at', $today)->count(),
            'active_qr' => Qr::where('expired_at', '>', $nowString)->count(),
        ];

        // 2. Ambil 5 user terbaru
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        // 3. Ambil 5 QR code terbaru
        $recentQrs = Qr::orderBy('created_at', 'desc')->take(5)->get();

        // 4. Ambil 10 live absensi aktivitas terbaru
        $recentAttendance = Absensi::with(['user', 'qr'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id_absensi', 'desc')
            ->take(10)
            ->get();

        // Format timestamps & parameters
        $avatarInitials = strtoupper(substr($user->username, 0, 2));

        return view('admin.dashboard', [
            'username' => $user->username,
            'role' => $user->role,
            'avatarInitials' => $avatarInitials,
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentQrs' => $recentQrs,
            'recentAttendance' => $recentAttendance,
        ]);
    }
}
