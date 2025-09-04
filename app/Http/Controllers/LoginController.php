<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login dan register.
     */
    public function showLoginForm()
    {
        // Data ini diperlukan untuk form registrasi di halaman yang sama
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $bidang = Bidang::orderBy('nama_bidang')->get();
        return view('auth.login_register', compact('jurusan', 'bidang'));
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        // 2. Coba lakukan otentikasi
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user(); // Dapatkan data pengguna yang berhasil login

            // 3. [PENTING] Periksa apakah email sudah terverifikasi
            if (!$user->hasVerifiedEmail()) {
                Auth::logout(); // Logout paksa jika email belum diverifikasi
                return back()->withErrors([
                    'email' => 'Email Anda belum diverifikasi. Silakan cek inbox Anda.'
                ])->withInput();
            }

            // 4. [PENTING] Periksa apakah status akun sudah 'active'
            if ($user->status !== 'active') {
                Auth::logout(); // Logout paksa jika status bukan active
                return back()->withErrors([
                    'email' => 'Akun Anda sedang menunggu persetujuan dari admin.'
                ])->withInput();
            }

            // 5. Jika semua pemeriksaan lolos, lanjutkan
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Selamat datang, ' . $user->nama . '!');
        }

        // Jika email/password salah dari awal
        return back()->withErrors([
            'email' => 'Kombinasi email dan password salah.',
        ])->withInput();
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
