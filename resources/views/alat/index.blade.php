@extends('layouts.master')

@section('title')
Daftar Alat
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('container')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Daftar Alat</h3>
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
                    Alat
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button onclick="addForm('{{ route('alat.store') }}')" class="btn btn-success btn-sm"><i
                            class="fa fa-plus"></i> Tambah
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <th width="5%">No</th>
                                <th>Bidang</th>
                                <th>Nama</th>
                                <th>Merk</th>
                                <th>Jumlah</th>
                                <th>Tanggal Beli</th>
                                <th>Kondisi</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('alat.form')
@endsection

@push('scripts')
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('alat.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_bidang'},
                {data: 'nama_alat'},
                {data: 'merk'},
                {data: 'jumlah'},
                {data: 'tanggal_beli'},
                {data: 'kondisi'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (!e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();

                        // SweetAlert success
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    })
                    .fail((xhr) => {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';

                        if (errors) {
                            for (let key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorMsg += `${errors[key][0]}<br>`;
                                }
                            }
                        } else {
                            errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                        }

                        // SweetAlert error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMsg
                        });
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Alat');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_bidang]').focus();
        $('#modal-form [name=nama_alat]').focus();
        $('#modal-form [name=merk]').focus();
        $('#modal-form [name=jumlah]').focus();
        $('#modal-form [name=tanggal_beli]').focus();
        $('#modal-form [name=kondisi]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Alat');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=id_bidang]').focus();
        $('#modal-form [name=nama_alat]').focus();
        $('#modal-form [name=merk]').focus();
        $('#modal-form [name=jumlah]').focus();
        $('#modal-form [name=tanggal_beli]').focus();
        $('#modal-form [name=kondisi]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=id_bidang]').val(response.id_bidang);
                $('#modal-form [name=nama_alat]').val(response.nama_alat);
                $('#modal-form [name=merk]').val(response.merk);
                $('#modal-form [name=jumlah]').val(response.jumlah);
                $('#modal-form [name=tanggal_beli]').val(response.tanggal_beli);
                $('#modal-form [name=kondisi]').val(response.kondisi);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function deleteData(url) {
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(url, {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                '_method': 'delete'
            })
            .done(() => {
                table.ajax.reload();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .fail(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Data tidak dapat dihapus'
                });
            });
        }
    });
}

</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $('#modal-form').on('shown.bs.modal', function () {
        flatpickr('.flatpickr', {
            dateFormat: 'Y-m-d',
            allowInput: true,
        });

        $('#id_bidang').select2({
            dropdownParent: $('#modal-form'),
            placeholder: "Pilih Bidang",
            allowClear: true
        });

        $('#kondisi').select2({
            dropdownParent: $('#modal-form'),
            placeholder: "Pilih Kondisi",
            allowClear: true
        });
    });
</script>
@endpush
