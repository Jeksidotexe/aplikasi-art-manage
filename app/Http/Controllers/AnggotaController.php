<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Bidang;
use App\Models\Jurusan;
use Illuminate\Support\Str;
use App\Mail\MemberApproved;
use App\Mail\MemberRejected;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // [BARU] Ambil data anggota yang pendaftarannya pending
        $pending_anggota = User::where('role', 'anggota')
            ->where('status', 'pending')
            ->whereNotNull('email_verified_at') // Hanya tampilkan yang sudah verifikasi email
            ->orderBy('created_at', 'asc')
            ->get();

        $jurusan = Jurusan::all()->pluck('nama_jurusan', 'id_jurusan');
        $bidang = Bidang::all()->pluck('nama_bidang', 'id_bidang');

        // Kirim data pending ke view
        return view('anggota.index', compact('jurusan', 'bidang', 'pending_anggota'));
    }

    public function data()
    {
        // [PERUBAHAN] Hanya tampilkan anggota yang sudah 'active' di DataTables
        $anggota = User::with(['jurusan', 'prodi', 'bidang'])
            ->where('role', 'anggota')
            ->where('status', 'active') // <-- Filter utama
            ->orderBy('tanggal_daftar', 'desc');

        return datatables()
            ->of($anggota)
            ->addIndexColumn()
            ->addColumn('select_all', function ($anggota) {
                return '<input type="checkbox" name="id_users[]" value="' . $anggota->id_users . '">';
            })
            // [TAMBAHAN] Menampilkan foto di DataTables
            ->addColumn('foto', function ($anggota) {
                $url = asset($anggota->foto ?? 'assets/images/default-profile.jpg');
                return '<img src="' . $url . '" alt="foto" class="avatar-sm rounded-circle">';
            })
            ->addColumn('nama_jurusan', function ($anggota) {
                return $anggota->jurusan->nama_jurusan ?? '-';
            })
            ->addColumn('nama_prodi', function ($anggota) {
                return $anggota->prodi->nama_prodi ?? '-';
            })
            ->addColumn('nama_bidang', function ($anggota) {
                return $anggota->bidang->nama_bidang ?? '-';
            })
            ->addColumn('aksi', function ($anggota) {
                return '
                <div class="d-flex gap-1">
                    <button type="button" onclick="editForm(`' . route('anggota.update', $anggota->id_users) . '`)" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></button>
                    <button type="button" onclick="deleteData(`' . route('anggota.destroy', $anggota->id_users) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'foto'])
            ->make(true);
    }

    /**
     * Mengambil data prodi berdasarkan jurusan yang dipilih untuk AJAX.
     */
    public function getProdi(Request $request)
    {
        $prodi = Prodi::where('id_jurusan', $request->id_jurusan)->orderBy('nama_prodi')->get();
        return response()->json($prodi);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|digits:10|unique:users,nim',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telepon' => ['required', 'max:15', 'regex:/^(?:\+62|62|0)8[1-9][0-9]{7,11}$/'],
            'id_bidang' => 'required|exists:bidang,id_bidang',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.digits' => 'NIM harus terdiri dari 10 digit.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'id_jurusan.required' => 'Jurusan wajib dipilih.',
            'id_prodi.required' => 'Program Studi wajib dipilih.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid (Contoh: 08123456789).',
            'id_bidang.required' => 'Bidang wajib dipilih.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'user_' . $request->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->move(public_path('images/anggota'), $namaFile);
            $path = 'images/anggota/' . $namaFile;
        }

        $anggota = new User();
        $anggota->fill($request->except('password', 'foto'));

        $anggota->nim = $request->nim;
        $anggota->id_jurusan = $request->id_jurusan;
        $anggota->id_prodi = $request->id_prodi;
        $anggota->nama = $request->nama;
        $anggota->email = $request->email;
        $anggota->email_verified_at = now();
        $anggota->password = bcrypt($request->password);
        $anggota->no_telepon = $request->no_telepon;
        $anggota->id_bidang = $request->id_bidang;
        $anggota->tanggal_daftar = now();
        $anggota->foto = $path;
        $anggota->role = 'anggota';
        $anggota->status = 'active';
        $anggota->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id_users)
    {
        $anggota = User::where('id_users', $id_users)->where('role', 'anggota')->firstOrFail();
        // [PERUBAHAN] Tambahkan URL foto ke response
        $anggota->foto_url = $anggota->foto ? asset($anggota->foto) : asset('assets/images/default-profile.jpg');
        return response()->json($anggota);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_users)
    {
        $anggota = User::where('id_users', $id_users)->where('role', 'anggota')->firstOrFail();

        // [PERUBAHAN] Logika dan pesan validasi custom untuk update
        $rules = [
            'nim' => 'required|digits:10|unique:users,nim,' . $id_users . ',id_users',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $id_users . ',id_users',
            'no_telepon' => ['required', 'max:15', 'regex:/^(?:\+62|62|0)8[1-9][0-9]{7,11}$/'],
            'id_bidang' => 'required|exists:bidang,id_bidang',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk foto
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $messages = [
            'nim.required' => 'NIM wajib diisi.',
            'nim.digits' => 'NIM harus terdiri dari 10 digit.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'id_jurusan.required' => 'Jurusan wajib dipilih.',
            'id_prodi.required' => 'Program Studi wajib dipilih.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid.',
            'id_bidang.required' => 'Bidang wajib dipilih.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ];

        $request->validate($rules, $messages);

        $anggota->fill($request->except('password', 'foto'));

        // [PERUBAHAN] Logika untuk update foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($anggota->foto && File::exists(public_path($anggota->foto))) {
                File::delete(public_path($anggota->foto));
            }
            $file = $request->file('foto');
            $namaFile = 'user_' . $request->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->move(public_path('images/anggota'), $namaFile);
            $anggota->foto = 'images/anggota/' . $namaFile;
        }

        if ($request->filled('password')) {
            $anggota->password = bcrypt($request->password);
        }

        $anggota->save();

        return response()->json('Data berhasil diperbarui', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_users)
    {
        $anggota = User::findOrFail($id_users);

        if ($anggota->peminjamanAlat()->exists()) {
            return response()->json([
                'message' => 'Data anggota ini tidak dapat dihapus karena memiliki riwayat peminjaman alat.'
            ], 422);
        }

        if ($anggota->foto && File::exists(public_path($anggota->foto))) {
            File::delete(public_path($anggota->foto));
        }

        $anggota->delete();

        return response(null, 204);
    }

    public function cetakAnggota(Request $request)
    {
        $anggota = User::with(['bidang', 'jurusan', 'prodi'])
            ->whereIn('id_users', $request->id_users)
            ->where('role', 'anggota')
            ->get();
        $pdf = Pdf::loadView('anggota.cetak', compact('anggota'));
        return $pdf->stream('anggota.pdf');
    }
}
