<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusan = Jurusan::all()->pluck('nama_jurusan', 'id_jurusan');
        return view('prodi.index', compact('jurusan'));
    }

    public function data()
    {
        // UBAH BAGIAN INI: Gunakan 'with' untuk mengambil relasi jurusan
        $prodi = Prodi::with('jurusan')->orderBy('nama_prodi', 'asc')->get();

        return datatables()
            ->of($prodi)
            ->addIndexColumn()
            // TAMBAHKAN KOLOM BARU UNTUK NAMA JURUSAN
            ->addColumn('nama_jurusan', function ($prodi) {
                return $prodi->jurusan->nama_jurusan;
            })
            ->addColumn('aksi', function ($prodi) {
                return '
                <div class="d-flex gap-1">
                    <button onclick="editForm(`' . route('prodi.update', $prodi->id_prodi) . '`)" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></button>
                    <button " onclick="deleteData(`' . route('prodi.destroy', $prodi->id_prodi) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // UBAH BAGIAN INI: Tambahkan validasi untuk id_jurusan
        $request->validate([
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'nama_prodi' => 'required'
        ], [
            'id_jurusan.required' => 'Harap pilih jurusan!',
            'id_jurusan.exists' => 'Jurusan tidak valid!',
            'nama_prodi.required' => 'Harap masukkan nama program studi!'
        ]);

        $prodi = new Prodi();
        $prodi->id_jurusan = $request->id_jurusan; // TAMBAHKAN INI
        $prodi->nama_prodi = $request->nama_prodi;
        $prodi->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id_prodi)
    {
        $prodi = Prodi::find($id_prodi);
        return response()->json($prodi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_prodi)
    {
        // UBAH BAGIAN INI: Tambahkan validasi untuk id_jurusan
        $request->validate([
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'nama_prodi' => 'required'
        ], [
            'id_jurusan.required' => 'Harap pilih jurusan!',
            'id_jurusan.exists' => 'Jurusan tidak valid!',
            'nama_prodi.required' => 'Harap masukkan nama program studi!'
        ]);

        $prodi = Prodi::find($id_prodi);
        $prodi->id_jurusan = $request->id_jurusan; // TAMBAHKAN INI
        $prodi->nama_prodi = $request->nama_prodi;
        $prodi->update();

        return response()->json('Data berhasil diperbarui', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_prodi)
    {
        $prodi = Prodi::find($id_prodi);
        $prodi->delete();

        return response(null, 204);
    }
}
