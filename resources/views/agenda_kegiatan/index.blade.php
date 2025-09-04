@extends('layouts.master')

@section('title')
Daftar Agenda Kegiatan
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('container')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Daftar Agenda Kegiatan</h3>
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
                    Agenda Kegiatan
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if (Auth::user()->role == 'admin')
                    <button onclick="addForm('{{ route('agenda_kegiatan.store') }}')" class="btn btn-success btn-sm"><i
                            class="fa fa-plus"></i> Tambah
                    </button>
                    <button onclick="cetakAgenda('{{ route('agenda_kegiatan.cetak_agenda') }}')"
                        class="btn btn-info btn-sm"><i class="fa fa-print"></i> Cetak Agenda
                    </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="" method="POST" class="form-agenda" enctype="multipart/form-data">
                            @csrf
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th width="5%">
                                        <input type="checkbox" name="select_all" id="select_all">
                                    </th>
                                    <th width="5%">No</th>
                                    <th>Bidang</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Lokasi</th>
                                    <th>Keterangan</th>
                                    <th>SK</th>
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

@includeIf('agenda_kegiatan.form')

@endsection

@push('scripts')
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                url: '{{ route('agenda_kegiatan.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_bidang'},
                {data: 'nama_kegiatan'},
                {data: 'tanggal'},
                {data: 'lokasi'},
                {data: 'keterangan'},
                {data: 'file_sk'},
                {data: 'aksi', searchable: false, sortable: false},
            ]

        });

        $('#modal-form').on('submit', function (e) {
            e.preventDefault();

            let form = $('#modal-form form')[0];
            let data = new FormData(form);
            let url = $('#modal-form form').attr('action');
            let method = $('#modal-form [name=_method]').val();

            $.ajax({
                url: url,
                type: method === 'post' ? 'POST' : 'POST',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
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
                error: function (xhr) {
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

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: errorMsg
                    });
                }
            });
        });
            $('[name=select_all]').on('click', function () {
                        $(':checkbox').prop('checked', this.checked);
                    });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Agenda');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=id_bidang]').focus();
        $('#modal-form [name=nama_kegiatan]').focus();
        $('#modal-form [name=tanggal]').focus();
        $('#modal-form [name=lokasi]').focus();
        $('#modal-form [name=keterangan]').focus();
        $('#modal-form [name=file_sk]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Agenda');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=id_bidang]').focus();
        $('#modal-form [name=nama_kegiatan]').focus();
        $('#modal-form [name=tanggal]').focus();
        $('#modal-form [name=lokasi]').focus();
        $('#modal-form [name=keterangan]').focus();
        $('#modal-form [name=file_sk]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=id_bidang]').val(response.id_bidang);
                $('#modal-form [name=nama_kegiatan]').val(response.nama_kegiatan);
                $('#modal-form [name=tanggal]').val(response.tanggal);
                $('#modal-form [name=lokasi]').val(response.lokasi);
                $('#modal-form [name=keterangan]').val(response.keterangan);
                $('#modal-form [name=file_sk]').val(response.file_sk);
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

function cetakAgenda(url) {
    if ($('input[name="id_agenda[]"]:checked').length < 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops!',
            text: 'Pilih data yang akan dicetak',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        return;
    }

    $('.form-agenda')
        .attr('target', '_blank')
        .attr('action', url)
        .submit();
}

</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $('#modal-form').on('shown.bs.modal', function () {
        flatpickr('.flatpickr', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            locale: 'id',
            allowInput: true,
        });

        $('#id_bidang').select2({
            dropdownParent: $('#modal-form'),
            placeholder: "Pilih Bidang",
            allowClear: true
        });
    });
</script>
@endpush