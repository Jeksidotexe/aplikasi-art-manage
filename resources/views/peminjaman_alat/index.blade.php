@extends('layouts.master')

@section('title')
{{ Auth::user()->role == 'admin' ? 'Daftar Peminjaman Alat' : 'Pengajuan & Riwayat Peminjaman' }}
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('container')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">@yield('title')</h3>
        </div>
        <div class="page-header ms-md-auto">
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ url('/dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">Peminjaman Alat</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if(Auth::user()->role == 'admin')
                    <button onclick="addForm('{{ route('peminjaman_alat.store') }}')" class="btn btn-success btn-sm"><i
                            class="fa fa-plus"></i> Tambah</button>
                    <button onclick="cetakPeminjaman('{{ route('peminjaman_alat.cetak_peminjaman') }}')"
                        class="btn btn-info btn-sm"><i class="fa fa-print"></i> Cetak Laporan</button>
                    @else
                    <button onclick="addForm('{{ route('peminjaman_alat.store') }}')" class="btn btn-success btn-sm"><i
                            class="fa fa-plus"></i> Ajukan Peminjaman</button>
                    @endif
                </div>
                <div class="card-body">
                    <form action="" method="POST" class="form-peminjaman">
                        @csrf
                        <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                @if(Auth::user()->role == 'admin')
                                <th width="5%"><input type="checkbox" name="select_all" id="select_all"></th>
                                <th width="5%">No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Alat</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th width="25%"><i class="fa fa-cog"></i></th>
                                @else
                                <th width="5%">No</th>
                                <th>Nama Alat</th>
                                <th>Bidang</th>
                                <th>Jumlah</th>
                                <th>Tgl Pinjam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                                @endif
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('peminjaman_alat.form')
@includeIf('peminjaman_alat.detail')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let table;
    // [VARIABEL AMAN] - Untuk menyimpan ID user, aman dari skrip lain
    let selectedUserId = null;

    // Fungsi terpusat untuk menampilkan pesan error validasi
    function handleValidationErrors(errors) {
        let errorMessages = Object.values(errors.responseJSON.errors).map(error => `<li>${error[0]}</li>`).join('');
        Swal.fire({ icon: 'error', title: 'Gagal', html: `<ul>${errorMessages}</ul>` });
    }

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: { url: '{{ route('peminjaman_alat.data') }}' },
            columns: [
                @if(Auth::user()->role == 'admin')
                {data: 'select_all', orderable: false, searchable: false},
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nim', defaultContent: '-'},
                {data: 'nama'},
                {data: 'nama_alat'},
                {data: 'jumlah_pinjam'},
                {data: 'status'},
                {data: 'aksi', orderable: false, searchable: false},
                @else
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_alat'},
                {data: 'nama_bidang', defaultContent: '-'},
                {data: 'jumlah_pinjam'},
                {data: 'tanggal_pinjam'},
                {data: 'status'},
                {data: 'aksi', orderable: false, searchable: false},
                @endif
            ]
        });

        // [HANDLER SUBMIT UTAMA] - Dengan suntikan nilai dari variabel aman
        $('#modal-form').on('submit', 'form', function (e) {
            e.preventDefault();
            const form = $(this);
            const submitButton = form.find('#submit-button');

            // [PERBAIKAN FINAL] Suntikkan kembali ID user dari variabel aman
            if ('{{ Auth::user()->role }}' === 'admin' && selectedUserId) {
                form.find('input[name="id_users"]').val(selectedUserId);
            }

            submitButton.prop('disabled', true);
            const method = form.find('input[name="_method"]').val() || 'POST';

            $.ajax({
                url: form.attr('action'),
                type: method,
                data: form.serialize(),
                success: function (response) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response, timer: 1500, showConfirmButton: false });
                },
                error: function (errors) {
                    if (errors.status === 422) {
                        handleValidationErrors(errors);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memproses data.' });
                    }
                },
                complete: function() {
                    if ('{{ Auth::user()->role }}' !== 'admin' || form.find('input[name="_method"]').length > 0) {
                         submitButton.prop('disabled', false);
                    }
                }
            });
        });

        // [HANDLER NIM UTAMA] - Menyimpan ID ke variabel aman
        $('#modal-form').on('blur', '#nim_anggota', function () {
            const nim = $(this).val().trim();
            const form = $(this).closest('form');
            const submitButton = form.find('#submit-button');
            const namaAnggotaField = form.find('#nama_anggota');
            const idUsersField = form.find('input[name="id_users"]');

            // Reset kondisi
            submitButton.prop('disabled', true);
            idUsersField.val('');
            selectedUserId = null; // Kosongkan variabel aman
            namaAnggotaField.val(nim ? 'Mencari...' : '');

            if (!nim) return;

            const url = '{{ route("peminjaman_alat.get_anggota", ["nim" => ":nim"]) }}'.replace(':nim', nim);

            $.get(url)
                .done(function (response) {
                    namaAnggotaField.val(response.nama);
                    idUsersField.val(response.id_users);
                    selectedUserId = response.id_users;  // Simpan ID ke variabel aman
                    submitButton.prop('disabled', false);
                })
                .fail(function () {
                    namaAnggotaField.val('');
                    Swal.fire('Gagal', 'NIM tidak ditemukan atau bukan anggota.', 'error');
                });
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });

    // FUNGSI UNTUK MEMBUKA MODAL
    function addForm(url) {
        const modal = $('#modal-form');
        const form = modal.find('form');

        modal.modal('show');
        modal.find('.modal-title').text('Tambah Peminjaman Alat');

        form[0].reset();
        form.attr('action', url);
        form.find('input[name="_method"]').remove();

        modal.find('.select2').val(null).trigger('change');
        selectedUserId = null; // Pastikan variabel aman kosong saat form baru dibuka

        const submitButton = form.find('#submit-button');
        if ('{{ Auth::user()->role }}' === 'admin') {
            submitButton.prop('disabled', true);
        } else {
            submitButton.prop('disabled', false);
        }
    }

    function detailForm(url) {
        $.get(url).done((response) => {
            $('#modal-detail').modal('show');
            $('#detail-nama').text(response.users?.nama || '-');
            $('#detail-nim').text(response.users?.nim || '-');
            $('#detail-jurusan').text(response.users?.jurusan?.nama_jurusan || '-');
            $('#detail-prodi').text(response.users?.prodi?.nama_prodi || '-');
            $('#detail-nama-alat').text(response.alat?.nama_alat || '-');
            $('#detail-nama-bidang').text(response.alat?.bidang?.nama_bidang || '-');
            $('#detail-merk-alat').text(response.alat?.merk || '-');
            $('#detail-jumlah-pinjam').text(response.jumlah_pinjam || '-');
            $('#detail-tgl-pinjam').text(response.tanggal_pinjam ?? '-');
            $('#detail-tgl-harus-kembali').text(response.tanggal_harus_kembali ?? '-');
            $('#detail-tgl-kembali').text(response.tanggal_kembali ?? '-');
            $('#detail-status-badge').html(table.column(6).data().toArray().find(s => s.includes(response.status)) || response.status);
            $('#detail-foto').attr('src', response.users?.foto ? `/${response.users.foto}` : '/images/default-user.png');
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat menampilkan detail.', 'error'));
    }

    function editForm(show_url, update_url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Edit Peminjaman Alat');

    let form = $('#modal-form form');
    form[0].reset();
    form.attr('action', update_url);

    if (!form.find('[name=_method]').length) {
        form.append('<input type="hidden" name="_method" value="PUT">');
    }

    // Ambil data dan isi form
    $.get(show_url)
        .done((response) => {
            // Data yang sudah ada sebelumnya
            $('#id_alat').val(response.id_alat).trigger('change');
            $('#jumlah_pinjam').val(response.jumlah_pinjam);
            $('#status').val(response.status);

            // ✨ TAMBAHKAN KODE INI
            if(response.users) {
                // Isi input NIM dan Nama
                $('#nim_anggota').val(response.users.nim);
                $('#nama_anggota').val(response.users.nama);

                // Isi input hidden id_users untuk dikirim saat update
                $('#id_users').val(response.users.id_users);

                // Nonaktifkan inputan anggota agar tidak bisa diubah
                $('#nim_anggota').prop('disabled', true);

                // Aktifkan tombol simpan karena data anggota sudah ada
                $('#submit-button').prop('disabled', false);
            }
        })
        .fail((errors) => {
            alert('Tidak dapat mengambil data.');
        });
}

    /**
     * [BARU] Fungsi untuk menghapus data
     */
    function deleteData(url) {
        Swal.fire({
            title: 'Hapus Data Ini?',
            text: "Data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { '_token': '{{ csrf_token() }}' },
                    success: () => {
                        table.ajax.reload();
                        Swal.fire('Berhasil!', 'Data telah dihapus.', 'success');
                    },
                    error: (errors) => {
                        Swal.fire('Gagal!', 'Tidak dapat menghapus data.', 'error');
                    }
                });
            }
        });
    }

    function approve(url) {
        Swal.fire({
            title: 'Setujui Peminjaman Ini?',
            text: "Stok alat akan dikurangi setelah disetujui.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {'_token': '{{ csrf_token() }}'})
                .done((response) => {
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response, 'success');
                })
                .fail((errors) => Swal.fire('Gagal!', errors.responseJSON.message, 'error'));
            }
        });
    }

        function reject(url) {
        Swal.fire({
            title: 'Tolak Peminjaman Ini?',
            input: 'textarea',
            inputLabel: 'Alasan Penolakan',
            inputPlaceholder: 'Tuliskan alasan penolakan (minimal 5 karakter)...',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Anda harus mengisi alasan penolakan!'
                }
                if (value.length < 5) {
                    return 'Alasan penolakan minimal 5 karakter.'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                    '_token': '{{ csrf_token() }}',
                    'alasan_penolakan': result.value
                })
                .done((response) => {
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response, 'success');
                })
                .fail((xhr) => { // Perbarui blok .fail() menjadi seperti ini
                    console.log(xhr);
                    let errorMessage = 'Tidak dapat menolak permintaan.';
                    // Cek jika error disebabkan oleh validasi (status 422)
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        // Ambil pesan error pertama dari Laravel
                        if (errors && errors.alasan_penolakan) {
                            errorMessage = errors.alasan_penolakan[0];
                        }
                    }
                    Swal.fire('Gagal!', errorMessage, 'error');
                });
            }
        });
    }

    function confirmPickup(url) {
        Swal.fire({
            title: 'Konfirmasi Pengambilan Alat?',
            text: "Pastikan Anda sudah menyerahkan alat kepada peminjam.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Konfirmasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {'_token': '{{ csrf_token() }}'})
                .done((response) => {
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response, 'success');
                })
                .fail((errors) => Swal.fire('Gagal!', errors.responseJSON.message, 'error'));
            }
        });
    }

        function processReturn(url) {
        Swal.fire({
            title: 'Proses Pengembalian Alat?',
            text: "Stok alat akan dikembalikan ke sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Proses!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {'_token': '{{ csrf_token() }}'})
                .done((response) => {
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response, 'success');
                })
                .fail((errors) => Swal.fire('Gagal!', errors.responseJSON.message, 'error'));
            }
        });
    }

    function cetakPeminjaman(url) {
        if ($('input[name="id_peminjaman[]"]:checked').length < 1) {
            Swal.fire('Peringatan!', 'Pilih data yang akan dicetak', 'warning');
            return;
        }
        $('.form-peminjaman').attr('target', '_blank').attr('action', url).submit();
    }

    $('#modal-form').on('shown.bs.modal', function () {
        flatpickr('.flatpickr', { dateFormat: 'Y-m-d', allowInput: true });
        if(!$(this).find('.select2-hidden-accessible').length) {
            $('.select2').select2({
                dropdownParent: $('#modal-form'),
                placeholder: "Pilih dari daftar",
                allowClear: true
            });
        }
    });
</script>
@endpush
