<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('jurusan.index');
    }

    public function data()
    {
        $jurusan = Jurusan::orderBy('id_jurusan', 'desc')->get();

        return datatables()
            ->of($jurusan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jurusan) {
                return '
                <div class="d-flex gap-1">
                    <button onclick="editForm(`' . route('jurusan.update', $jurusan->id_jurusan) . '`)" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteData(`' . route('jurusan.destroy', $jurusan->id_jurusan) . '`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
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
            'nama_jurusan' => 'required'
        ], [
            'nama_jurusan.required' => 'Harap masukkan nama jurusan!'
        ]);

        $jurusan = new Jurusan();
        $jurusan->nama_jurusan = $request->nama_jurusan;
        $jurusan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id_jurusan)
    {
        $jurusan = Jurusan::find($id_jurusan);

        return response()->json($jurusan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_jurusan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_jurusan)
    {

        $request->validate([
            'nama_jurusan' => 'required'
        ], [
            'nama_jurusan.required' => 'Harap masukkan nama jurusan!'
        ]);

        $jurusan = Jurusan::find($id_jurusan);
        $jurusan->nama_jurusan = $request->nama_jurusan;
        $jurusan->update();

        return response()->json('Data berhasil diperbarui', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_jurusan)
    {
        $jurusan = Jurusan::find($id_jurusan);
        $jurusan->delete();

        return response(null, 204);
    }
}
