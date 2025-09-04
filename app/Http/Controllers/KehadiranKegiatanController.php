<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Agenda_kegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Kehadiran_kegiatan;

class KehadiranKegiatanController extends Controller
{
    /**
     * [BARU] Menambahkan metode untuk mengambil detail agenda.
     */
    public function getAgendaDetail($id_agenda)
    {
        // Cari agenda beserta relasi bidangnya
        $agenda = Agenda_kegiatan::with('bidang')->find($id_agenda);

        if ($agenda) {
            return response()->json($agenda);
        }

        return response()->json(['message' => 'Agenda tidak ditemukan'], 404);
    }

    public function index()
    {
        $agenda_kegiatan = Agenda_kegiatan::orderBy('nama_kegiatan')->get()->pluck('nama_kegiatan', 'id_agenda');
        $bidang = Bidang::orderBy('nama_bidang')->get()->pluck('nama_bidang', 'id_bidang');
        return view('kehadiran_kegiatan.index', compact('agenda_kegiatan', 'bidang'));
    }

    public function data()
    {
        $kehadiran_kegiatan = Kehadiran_kegiatan::leftJoin('agenda_kegiatan', 'agenda_kegiatan.id_agenda', '=', 'kehadiran_kegiatan.id_agenda')
            ->leftJoin('bidang', 'bidang.id_bidang', '=', 'kehadiran_kegiatan.id_bidang')
            ->select('kehadiran_kegiatan.*', 'agenda_kegiatan.nama_kegiatan', 'bidang.nama_bidang')
            ->get();


        return datatables()
            ->of($kehadiran_kegiatan)
            ->addIndexColumn()
            ->addColumn('file_absensi', function ($kehadiran_kegiatan) {
                if ($kehadiran_kegiatan->file_absensi && file_exists(public_path('files/' . $kehadiran_kegiatan->file_absensi))) {
                    $url = route('kehadiran_kegiatan.preview', ['filename' => $kehadiran_kegiatan->file_absensi]);
                    $icon = asset('images/pdf-icon.png'); // Gambar ikon PDF

                    return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">
                    <img src="' . $icon . '" alt="PDF" width="30" height="30">
                </a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('select_all', function ($kehadiran_kegiatan) {
                return '
                    <input type="checkbox" name="id_kehadiran[]" value="' . $kehadiran_kegiatan->id_kehadiran . '">
                ';
            })
            ->addColumn('aksi', function ($kehadiran_kegiatan) {
                return '
                <div class="d-flex gap-1">
                    <button type="button" onclick="editForm(`' . route('kehadiran_kegiatan.update', $kehadiran_kegiatan->id_kehadiran) . '`)" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></button>
                    <button type="button" onclick="deleteData(`' . route('kehadiran_kegiatan.destroy', $kehadiran_kegiatan->id_kehadiran) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'file_absensi', 'select_all'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_agenda' => 'required',
            'id_bidang' => 'required',
            'file_absensi' => 'required|mimes:pdf|max:2048',
        ], [
            'id_agenda.required' => 'Harap pilih kegiatan!',
            'id_bidang.required' => 'Harap pilih bidang!',
            'file_absensi.required' => 'Harap upload file absensi!',
            'file_absensi.mimes' => 'Harap upload file bertipe PDF!',
            'file_absensi.max' => 'File melebihi batas ukuran!(max:2MB)',
        ]);

        $kehadiran_kegiatan = new Kehadiran_kegiatan();
        $kehadiran_kegiatan->id_agenda = $request->id_agenda;
        $kehadiran_kegiatan->id_bidang = $request->id_bidang;
        if ($request->hasFile('file_absensi')) {
            $file = $request->file('file_absensi');
            $customName = 'Absensi_' . now()->format('Ymd_His') . '_' . Str::slug($request->nama_kegiatan, '_') . '.pdf';

            try {
                $file->move(public_path('files'), $customName);
                $kehadiran_kegiatan->file_absensi = $customName;
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal menyimpan file: ' . $e->getMessage()
                ], 500);
            }
        }
        $kehadiran_kegiatan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id_kehadiran)
    {
        $kehadiran_kegiatan = Kehadiran_kegiatan::find($id_kehadiran);

        return response()->json($kehadiran_kegiatan);
    }

    public function update(Request $request, $id_kehadiran)
    {
        $request->validate([
            'id_agenda' => 'required',
            'id_bidang' => 'required',
            'file_absensi' => 'nullable|mimes:pdf|max:2048',
        ], [
            'id_agenda.required' => 'Harap pilih kegiatan!',
            'id_bidang.required' => 'Harap pilih bidang!',
            'file_absensi.mimes' => 'Harap upload file bertipe PDF!',
            'file_absensi.max' => 'File melebihi batas ukuran!(max:2MB)',
        ]);

        $kehadiran_kegiatan = Kehadiran_kegiatan::find($id_kehadiran);
        $kehadiran_kegiatan->id_agenda = $request->id_agenda;
        $kehadiran_kegiatan->id_bidang = $request->id_bidang;
        if ($request->hasFile('file_absensi')) {
            // Hapus file lama jika ada
            $oldFile = public_path('files/' . $kehadiran_kegiatan->file_absensi);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }

            $file = $request->file('file_absensi');
            $customName = 'Absensi_' . now()->format('Ymd_His') . '_' . Str::slug($request->nama_kegiatan, '_') . '.pdf';

            try {
                $file->move(public_path('files'), $customName);
                $kehadiran_kegiatan->file_absensi = $customName;
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal menyimpan file: ' . $e->getMessage()
                ], 500);
            }
        }
        $kehadiran_kegiatan->update();

        return response()->json('Data berhasil diperbarui', 200);
    }

    public function destroy($id_kehadiran)
    {
        $kehadiran_kegiatan = Kehadiran_kegiatan::find($id_kehadiran);
        if ($kehadiran_kegiatan->file_absensi && file_exists(public_path('files/' . $kehadiran_kegiatan->file_absensi))) {
            unlink(public_path('files/' . $kehadiran_kegiatan->file_absensi));
        }
        $kehadiran_kegiatan->delete();

        return response(null, 204);
    }

    public function cetakKehadiran(Request $request)
    {
        $kehadiran_kegiatan = Kehadiran_kegiatan::with(['agenda_kegiatan', 'bidang'])
            ->whereIn('id_kehadiran', $request->id_kehadiran)
            ->get();

        $pdf = Pdf::loadView('kehadiran_kegiatan.cetak', compact('kehadiran_kegiatan'))
            ->setPaper('a4', 'landscape');;
        return $pdf->stream('kehadiran_kegiatan.pdf');
    }

    public function preview($filename)
    {
        $path = public_path('files/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
}
