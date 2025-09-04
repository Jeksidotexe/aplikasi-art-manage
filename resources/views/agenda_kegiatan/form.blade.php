<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal-form-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" method="post" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('post')

            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="id_bidang" class="col-sm-3 col-form-label">Bidang</label>
                    <div class="col-sm-8">
                        <select name="id_bidang" id="id_bidang" class="form-select select2" style="width: 100%;">
                            <option value="">Pilih Bidang</option>
                            @foreach ($bidang as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama_kegiatan" class="col-sm-3 col-form-label">Nama Kegiatan</label>
                    <div class="col-sm-8">
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tanggal" class="col-sm-3 col-form-label">Tanggal dan Waktu</label>
                    <div class="col-sm-8">
                        <input type="text" name="tanggal" id="tanggal" class="form-control flatpickr"
                            autocomplete="off">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="lokasi" class="col-sm-3 col-form-label">Lokasi</label>
                    <div class="col-sm-8">
                        <input type="text" name="lokasi" id="lokasi" class="form-control">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                    <div class="col-sm-8">
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="file_sk" class="col-sm-3 col-form-label">File Surat Keputusan</label>
                    <div class="col-sm-8">
                        <input type="file" name="file_sk" id="file_sk" class="form-control" form-control>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-black btn-sm" data-bs-dismiss="modal">
                    <i class="fa fa-times-circle"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>