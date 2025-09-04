<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Agenda_kegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AgendaKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bidang = Bidang::all()->pluck('nama_bidang', 'id_bidang');
        return  view('agenda_kegiatan.index', compact('bidang'));
    }

    public function data()
    {
        $agenda_kegiatan = Agenda_kegiatan::leftJoin('bidang', 'bidang.id_bidang', '=', 'agenda_kegiatan.id_bidang')
            ->select('agenda_kegiatan.*', 'bidang.nama_bidang')
            ->get();

        return datatables()
            ->of($agenda_kegiatan)
            ->addIndexColumn()
            ->addColumn('file_sk', function ($agenda_kegiatan) {
                if ($agenda_kegiatan->file_sk && file_exists(public_path('files/' . $agenda_kegiatan->file_sk))) {
                    $url = route('agenda_kegiatan.preview', ['filename' => $agenda_kegiatan->file_sk]);
                    $icon = asset('images/pdf-icon.png'); // Gambar ikon PDF

                    return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">
                    <img src="' . $icon . '" alt="PDF" width="30" height="30">
                </a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('select_all', function ($agenda_kegiatan) {
                return '
                    <input type="checkbox" name="id_agenda[]" value="' . $agenda_kegiatan->id_agenda . '">
                ';
            })
            ->addColumn('aksi', function ($agenda_kegiatan) {
                if (Auth::user()->role == 'admin') {
                    return '
                    <div class="d-flex gap-1">
                        <button type="button" onclick="editForm(`' . route('agenda_kegiatan.update', $agenda_kegiatan->id_agenda) . '`)" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="deleteData(`' . route('agenda_kegiatan.destroy', $agenda_kegiatan->id_agenda) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                    </div>
                ';
                } else {
                    return '-';
                }
            })
            ->rawColumns(['aksi', 'file_sk', 'select_all'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_bidang' => 'required',
            'nama_kegiatan' => 'required',
            'tanggal' => 'required',
            'lokasi' => 'required',
            'keterangan' => 'required',
            'file_sk' => 'required|mimes:pdf|max:2048',
        ], [
            'id_bidang.required' => 'Harap pilih bidang!',
            'nama_kegiatan.required' => 'Harap masukkan nama kegiatan!',
            'tanggal.required' => 'Harap masukkan tanggal dan waktu!',
            'lokasi.required' => 'Harap masukkan lokasi!',
            'keterangan.required' => 'Harap masukkan keterangan!',
            'file_sk.required' => 'Harap upload file surat keputusan!',
            'file_sk.mimes' => 'Harap upload file bertipe PDF!',
            'file_sk.max' => 'File melebihi batas ukuran!(max:2MB)',
        ]);

        $agenda_kegiatan = new Agenda_kegiatan();
        $agenda_kegiatan->id_bidang = $request->id_bidang;
        $agenda_kegiatan->nama_kegiatan = $request->nama_kegiatan;
        $agenda_kegiatan->tanggal = $request->tanggal;
        $agenda_kegiatan->lokasi = $request->lokasi;
        $agenda_kegiatan->keterangan = $request->keterangan;

        if ($request->hasFile('file_sk')) {
            $file = $request->file('file_sk');
            $customName = 'SK_' . now()->format('Ymd_His') . '_' . Str::slug($request->nama_kegiatan, '_') . '.pdf';

            try {
                $file->move(public_path('files'), $customName);
                $agenda_kegiatan->file_sk = $customName;
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal menyimpan file: ' . $e->getMessage()
                ], 500);
            }
        }

        $agenda_kegiatan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id_agenda)
    {
        $agenda_kegiatan = Agenda_kegiatan::find($id_agenda);

        return response()->json($agenda_kegiatan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_agenda)
    {

        $request->validate([
            'id_bidang' => 'required',
            'nama_kegiatan' => 'required',
            'tanggal' => 'required',
            'lokasi' => 'required',
            'keterangan' => 'required',
            'file_sk' => 'nullable|mimes:pdf|max:2048',
        ], [
            'id_bidang.required' => 'Harap pilih bidang!',
            'nama_kegiatan.required' => 'Harap masukkan nama kegiatan!',
            'tanggal.required' => 'Harap masukkan tanggal dan waktu!',
            'lokasi.required' => 'Harap masukkan lokasi!',
            'keterangan.required' => 'Harap masukkan keterangan!',
            'file_sk.mimes' => 'Harap upload file bertipe PDF!',
            'file_sk.max' => 'File melebihi batas ukuran!(max:2MB)',
        ]);

        $agenda_kegiatan = Agenda_kegiatan::find($id_agenda);
        $agenda_kegiatan->id_bidang = $request->id_bidang;
        $agenda_kegiatan->nama_kegiatan = $request->nama_kegiatan;
        $agenda_kegiatan->tanggal = $request->tanggal;
        $agenda_kegiatan->lokasi = $request->lokasi;
        $agenda_kegiatan->keterangan = $request->keterangan;
        if ($request->hasFile('file_sk')) {
            // Hapus file lama jika ada
            $oldFile = public_path('files/' . $agenda_kegiatan->file_sk);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }

            $file = $request->file('file_sk');
            $customName = 'SK_' . now()->format('Ymd_His') . '_' . Str::slug($request->nama_kegiatan, '_') . '.pdf';

            try {
                $file->move(public_path('files'), $customName);
                $agenda_kegiatan->file_sk = $customName;
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal menyimpan file: ' . $e->getMessage()
                ], 500);
            }
        }
        $agenda_kegiatan->update();

        return response()->json('Data berhasil diperbarui', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_agenda)
    {
        $agenda_kegiatan = Agenda_kegiatan::find($id_agenda);
        if ($agenda_kegiatan->file_sk && file_exists(public_path('files/' . $agenda_kegiatan->file_sk))) {
            unlink(public_path('files/' . $agenda_kegiatan->file_sk));
        }
        $agenda_kegiatan->delete();

        return response(null, 204);
    }

    public function cetakAgenda(Request $request)
    {
        $agenda_kegiatan = Agenda_kegiatan::with(['bidang'])
            ->whereIn('id_agenda', $request->id_agenda)
            ->get();

        $pdf = Pdf::loadView('agenda_kegiatan.cetak', compact('agenda_kegiatan'));
        return $pdf->stream('agenda_kegiatan.pdf');
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
