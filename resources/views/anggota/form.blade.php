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
                    <label for="nim" class="col-sm-3 col-form-label">NIM</label>
                    <div class="col-sm-8">
                        <input type="text" name="nim" id="nim" class="form-control">
                        <div class="invalid-feedback" data-field="nim"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="id_jurusan" class="col-sm-3 col-form-label">Jurusan</label>
                    <div class="col-sm-8">
                        <select name="id_jurusan" id="id_jurusan" class="form-select select2" style="width: 100%;">
                            <option value="">Pilih Jurusan</option>
                            @foreach ($jurusan as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" data-field="id_jurusan"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="id_prodi" class="col-sm-3 col-form-label">Prodi</label>
                    <div class="col-sm-8">
                        <select name="id_prodi" id="id_prodi" class="form-select select2" style="width: 100%;">
                            <option value="">Pilih Jurusan Terlebih Dahulu</option>
                        </select>
                        <div class="invalid-feedback" data-field="id_prodi"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input type="text" name="nama" id="nama" class="form-control">
                        <div class="invalid-feedback" data-field="nama"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" name="email" id="email" class="form-control">
                        <div class="invalid-feedback" data-field="email"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="password" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-8">
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="no_telepon" class="col-sm-3 col-form-label">No. Telepon</label>
                    <div class="col-sm-8">
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control">
                        <div class="invalid-feedback" data-field="no_telepon"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="id_bidang" class="col-sm-3 col-form-label">Bidang</label>
                    <div class="col-sm-8">
                        <select name="id_bidang" id="id_bidang" class="form-select select2" style="width: 100%;">
                            <option value="">Pilih Bidang</option>
                            @foreach ($bidang as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" data-field="id_bidang"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="foto" class="col-sm-3 col-form-label">Foto Profil</label>
                    <div class="col-sm-8">
                        <input type="file" name="foto" id="foto" class="form-control" onchange="previewImage()">
                        <div class="invalid-feedback" data-field="foto"></div>
                        <div class="mt-2">
                            <img class="img-thumbnail img-preview" width="150" style="display: none;">
                        </div>
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