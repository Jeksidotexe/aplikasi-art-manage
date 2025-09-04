@extends('layouts.auth')

@section('title', 'Login & Registrasi')

{{-- Menempatkan form login --}}
@section('login')
<form action="{{ route('login') }}" method="POST">
    @csrf
    <h1>Silakan Login</h1>

    <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
    </div>
    <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required />
    </div>

    <button type="submit">Login</button>
</form>
@endsection


{{-- Menempatkan form registrasi --}}
@section('register')
<form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h1>Buat Akun Baru</h1>

    <div class="form-grid">
        {{-- Baris 1: NIM dan Nama (Tetap 2 kolom) --}}
        <div class="input-group">
            <i class="fas fa-id-card"></i>
            <input type="text" name="nim" placeholder="NIM (10 digit)" value="{{ old('nim') }}" required />
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required />
        </div>

        {{-- Baris 2: Jurusan dan Prodi (Tetap 2 kolom) --}}
        <div class="input-group">
            <i class="fas fa-building"></i>
            <select name="id_jurusan" id="id_jurusan" required>
                <option value="" disabled selected>Pilih Jurusan</option>
                @foreach ($jurusan as $item)
                <option value="{{ $item->id_jurusan }}" {{ old('id_jurusan')==$item->id_jurusan ? 'selected' : '' }}>
                    {{ $item->nama_jurusan }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="input-group">
            <i class="fas fa-chalkboard-teacher"></i>
            <select name="id_prodi" id="id_prodi" disabled required>
                <option value="">Pilih Prodi</option>
            </select>
        </div>

        {{-- Baris Selanjutnya: Dibuat Full-Width --}}
        <div class="full-width-group">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
            </div>
        </div>
        <div class="full-width-group">
            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="no_telepon" placeholder="Nomor Telepon (contoh: 0812...)"
                    value="{{ old('no_telepon') }}" required />
            </div>
        </div>
        <div class="full-width-group">
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password (minimal 8 karakter)" required />
            </div>
        </div>
        <div class="full-width-group">
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required />
            </div>
        </div>
        <div class="full-width-group">
            <div class="input-group">
                <i class="fas fa-theater-masks"></i>
                <select name="id_bidang" required>
                    <option value="" disabled selected>Pilih Bidang</option>
                    @foreach ($bidang as $item)
                    <option value="{{ $item->id_bidang }}" {{ old('id_bidang')==$item->id_bidang ? 'selected' : '' }}>
                        {{ $item->nama_bidang }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Input Foto (Sudah Full-Width) --}}
        <div class="full-width-group">
            <label for="foto" class="form-label">Pilih Foto (Wajib, max: 2MB)</label>
            <div class="file-input-wrapper">
                <input type="file" name="foto" id="foto" required class="file-input-real">
                <div class="file-input-fake">
                    <span class="file-input-button">
                        <i class="fas fa-upload"></i>
                    </span>
                    <span class="file-input-label" id="file-name-display">
                        Belum ada file dipilih...
                    </span>
                </div>
            </div>
        </div>
    </div>

    <button type="submit">Daftar</button>
</form>
@endsection


{{-- BAGIAN PENTING ADA DI SINI --}}
@push('scripts-auth')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Skrip untuk dropdown Prodi ---
        const jurusanSelect = document.getElementById('id_jurusan');
        const prodiSelect = document.getElementById('id_prodi');

        function fetchProdi(idJurusan, selectedProdi = null) {
            prodiSelect.innerHTML = '<option value="">Memuat...</option>';
            prodiSelect.disabled = true;

            if (idJurusan) {
                fetch(`/anggota/get-prodi?id_jurusan=${idJurusan}`)
                    .then(response => response.json())
                    .then(data => {
                        prodiSelect.innerHTML = '<option value="">Pilih Prodi</option>';
                        data.forEach(prodi => {
                            const option = document.createElement('option');
                            option.value = prodi.id_prodi;
                            option.textContent = prodi.nama_prodi;
                            if (selectedProdi && prodi.id_prodi == selectedProdi) {
                                option.selected = true;
                            }
                            prodiSelect.appendChild(option);
                        });
                        prodiSelect.disabled = false;
                    });
            } else {
                prodiSelect.innerHTML = '<option value="">Pilih Jurusan Terlebih Dahulu</option>';
            }
        }

        jurusanSelect.addEventListener('change', function() {
            fetchProdi(this.value);
        });

        if (jurusanSelect.value) {
            fetchProdi(jurusanSelect.value, '{{ old('id_prodi') }}');
        }


        // --- Skrip untuk menampilkan nama file ---
        const fotoInput = document.getElementById('foto');
        const fileNameDisplay = document.getElementById('file-name-display');
        const defaultFileName = 'Belum ada file dipilih...';

        fotoInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                // Jika ada file yang dipilih, tampilkan namanya
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                // Jika tidak, tampilkan teks default
                fileNameDisplay.textContent = defaultFileName;
            }
        });
    });
</script>
@endpush