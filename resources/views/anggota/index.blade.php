@extends('layouts.master')

@section('title')
Daftar Anggota
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('container')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Daftar Anggota Aktif</h3>
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
                    Anggota
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button onclick="addForm('{{ route('anggota.store') }}')" class="btn btn-success btn-sm"><i
                            class="fa fa-plus"></i> Tambah
                    </button>
                    <button onclick="cetakAnggota('{{ route('anggota.cetak_anggota') }}')"
                        class="btn btn-info btn-sm"><i class="fa fa-print"></i> Cetak Anggota
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="" method="POST" class="form-anggota">
                            @csrf
                            <table id="multi-filter-select" class="display table table-striped table-hover">
                                <thead>
                                    <th width="5%">
                                        <input type="checkbox" name="select_all" id="select_all">
                                    </th>
                                    <th width="5%">No</th>
                                    <th>Foto</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Prodi</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Bidang</th>
                                    <th>Tgl Daftar</th>
                                    <th width="15%"><i class="fa fa-cog"></i></th>
                                </thead>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('anggota.form')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Validator.js (pastikan path ini benar jika Anda menggunakannya) --}}
<script src="{{ asset('assets/js/validator.min.js') }}"></script>

<script>
    let table;
    let selectedProdiOnEdit = null;

    // [FUNGSI BARU] Untuk pratinjau gambar
    function previewImage() {
        const image = document.querySelector('#foto');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }

    // [FUNGSI BARU] Untuk membersihkan error validasi
    function clearValidationErrors() {
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    $(function () {
        table = $('#multi-filter-select').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('anggota.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'foto'},
                {data: 'nim'},
                {data: 'nama'},
                {data: 'nama_jurusan'},
                {data: 'nama_prodi'},
                {data: 'email'},
                {data: 'no_telepon'},
                {data: 'nama_bidang'},
                {data: 'tanggal_daftar'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            // Sesuaikan urutan kolom jika perlu
            columnDefs: [
                { "orderable": false, "targets": [0, 2, 8] } // Nonaktifkan sort untuk checkbox, foto, aksi
            ]
        });

        $('#modal-form form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const url = $(form).attr('action');
            const formData = new FormData(form);

            $.ajax({
                url: url,
                method: 'POST', // Method selalu POST, karena PUT/PATCH disimulasikan via _method
                data: formData,
                processData: false, // Penting untuk FormData
                contentType: false, // Penting untuk FormData
                beforeSend: function() {
                    clearValidationErrors(); // Bersihkan error sebelum submit
                },
                success: function(response) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        // Tampilkan error di bawah setiap field
                        $.each(errors, function(field, messages) {
                            const input = $(`#${field}`);
                            input.addClass('is-invalid');
                            input.siblings('.invalid-feedback').text(messages[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Harap periksa kembali isian form Anda.'
                        });
                    } else {
                         Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Tidak dapat menyimpan data.'
                        });
                    }
                }
            });
        });


        $('[name=select_all]').on('click', function() {
            $(':checkbox').prop('checked', this.checked);
        });

        // Logika untuk getProdi (tidak ada perubahan)
        $('#id_jurusan').on('change', function() {
            let idJurusan = $(this).val();
            let prodiSelect = $('#id_prodi');

            prodiSelect.empty().append('<option value="">Memuat...</option>').prop('disabled', true);

            if (idJurusan) {
                $.ajax({
                    url: '{{ route('anggota.get_prodi') }}',
                    type: 'GET',
                    data: { id_jurusan: idJurusan },
                    success: function(data) {
                        prodiSelect.empty().append('<option value=""></option>').prop('disabled', false);
                        $.each(data, function(key, value) {
                            prodiSelect.append('<option value="' + value.id_prodi + '">' + value.nama_prodi + '</option>');
                        });
                        if (selectedProdiOnEdit) {
                            prodiSelect.val(selectedProdiOnEdit).trigger('change');
                            selectedProdiOnEdit = null;
                        } else {
                            prodiSelect.val('').trigger('change');
                        }
                    },
                    error: function() {
                        prodiSelect.empty().append('<option value="">Gagal memuat prodi</option>');
                    }
                });
            } else {
                prodiSelect.empty().append('<option value=""></option>').prop('disabled', true).trigger('change');
            }
        });
    });

    function addForm(url) {
        clearValidationErrors();
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Anggota');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=password]').attr('placeholder', 'Wajib diisi');
        // [PERUBAHAN] Reset pratinjau gambar
        $('.img-preview').attr('src', '').hide();

        $('#id_jurusan').val('').trigger('change');
        $('#id_prodi').empty().append('<option value=""></option>').prop('disabled', true).trigger('change');
        $('#id_bidang').val('').trigger('change');
    }

    function editForm(url) {
        clearValidationErrors();
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Anggota');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=password]').prop('required', false).attr('placeholder', 'Kosongkan jika tidak diubah');
        // [PERUBAHAN] Reset pratinjau gambar
        $('.img-preview').attr('src', '').hide();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nim]').val(response.nim);
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=email]').val(response.email);
                $('#modal-form [name=no_telepon]').val(response.no_telepon);
                $('#modal-form [name=id_bidang]').val(response.id_bidang).trigger('change');

                // [PERUBAHAN] Tampilkan foto yang sudah ada
                if (response.foto) {
                    $('.img-preview').attr('src', response.foto_url).show();
                }

                selectedProdiOnEdit = response.id_prodi;
                $('#modal-form [name=id_jurusan]').val(response.id_jurusan).trigger('change');
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
            });
    }

    function deleteData(url) {
        Swal.fire({
            title: 'Yakin ingin menghapus data?',
            text: "Data anggota ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                    })
                    .fail(function (xhr) {
                    // Siapkan pesan error default
                    let errorMessage = 'Tidak dapat menghapus data.';

                    // Cek apakah server mengirim pesan error spesifik dalam format JSON
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    // Tampilkan pesan error yang benar
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage,
                    });
                });
            }
        });
    }

    function cetakAnggota(url) {
        if ($('input:checked').length < 1) {
            Swal.fire('Perhatian!', 'Pilih data yang akan dicetak.', 'warning');
            return;
        }
        $('.form-anggota').attr('target', '_blank').attr('action', url).submit();
    }

    // --- PERUBAHAN DI SINI ---
    // Skrip untuk inisialisasi Select2
    $('#modal-form').on('shown.bs.modal', function () {
        $('#id_jurusan').select2({
            dropdownParent: $('#modal-form'),
            placeholder: "Pilih Jurusan",
            allowClear: true
        });

        $('#id_prodi').select2({
            dropdownParent: $('#modal-form'),
            placeholder: "Pilih Prodi",
            allowClear: true
        });

        $('#id_bidang').select2({
            dropdownParent: $('#modal-form'),
            placeholder: "Pilih Bidang",
            allowClear: true
        });
    });
</script>
@endpush