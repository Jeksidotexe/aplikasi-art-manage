@extends('layouts.master')

@section('title')
Daftar Kehadiran Kegiatan
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('container')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Daftar Kehadiran Kegiatan</h3>
        </div>
        <div class="page-header ms-md-auto">
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ url('/dashboard') }}"><i class="icon-home"></i></a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    Kehadiran Kegiatan
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button onclick="addForm('{{ route('kehadiran_kegiatan.store') }}')"
                        class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</button>
                    <button onclick="cetakKehadiran('{{ route('kehadiran_kegiatan.cetak_kehadiran') }}')"
                        class="btn btn-info btn-sm"><i class="fa fa-print"></i> Cetak Kehadiran</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="" method="POST" class="form-kehadiran">
                            @csrf
                            <table id="multi-filter-select" class="display table table-striped table-hover">
                                <thead>
                                    <th width="5%"><input type="checkbox" name="select_all" id="select_all"></th>
                                    <th width="5%">No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Bidang</th>
                                    <th>File Absensi</th>
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

@includeIf('kehadiran_kegiatan.form')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let table;

    $(function () {
        table = $('#multi-filter-select').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: { url: '{{ route('kehadiran_kegiatan.data') }}' },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_kegiatan'},
                {data: 'nama_bidang'},
                {data: 'file_absensi', searchable: false, sortable: false},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form form').on('submit', function (e) {
            e.preventDefault();
            let form = this;
            let data = new FormData(form);
            let url = $(form).attr('action');

            $(form).find('button[type=submit]').prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                processData: false, contentType: false,
                success: function (response) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response, timer: 1500, showConfirmButton: false });
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = errors ? Object.values(errors).join('<br>') : 'Terjadi kesalahan.';
                    Swal.fire({ icon: 'error', title: 'Gagal', html: errorMsg });
                },
                complete: function () {
                    $(form).find('button[type=submit]').prop('disabled', false);
                }
            });
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });

        $('#id_agenda').on('change', function() {
            let agendaId = $(this).val();

            if (agendaId) {
                $.get(`{{ url('/get-agenda-detail') }}/${agendaId}`)
                    .done(function (data) {
                        $('#nama_bidang_display').val(data.bidang.nama_bidang);
                        $('#id_bidang_hidden').val(data.id_bidang);
                    });
            } else {
                $('#nama_bidang_display').val('');
                $('#id_bidang_hidden').val('');
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kehadiran');
        let form = $('#modal-form form');
        form[0].reset();
        form.attr('action', url);
        form.find('[name=_method]').val('post');

        $('#id_agenda').val(null).trigger('change');
        $('#file_absensi').prop('required', true);
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Kehadiran');
        let form = $('#modal-form form');
        form[0].reset();
        form.attr('action', url);
        form.find('[name=_method]').val('put');
        $('#file_absensi').prop('required', false);

        $.get(url.replace('/update', '/show'))
            .done(function(response) {
                form.find('[name=id_agenda]').val(response.id_agenda).trigger('change');
            })
            .fail(function() {
                alert('Tidak dapat menampilkan data');
            });
    }

    function deleteData(url) {
        Swal.fire({
            title: 'Apakah anda yakin?', text: "Data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {'_token': $('meta[name=csrf-token]').attr('content'), '_method': 'delete'})
                    .done((response) => {
                        table.ajax.reload();
                        Swal.fire({icon: 'success', title: 'Berhasil', text: 'Data berhasil dihapus.'});
                    })
                    .fail((errors) => {
                        Swal.fire({icon: 'error', title: 'Gagal!', text: 'Tidak dapat menghapus data.'});
                        return;
                    });
            }
        })
    }

    function cetakKehadiran(url) {
        if ($('input[name="id_kehadiran[]"]:checked').length < 1) {
            Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Pilih data yang akan dicetak' });
            return;
        }
        $('.form-kehadiran').attr('target', '_blank').attr('action', url).submit();
    }

    $('#modal-form').on('shown.bs.modal', function () {
        $('#id_agenda').select2({
            dropdownParent: $('#modal-form'),
            placeholder: "Pilih Kegiatan",
            allowClear: true
        });
    });
</script>
@endpush
