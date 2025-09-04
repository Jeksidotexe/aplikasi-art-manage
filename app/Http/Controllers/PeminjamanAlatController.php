<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman_alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; // Pastikan Carbon di-import

class PeminjamanAlatController extends Controller
{
    // ... (metode getAnggotaByNim, getAlatById, index, data tetap sama) ...
    public function getAnggotaByNim($nim)
    {
        $anggota = User::where('nim', $nim)->where('role', 'anggota')->first();
        return $anggota ? response()->json($anggota) : response()->json(['message' => 'Anggota tidak ditemukan'], 404);
    }

    public function getAlatById($id)
    {
        $alat = Alat::with('bidang')->find($id);
        return $alat ? response()->json($alat) : response()->json(['message' => 'Alat tidak ditemukan'], 404);
    }

    public function index()
    {
        $alat = Alat::where('jumlah', '>', 0)->orderBy('nama_alat')->get()->pluck('nama_alat', 'id_alat');
        return view('peminjaman_alat.index', compact('alat'));
    }

    public function data()
    {
        $query = Peminjaman_alat::with([
            'users' => fn($q) => $q->withDefault(['nama' => 'User Dihapus', 'nim' => '-']),
            'alat.bidang' => fn($q) => $q->withDefault(['nama_bidang' => '-']),
        ]);

        if (Auth::user()->role == 'anggota') {
            $query->where('id_users', Auth::id());
        }

        $query->latest();

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->filterColumn('nama', function ($query, $keyword) {
                $query->whereHas('users', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('nim', function ($query, $keyword) {
                $query->whereHas('users', function ($q) use ($keyword) {
                    $q->where('nim', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('nama_alat', function ($query, $keyword) {
                $query->whereHas('alat', function ($q) use ($keyword) {
                    $q->where('nama_alat', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('peminjaman_alat.status', 'like', "%{$keyword}%");
            })
            ->addColumn('select_all', fn($peminjaman) => '<input type="checkbox" name="id_peminjaman[]" value="' . $peminjaman->id_peminjaman . '">')
            ->addColumn('nama', fn($peminjaman) => $peminjaman->users->nama)
            ->addColumn('nim', fn($peminjaman) => $peminjaman->users->nim)
            ->addColumn('nama_alat', fn($peminjaman) => $peminjaman->alat->nama_alat ?? 'Alat Dihapus')
            ->addColumn('nama_bidang', fn($peminjaman) => $peminjaman->alat->bidang->nama_bidang)
            ->addColumn('tanggal_pinjam', function ($peminjaman) {
                // [BARU] Format tanggal pinjam, tampilkan '-' jika belum di-set
                return $peminjaman->tanggal_pinjam ? Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d M Y') : '-';
            })
            ->addColumn('status', function ($peminjaman) {
                $statusMap = [
                    'diajukan'     => 'badge badge-info',
                    'disetujui'    => 'badge badge-primary',
                    'dipinjam'     => 'badge badge-warning',
                    'dikembalikan' => 'badge badge-success',
                    'ditolak'      => 'badge badge-danger',
                ];
                $badgeClass = $statusMap[strtolower($peminjaman->status)] ?? 'badge bg-black';
                return '<span class="' . $badgeClass . '">' . ucfirst($peminjaman->status) . '</span>';
            })
            ->addColumn('aksi', function ($peminjaman) {
                if (Auth::user()->role == 'admin') {
                    $buttons = '<div class="d-flex gap-1">';
                    if ($peminjaman->status == 'diajukan') {
                        $buttons .= '<button type="button" onclick="approve(`' . route('peminjaman_alat.approve', $peminjaman->id_peminjaman) . '`)" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Setujui</button>';
                        $buttons .= ' <button type="button" onclick="reject(`' . route('peminjaman_alat.reject', $peminjaman->id_peminjaman) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Tolak</button>';
                    } elseif ($peminjaman->status == 'disetujui') {
                        $buttons .= '<button type="button" onclick="confirmPickup(`' . route('peminjaman_alat.confirm_pickup', $peminjaman->id_peminjaman) . '`)" class="btn btn-xs btn-primary"><i class="fa fa-handshake"></i> Konfirmasi Ambil</button>';
                    } elseif ($peminjaman->status == 'dipinjam') {
                        $buttons .= '<button type="button" onclick="processReturn(`' . route('peminjaman_alat.process_return', $peminjaman->id_peminjaman) . '`)" class="btn btn-xs btn-info"><i class="fa fa-undo"></i> Proses Kembali</button>';
                    }
                    $buttons .= ' <button type="button" onclick="detailForm(`' . route('peminjaman_alat.show', $peminjaman->id_peminjaman) . '`)" class="btn btn-xs btn-secondary"><i class="fa fa-eye"></i></button>';
                    $buttons .= ' <button type="button" onclick="editForm(`' . route('peminjaman_alat.show', $peminjaman->id_peminjaman) . '`, `' . route('peminjaman_alat.update', $peminjaman->id_peminjaman) . '`)" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></button>';
                    $buttons .= ' <button type="button" onclick="deleteData(`' . route('peminjaman_alat.destroy', $peminjaman->id_peminjaman) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
                    $buttons .= '</div>';
                    return $buttons;
                }
                return '<button type="button" onclick="detailForm(`' . route('peminjaman_alat.show', $peminjaman->id_peminjaman) . '`)" class="btn btn-xs btn-secondary"><i class="fa fa-eye"></i> Detail</button>';
            })
            ->rawColumns(['aksi', 'select_all', 'status'])
            ->make(true);
    }

    /**
     * [BARU] Mengambil data user untuk Select2 AJAX
     */
    public function getUsers(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $users = User::where('role', 'anggota')->orderBy('nama')->limit(10)->get();
        } else {
            $users = User::where('role', 'anggota')
                ->where(function ($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nim', 'like', '%' . $search . '%');
                })
                ->orderBy('nama')->limit(10)->get();
        }

        $response = [];
        foreach ($users as $user) {
            $response[] = [
                'id' => $user->id,
                'text' => $user->nama . ' - ' . $user->nim
            ];
        }
        return response()->json($response);
    }

    /**
     * [DIUBAH TOTAL] Method store sekarang menangani admin dan anggota,
     * serta fitur "langsung pinjam" untuk admin.
     */
    public function store(Request $request)
    {
        $isAdmin = Auth::user()->role == 'admin';

        // Aturan validasi dasar
        $rules = [
            'id_alat' => 'required|exists:alat,id_alat',
            'jumlah_pinjam' => 'required|integer|min:1',
        ];

        // Pesan error custom
        $messages = [
            'id_alat.required' => 'Anda harus memilih alat yang akan dipinjam.',
            'jumlah_pinjam.required' => 'Jumlah pinjam tidak boleh kosong.',
        ];

        // Tambahan validasi jika yang input adalah admin
        if ($isAdmin) {
            $rules['id_users'] = 'required|exists:users,id_users';

            $rules['status'] = 'required|in:diajukan,dipinjam';
            $messages['id_users.required'] = 'Anda harus memilih anggota peminjam.';
        }

        $request->validate($rules, $messages);

        $alat = Alat::findOrFail($request->id_alat);

        // Cek ketersediaan stok
        if ($request->jumlah_pinjam > $alat->jumlah) {
            return response()->json(['message' => 'Gagal, jumlah pinjam melebihi stok yang tersedia.'], 422);
        }

        $data = $request->all();
        $peminjamId = $isAdmin ? $request->id_users : Auth::id();
        $data['id_users'] = $peminjamId;

        // Logika "Langsung Pinjam" untuk Admin
        if ($isAdmin && $request->status == 'dipinjam') {
            $alat->jumlah -= $request->jumlah_pinjam;
            $alat->save();

            $data['tanggal_pinjam'] = now();
            $data['tanggal_harus_kembali'] = now()->addDays(2);
            $message = 'Data peminjaman berhasil ditambahkan dan alat langsung berstatus dipinjam.';
        } else {
            $data['status'] = 'diajukan';
            $message = $isAdmin
                ? 'Data peminjaman berhasil ditambahkan dengan status diajukan.'
                : 'Pengajuan peminjaman berhasil dikirim. Mohon tunggu verifikasi admin.';
        }

        Peminjaman_alat::create($data);
        return response()->json($message, 200);
    }

    /**
     * [BARU] Menambahkan method update untuk memproses data editan.
     */
    public function update(Request $request, $id)
    {
        // Validasi mirip seperti store, tapi sesuaikan jika ada field yang tidak boleh diubah
        $request->validate([
            'id_alat' => 'required|exists:alat,id_alat',
            'jumlah_pinjam' => 'required|integer|min:1',
        ], [
            'nama_bidang.required' => 'Harap pilih alat!',
            'jumlah.required' => 'Harap masukkan jumlah pinjam!'
        ]);

        $peminjaman = Peminjaman_alat::findOrFail($id);
        $alat = Alat::findOrFail($request->id_alat);

        // Cek stok, dengan memperhitungkan jumlah yang sudah dipinjam di record ini
        $stokTersedia = $alat->jumlah + $peminjaman->jumlah_pinjam;
        if ($request->jumlah_pinjam > $stokTersedia) {
            return response()->json(['message' => 'Jumlah pinjam melebihi stok yang tersedia.'], 422);
        }

        $peminjaman->update($request->all());

        return response()->json('Data berhasil diperbarui.', 200);
    }

    // ... (metode approve dan reject tidak berubah) ...
    public function approve($id)
    {
        $peminjaman = Peminjaman_alat::findOrFail($id);
        $alat = Alat::findOrFail($peminjaman->id_alat);

        if ($peminjaman->jumlah_pinjam > $alat->jumlah) {
            $peminjaman->status = 'ditolak';
            $peminjaman->keterangan_admin = 'Stok tidak mencukupi saat permintaan akan disetujui.';
            $peminjaman->save();
            return response()->json(['message' => 'Gagal! Stok alat tidak mencukupi.'], 422);
        }

        $peminjaman->status = 'disetujui';
        $peminjaman->keterangan_admin = 'Pengajuan Anda telah disetujui. Alat dapat diambil di lokasi.';
        $peminjaman->save();

        return response()->json('Peminjaman berhasil disetujui.', 200);
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['alasan_penolakan' => 'required|string|min:5']);
        $peminjaman = Peminjaman_alat::findOrFail($id);
        $peminjaman->status = 'ditolak';
        $peminjaman->keterangan_admin = $request->alasan_penolakan;
        $peminjaman->save();
        return response()->json('Peminjaman telah ditolak.', 200);
    }


    /**
     * [PERUBAHAN] Konfirmasi pengambilan alat oleh Admin.
     * Di sinilah tanggal pinjam dan tanggal harus kembali di-set.
     */
    public function confirmPickup($id)
    {
        $peminjaman = Peminjaman_alat::findOrFail($id);

        if ($peminjaman->status !== 'disetujui') {
            return response()->json(['message' => 'Status peminjaman tidak valid untuk aksi ini.'], 422);
        }

        $alat = Alat::findOrFail($peminjaman->id_alat);

        if ($peminjaman->jumlah_pinjam > $alat->jumlah) {
            return response()->json(['message' => 'Gagal! Stok alat ternyata sudah tidak mencukupi.'], 422);
        }

        $alat->jumlah -= $peminjaman->jumlah_pinjam;
        $alat->save();

        // [LOGIKA BARU] Set tanggal pinjam dan tanggal harus kembali saat ini juga
        $peminjaman->status = 'dipinjam';
        $peminjaman->tanggal_pinjam = now(); // Set tanggal pinjam ke waktu sekarang
        $peminjaman->tanggal_harus_kembali = now()->addDays(2); // Set jatuh tempo 2 hari dari sekarang
        $peminjaman->save();

        return response()->json('Alat telah dikonfirmasi diambil oleh peminjam.', 200);
    }

    // ... (sisa controller: processReturn, show, destroy, cetakPeminjaman tidak berubah) ...
    public function processReturn($id)
    {
        $peminjaman = Peminjaman_alat::findOrFail($id);
        if ($peminjaman->status !== 'dipinjam') {
            return response()->json(['message' => 'Status peminjaman tidak valid untuk aksi ini.'], 422);
        }
        $peminjaman->status = 'dikembalikan';
        $peminjaman->tanggal_kembali = now();
        $peminjaman->save();

        $alat = Alat::find($peminjaman->id_alat);
        if ($alat) {
            $alat->jumlah += $peminjaman->jumlah_pinjam;
            $alat->save();
        }
        return response()->json('Alat telah dikembalikan dan stok diperbarui.', 200);
    }

    public function show($id)
    {
        $peminjaman_alat = Peminjaman_alat::with(['users.jurusan', 'users.prodi', 'alat.bidang'])->findOrFail($id);
        return response()->json($peminjaman_alat);
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman_alat::find($id);
        if (in_array($peminjaman->status, ['disetujui', 'dipinjam'])) {
            $alat = Alat::find($peminjaman->id_alat);
            if ($alat) {
                $alat->jumlah += $peminjaman->jumlah_pinjam;
                $alat->save();
            }
        }
        $peminjaman->delete();
        return response(null, 204);
    }

    public function cetakPeminjaman(Request $request)
    {
        $peminjaman_alat = Peminjaman_alat::with(['users', 'alat.bidang'])
            ->whereIn('id_peminjaman', $request->id_peminjaman)
            ->get();
        $pdf = Pdf::loadView('peminjaman_alat.cetak', compact('peminjaman_alat'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-peminjaman-alat.pdf');
    }
}
