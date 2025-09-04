<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal-form-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" method="post" class="modal-content">
            @csrf
            @method('post')

            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="id_jurusan" class="col-sm-3 col-form-label">Jurusan</label>
                    <div class="col-sm-8">
                        <select name="id_jurusan" id="id_jurusan" class="form-select select2" style="width: 100%;">
                            <option value="">Pilih Jurusan</option>
                            @foreach ($jurusan as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- END --}}

                <div class="mb-3 row">
                    <label for="nama_prodi" class="col-sm-3 col-form-label">Nama Prodi</label>
                    <div class="col-sm-8">
                        <input type="text" name="nama_prodi" id="nama_prodi" class="form-control">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-sm btn-black" data-bs-dismiss="modal">
                    <i class="fa fa-times-circle"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>