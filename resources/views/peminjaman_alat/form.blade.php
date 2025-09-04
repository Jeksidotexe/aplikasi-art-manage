<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal-form-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" method="post" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                @if (Auth::user()->role == 'admin')
                <div class="mb-3 row">
                    <label for="nim_anggota" class="col-sm-3 col-form-label">NIM Anggota</label>
                    <div class="col-sm-8">
                        <input type="text" id="nim_anggota" class="form-control"
                            placeholder="Ketik NIM, lalu tekan Tab atau klik di luar">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama_anggota" class="col-sm-3 col-form-label">Nama Anggota</label>
                    <div class="col-sm-8">
                        <input type="text" id="nama_anggota" class="form-control" readonly>
                        {{-- Input tersembunyi untuk menyimpan ID user yang akan dikirim ke controller --}}
                        <input type="hidden" name="id_users" id="id_users">
                    </div>
                </div>
                @endif

                <div class="mb-3 row">
                    <label for="id_alat" class="col-sm-3 col-form-label">Nama Alat</label>
                    <div class="col-sm-8">
                        <select name="id_alat" id="id_alat" class="form-select select2" style="width: 100%;">
                            <option value="" selected disabled>Pilih Alat</option>
                            @foreach ($alat as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="jumlah_pinjam" class="col-sm-3 col-form-label">Jumlah Pinjam</label>
                    <div class="col-sm-8">
                        <input type="number" name="jumlah_pinjam" id="jumlah_pinjam" class="form-control"
                            placeholder="Masukkan jumlah pinjam">
                    </div>
                </div>

                @if (Auth::user()->role == 'admin')
                <div class="mb-3 row">
                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-8">
                        <select name="status" id="status" class="form-select">
                            <option value="">Pilih Status</option>
                            <option value="diajukan">Diajukan</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="dikembalikan">Dikembalikan</option>
                        </select>
                    </div>
                </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm" id="submit-button" disabled><i
                        class="fa fa-save"></i>
                    Simpan</button>
                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal"><i
                        class="fa fa-times-circle"></i> Batal</button>
            </div>
        </form>
    </div>
</div>