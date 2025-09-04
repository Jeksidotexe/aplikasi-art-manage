<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function profil()
    {
        $profil = Auth::user();
        return view('user.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $messages = [
            'nama.required' => 'Harap masukkan nama!',
            'email.email' => 'Format email tidak valid!',
            'email.required' => 'Harap masukkan email!',
            'old_password.required_with' => 'Password lama harus diisi jika ingin mengganti password.',
            'old_password.min' => 'Password lama minimal 6 karakter.',
            'password.min' => 'Masukkan password minimal 6 karakter!',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Gambar harus bertipe jpeg, png, jpg, gif, atau svg.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ];

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
            'old_password' => 'nullable|min:6|required_with:password',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], $messages);



        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        /** @var \App\Models\User $user */

        $user = Auth::user();
        $user->nama = $request->nama;
        $user->email = $request->email;

        if ($request->filled('password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'errors' => ['old_password' => ['Password lama tidak sesuai!.']]
                ], 422);
            }

            if ($request->password !== $request->password_confirmation) {
                return response()->json([
                    'errors' => ['password_confirmation' => ['Konfirmasi password tidak cocok dengan password baru!']]
                ], 422);
            }

            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            $file = $request->file('foto');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            $user->foto = 'images/' . $fileName;
        }

        $user->save();

        return response()->json(['message' => 'Profil berhasil diperbarui'], 200);
    }
}
