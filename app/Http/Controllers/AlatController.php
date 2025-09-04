<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Bidang;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bidang = Bidang::all()->pluck('nama_bidang', 'id_bidang');
        return view('alat.index', compact('bidang'));
    }

    public function data()
    {
        $alat = Alat::leftJoin('bidang', 'bidang.id_bidang', '=', 'alat.id_bidang')
            ->select('alat.*', 'bidang.nama_bidang')
            ->get();

        return datatables()
            ->of($alat)
            ->addIndexColumn()
            ->editColumn('nama_bidang', function ($row) {
                return ucfirst(strtolower($row->nama_bidang));
            })
            ->editColumn('nama_alat', function ($row) {
                return ucfirst(strtolower($row->nama_alat));
            })
            ->addColumn('kondisi', function ($alat) {
                $badgeClass = match (strtolower($alat->kondisi)) {
                    'baik' => 'badge badge-success',
                    'rusak' => 'badge badge-danger',
                    'perlu perbaikan' => 'badge badge-warning',
                    default => 'badge bg-black'
                };

                return '<span class="' . $badgeClass . '">' . ucfirst($alat->kondisi) . '</span>';
            })
            ->addColumn('aksi', function ($alat) {
                return '
                <div class="d-flex gap-1">
                    <button onclick="editForm(`' . route('alat.update', $alat->id_alat) . '`)" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteData(`' . route('alat.destroy', $alat->id_alat) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['nama_alat', 'kondisi', 'aksi'])
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
            'nama_alat' => 'required',
            'merk' => 'required',
            'jumlah' => 'required',
            'tanggal_beli' => 'required',
            'kondisi' => 'required'
        ], [
            'id_bidang.required' => 'Harap pilih bidang!',
            'nama_alat.required' => 'Harap masukkan nama alat musik!',
            'merk.required' => 'Harap masukkan merk alat musik!',
            'jumlah.required' => 'Harap masukkan jumlah alat musik!',
            'tanggal_beli.required' => 'Harap masukkan tanggal pembelian alat musik!',
            'kondisi.required' => 'Harap pilih kondisi alat musik!'
        ]);

        $alat = new Alat();
        $alat->id_bidang = $request->id_bidang;
        $alat->nama_alat = $request->nama_alat;
        $alat->merk = $request->merk;
        $alat->jumlah = $request->jumlah;
        $alat->tanggal_beli = $request->tanggal_beli;
        $alat->kondisi = $request->kondisi;
        $alat->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id_alat)
    {
        $alat = Alat::find($id_alat);

        return response()->json($alat);
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
    public function update(Request $request, $id_alat)
    {

        $request->validate([
            'id_bidang' => 'required',
            'nama_alat' => 'required',
            'merk' => 'required',
            'jumlah' => 'required',
            'tanggal_beli' => 'required',
            'kondisi' => 'required'
        ], [
            'id_bidang.required' => 'Harap masukkan pilih bidang!',
            'nama_alat.required' => 'Harap masukkan nama alat musik!',
            'merk.required' => 'Harap masukkan merk alat musik!',
            'jumlah.required' => 'Harap masukkan jumlah alat musik!',
            'tanggal_beli.required' => 'Harap masukkan tanggal pembelian alat musik!',
            'kondisi.required' => 'Harap pilih kondisi alat musik!'
        ]);

        $alat = Alat::find($id_alat);
        $alat->id_bidang = $request->id_bidang;
        $alat->nama_alat = $request->nama_alat;
        $alat->merk = $request->merk;
        $alat->jumlah = $request->jumlah;
        $alat->tanggal_beli = $request->tanggal_beli;
        $alat->kondisi = $request->kondisi;
        $alat->update();

        return response()->json('Data berhasil diperbarui', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_alat)
    {
        $alat = Alat::find($id_alat);
        $alat->delete();

        return response(null, 204);
    }
}
