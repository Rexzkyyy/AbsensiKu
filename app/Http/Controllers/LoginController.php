<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Proses autentikasi login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim($request->input('username'));
        $password = trim($request->input('password'));

        // Cari user berdasarkan username atau email
        $user = User::where('username', $input)
                    ->orWhere('email', $input)
                    ->first();

        if ($user) {
            // Cek password: plain text (legacy) ATAU hashed (Laravel standard)
            $passwordMatches = false;
            if ($password === $user->password) {
                $passwordMatches = true;
            } else {
                try {
                    if (Hash::check($password, $user->password)) {
                        $passwordMatches = true;
                    }
                } catch (\RuntimeException $e) {
                    // Abaikan exception jika password di database bukan format bcrypt (plain text)
                }
            }

            if ($passwordMatches) {
                // Loginkan user secara manual
                Auth::login($user, true);

                $request->session()->regenerate();

                // Redirect sesuai role
                return $this->redirectUserByRole($user);
            }
        }

        return back()->withErrors([
            'error' => 'Username atau password yang Anda masukkan salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Redirect helper berdasarkan role.
     */
    private function redirectUserByRole($user)
    {
        $role = strtolower(trim((string) $user->role));

        if ($role === 'mentor' || $role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'magang') {
            return redirect()->route('magang.dashboard');
        }

        Auth::logout();

        return redirect()->route('login')->withErrors([
            'error' => 'Role akun tidak valid. Hubungi admin untuk memperbaiki data user.',
        ]);
    }
}
