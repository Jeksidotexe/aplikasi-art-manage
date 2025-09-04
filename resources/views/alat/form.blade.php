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
                    <label for="nama_alat" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input type="text" name="nama_alat" id="nama_alat" class="form-control">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="merk" class="col-sm-3 col-form-label">Merk</label>
                    <div class="col-sm-8">
                        <input type="text" name="merk" id="merk" class="form-control">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jumlah" class="col-sm-3 col-form-label">Jumlah</label>
                    <div class="col-sm-8">
                        <input type="number" name="jumlah" id="jumlah" class="form-control">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tanggal_beli" class="col-sm-3 col-form-label">Tanggal Beli</label>
                    <div class="col-sm-8">
                        <input type="text" name="tanggal_beli" id="tanggal_beli" class="form-control flatpickr"
                            autocomplete="off">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="kondisi" class="col-sm-3 col-form-label">Kondisi</label>
                    <div class="col-sm-8">
                        <select name="kondisi" id="kondisi" class="form-select select2" style="width: 100%;">
                            <option value="">Pilih Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                            <option value="perlu perbaikan">Perlu perbaikan</option>
                        </select>
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