<div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal-form-label" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <form action="{{ route('laporan.index') }}" method="get" enctype="multipart/form-data" class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="modal-form-label"></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">

                <div class="mb-3 row">

                    <label for="tanggal_awal" class="col-sm-3 col-form-label">Tanggal Awal</label>

                    <div class="col-sm-8">

                        <input type="text" name="tanggal_awal" id="tanggal_awal" class="form-control flatpickr"
                            autocomplete="off" value="{{ request('tanggal_awal') }}">

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="tanggal_akhir" class="col-sm-3 col-form-label">Tanggal Akhir</label>

                    <div class="col-sm-8">

                        <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control flatpickr"
                            autocomplete="off" value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}">

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