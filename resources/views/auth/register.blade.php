@extends('layouts.auth')

@section('title', 'Register')

@section('register')
{{-- [PERUBAHAN] Form ini sekarang berada di section 'register' --}}
<form action="{{ route('register.post') }}" method="POST">
    @csrf
    <h1>Buat Akun</h1>

    <input type="text" name="nim" placeholder="NIM (10 digit)" value="{{ old('nim') }}" required />
    <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required />

    <select name="id_jurusan" id="id_jurusan" required>
        <option value="" disabled selected>Pilih Jurusan</option>
        @foreach ($jurusan as $item)
        <option value="{{ $item->id_jurusan }}" {{ old('id_jurusan')==$item->id_jurusan ? 'selected' : '' }}>
            {{ $item->nama_jurusan }}
        </option>
        @endforeach
    </select>

    <select name="id_prodi" id="id_prodi" required disabled>
        <option value="">Pilih Jurusan Terlebih Dahulu</option>
    </select>

    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
    <input type="password" name="password" placeholder="Password (min. 8 karakter)" required />
    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required />
    <input type="text" name="no_telepon" placeholder="Nomor Telepon (08...)" value="{{ old('no_telepon') }}" required />

    <select name="id_bidang" required>
        <option value="" disabled selected>Pilih Bidang Minat</option>
        @foreach ($bidang as $item)
        <option value="{{ $item->id_bidang }}" {{ old('id_bidang')==$item->id_bidang ? 'selected' : '' }}>
            {{ $item->nama_bidang }}
        </option>
        @endforeach
    </select>

    <button type="submit">Daftar</button>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jurusanSelect = document.getElementById('id_jurusan');
        const prodiSelect = document.getElementById('id_prodi');

        jurusanSelect.addEventListener('change', function() {
            let idJurusan = this.value;
            prodiSelect.innerHTML = '<option value="">Memuat...</option>';
            prodiSelect.disabled = true;

            if (idJurusan) {
                // Pastikan Anda sudah membuat API route ini
                // Contoh: Route::get('/api/jurusan/{jurusan}/prodi', [ProdiController::class, 'getByJurusan']);
                fetch(`/anggota/get-prodi?id_jurusan=${idJurusan}`)
                    .then(response => response.json())
                    .then(data => {
                        prodiSelect.innerHTML = '<option value="">Pilih Prodi</option>';
                        data.forEach(prodi => {
                            prodiSelect.innerHTML += `<option value="${prodi.id_prodi}">${prodi.nama_prodi}</option>`;
                        });
                        prodiSelect.disabled = false;
                    });
            } else {
                prodiSelect.innerHTML = '<option value="">Pilih Jurusan Terlebih Dahulu</option>';
            }
        });
    });
</script>
@endpush
