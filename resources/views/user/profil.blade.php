@extends('layouts.master')

@section('title')
Edit Profil
@endsection

@section('container')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Edit Profil</h3>
        </div>
        <div class="page-header ms-md-auto">
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ url('/dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    Edit Profil
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form id="formProfil" action="{{ route('user.update_profil') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="nama" class="col-lg-2 control-label">Nama</label>
                            <div class="col-lg-6">
                                <input type="text" name="nama" class="form-control" id="nama"
                                    value="{{ $profil->nama }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-6">
                                <input type="text" name="email" class="form-control" id="email"
                                    value="{{ $profil->email }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="foto" class="col-lg-2 control-label">Profil</label>
                            <div class="col-lg-4">
                                <input type="file" name="foto" class="form-control" id="foto"
                                    onchange="preview('.tampil-foto', this.files[0])">
                                <br>
                                <div class="tampil-foto">
                                    <img src="{{ url($profil->foto ?? '/') }}" class="foto-preview">
                                </div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="old_password" class="col-lg-2 control-label">Password Lama</label>
                            <div class="col-lg-6">
                                <input type="password" name="old_password" id="old_password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-6">
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-lg-2 control-label">Konfirmasi
                                Password</label>
                            <div class="col-lg-6">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" data-match="#password">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.col -->
</div>
</div>

@endsection
@push('css')
<style>
    .foto-preview {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #ddd;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#formProfil').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    Swal.showLoading();
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    let res = xhr.responseJSON;
                    let messages = '';

                    if (res?.errors) {
                        Object.values(res.errors).forEach(function (errArray) {
                            errArray.forEach(function (msg) {
                                messages += `${msg}<br>`;
                            });
                        });
                    } else {
                        messages = res?.message || 'Terjadi kesalahan!';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: messages,
                        confirmButtonText: 'Tutup'
                    });
                }
            });
        });
    });

    $('#foto').on('change', function () {
    const file = this.files[0];
    if (file && file.size > 2 * 1024 * 1024) { // 2MB
        Swal.fire({
            icon: 'warning',
            title: 'Ukuran Gambar Terlalu Besar',
            text: 'Ukuran maksimal gambar adalah 2MB',
        });
        $(this).val(''); // Reset file input
        $('.tampil-foto').html(''); // Kosongkan preview jika perlu
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        $('.tampil-foto').html(`<img src="${e.target.result}" class="foto-preview">`);
    }
    reader.readAsDataURL(file);
});
</script>
@endpush