<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal-form-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" method="post" enctype="multipart/form-data" class="modal-content" novalidate>
            @csrf
            @method('post')

            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="id_agenda" class="col-sm-3 col-form-label">Nama Kegiatan</label>
                    <div class="col-sm-8">
                        <select name="id_agenda" id="id_agenda" class="form-select select2" style="width: 100%;">
                            <option value="">Pilih Kegiatan</option>
                            @foreach ($agenda_kegiatan as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama_bidang_display" class="col-sm-3 col-form-label">Bidang</label>
                    <div class="col-sm-8">
                        <input type="text" id="nama_bidang_display" class="form-control" placeholder="Bidang" readonly
                            style="background-color: #e9ecef;">
                        <input type="hidden" name="id_bidang" id="id_bidang_hidden">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="file_absensi" class="col-sm-3 col-form-label">File Absensi</label>
                    <div class="col-sm-8">
                        <input type="file" name="file_absensi" id="file_absensi" class="form-control">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">
                    <i class="fa fa-times-circle"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>