<div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="modal-detail-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="modal-detail-label">Detail Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Informasi Peminjam -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Peminjam</h6>
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img id="detail-foto" src="/images/default-profile.jpg" alt="Foto Profil"
                                class="img-thumbnail rounded-circle shadow"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-2">
                                <label class="col-sm-4 fw-semibold">Nama</label>
                                <div class="col-sm-8" id="detail-nama"></div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-4 fw-semibold">NIM</label>
                                <div class="col-sm-8" id="detail-nim"></div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-4 fw-semibold">Jurusan</label>
                                <div class="col-sm-8" id="detail-jurusan"></div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-4 fw-semibold">Program Studi</label>
                                <div class="col-sm-8" id="detail-prodi"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Peminjaman -->
                <div>
                    <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Peminjaman</h6>
                    <div class="row mb-2">
                        <label class="col-sm-4 fw-semibold">Nama Alat</label>
                        <div class="col-sm-8" id="detail-nama-alat"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 fw-semibold">Bidang Alat</label>
                        <div class="col-sm-8" id="detail-nama-bidang"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 fw-semibold">Merk</label>
                        <div class="col-sm-8" id="detail-merk-alat"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 fw-semibold">Jumlah Pinjam</label>
                        <div class="col-sm-8" id="detail-jumlah-pinjam"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 fw-semibold">Tanggal Pinjam</label>
                        <div class="col-sm-8" id="detail-tgl-pinjam"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 fw-semibold">Harus Kembali</label>
                        <div class="col-sm-8" id="detail-tgl-harus-kembali"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 fw-semibold">Tanggal Kembali</label>
                        <div class="col-sm-8" id="detail-tgl-kembali"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 fw-semibold">Status</label>
                        <div class="col-sm-8" id="detail-status-badge"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-black btn-sm" data-bs-dismiss="modal">
                    <i class="fa fa-times-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>