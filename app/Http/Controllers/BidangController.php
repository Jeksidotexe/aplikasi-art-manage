<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bidang.index');
    }

    public function data()
    {
        $bidang = Bidang::orderBy('id_bidang', 'desc')->get();

        return datatables()
            ->of($bidang)
            ->addIndexColumn()
            ->addColumn('aksi', function ($bidang) {
                return '
                <div class="d-flex gap-1">
                    <button onclick="editForm(`' . route('bidang.update', $bidang->id_bidang) . '`)" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteData(`' . route('bidang.destroy', $bidang->id_bidang) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
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
            'nama_bidang' => 'required',
            'deskripsi' => 'required'
        ], [
            'nama_bidang.required' => 'Harap masukkan nama bidang!',
            'deskripsi.required' => 'Harap masukkan deskripsi bidang!'
        ]);

        $bidang = new Bidang();
        $bidang->nama_bidang = $request->nama_bidang;
        $bidang->deskripsi = $request->deskripsi;
        $bidang->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id_bidang)
    {
        $bidang = Bidang::find($id_bidang);

        return response()->json($bidang);
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
    public function update(Request $request, $id_bidang)
    {

        $request->validate([
            'nama_bidang' => 'required',
            'deskripsi' => 'required'
        ], [
            'nama_bidang.required' => 'Harap masukkan nama bidang!',
            'deskripsi.required' => 'Harap masukkan deskripsi bidang!'
        ]);

        $bidang = Bidang::find($id_bidang);
        $bidang->nama_bidang = $request->nama_bidang;
        $bidang->deskripsi = $request->deskripsi;
        $bidang->update();

        return response()->json('Data berhasil diperbarui', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_bidang)
    {
        $bidang = Bidang::find($id_bidang);
        $bidang->delete();

        return response(null, 204);
    }
}
