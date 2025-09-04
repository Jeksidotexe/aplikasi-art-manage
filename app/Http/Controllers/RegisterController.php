<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bidang;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $bidang = Bidang::orderBy('nama_bidang')->get();
        return view('auth.login_register', compact('jurusan', 'bidang'));
    }

    /**
     * [BARU] Menampilkan halaman setelah email berhasil diverifikasi.
     */
    public function showVerifiedNotice()
    {
        return view('auth.verified');
    }

    public function register(Request $request)
    {
        // [PERUBAHAN] Menambahkan validasi foto dan pesan custom
        $request->validate([
            'nim'           => 'required|digits:10|unique:users,nim',
            'nama'          => 'required|string|max:255',
            'id_jurusan'    => 'required|exists:jurusan,id_jurusan',
            'id_prodi'      => 'required|exists:prodi,id_prodi',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'no_telepon'    => ['required', 'max:15', 'regex:/^(?:\+62|62|0)8[1-9][0-9]{7,11}$/'],
            'id_bidang'     => 'required|exists:bidang,id_bidang',
            'foto'          => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk foto
        ], [
            // [BARU] Pesan error validasi custom
            'nim.required' => 'NIM wajib diisi.',
            'nim.digits' => 'NIM harus terdiri dari 10 digit angka.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'id_jurusan.required' => 'Jurusan wajib dipilih.',
            'id_prodi.required' => 'Prodi wajib dipilih.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid (contoh: 0812...).',
            'id_bidang.required' => 'Bidang minat wajib dipilih.',
            'foto.required' => 'Foto profil wajib diunggah.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            try {
                $file = $request->file('foto');
                // Format nama file: user_YYYY-MM-DD.extensi
                $namaFile = 'user_' . date('Y-m-d') . '.' . $file->getClientOriginalExtension();
                // Simpan ke public/images/
                $path = $file->move(public_path('images'), $namaFile);
                // Dapatkan path relatif untuk disimpan di database
                $path = 'images/' . $namaFile;
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan file: ' . $e->getMessage());
                // Kembali dengan error jika upload gagal
                return back()->withInput()->withErrors(['foto' => 'Gagal mengunggah foto.']);
            }
        }

        $user = User::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'id_jurusan' => $request->id_jurusan,
            'id_prodi' => $request->id_prodi,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'id_bidang' => $request->id_bidang,
            'foto' => $path, // [PERUBAHAN] Simpan path foto yang diupload
            'role' => 'anggota',
            'status' => 'pending',
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Silakan periksa email Anda untuk verifikasi.');
    }
}
