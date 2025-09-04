<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>{{ config('app.name') }} | @yield('title', 'Login')</title>
    <link rel="icon" href="{{ asset('kaiadmin-lite-1.2.0/assets/img/logo-usb1.jpg') }}" type="image/x-icon" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('auth-template/style.css') }}" />

    <style>
        /* CSS untuk daftar error (sebelumnya) */
        .swal-error-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: left;
            font-size: 14px;
        }

        .swal-error-list li {
            padding: 8px 12px;
            margin-bottom: 5px;
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #374151;
            border-radius: 4px;
        }

        /* [BARU] CSS Kustom untuk SweetAlert */
        .swal-title-custom {
            font-size: 22px;
            /* Ukuran judul lebih besar */
            font-weight: 600;
            /* Judul lebih tebal */
            color: #333;
            /* Warna teks judul lebih gelap */
            margin-bottom: 15px;
            /* Tambah jarak bawah judul */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Contoh font profesional */
        }

        .swal-html-container-custom {
            font-size: 16px;
            /* Ukuran teks pesan lebih besar */
            color: #555;
            /* Warna teks pesan lebih kalem */
            text-align: center;
            /* Pusatkan teks pesan */
            font-family: 'Open Sans', sans-serif;
            /* Contoh font profesional lainnya */
        }

        .swal-confirm-button-custom {
            background-color: #007bff !important;
            /* Warna tombol utama */
            color: #fff !important;
            /* Warna teks tombol */
            font-size: 16px !important;
            /* Ukuran teks tombol lebih besar */
            padding: 10px 24px !important;
            /* Padding tombol lebih besar */
            border-radius: 8px !important;
            /* Sudut tombol lebih membulat */
            border: none !important;
            /* Hilangkan border default */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
            /* Tambah shadow halus */
            transition: background-color 0.3s ease !important;
            /* Efek hover halus */
            font-family: 'Roboto', sans-serif !important;
            /* Contoh font tombol */
        }

        .swal-confirm-button-custom:hover {
            background-color: #0056b3 !important;
            /* Warna tombol saat dihover */
        }
    </style>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            @yield('register')
        </div>
        <div class="form-container sign-in-container">
            @yield('login')
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Sudah Punya Akun?</h1>
                    <p>Silakan login untuk masuk ke dashboard.</p>
                    <button class="ghost" id="signIn">Login</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Halo, Calon Anggota!</h1>
                    <p>Masukkan data diri Anda dan mulailah perjalanan bersama kami.</p>
                    <button class="ghost" id="signUp">Daftar</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>
            Copyright &copy; {{ date('Y') }}. All rights reserved.
            UKM Seni & Budaya - Politeknik Negeri Sambas
        </p>
    </footer>

    <script src="{{ asset('auth-template/main.js') }}"></script>
</body>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts-auth') {{-- Ini untuk skrip dari login_register.blade.php --}}

<script>
    // Logika untuk menampilkan error dengan SweetAlert
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: `
                    <ul class="swal-error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#007bff',
                customClass: { // Tambahkan customClass di sini
                    title: 'swal-title-custom',
                    htmlContainer: 'swal-html-container-custom',
                    confirmButton: 'swal-confirm-button-custom'
                }
            });

            // Logika untuk beralih ke panel registrasi jika error berasal dari sana
            @if (
                $errors->has('nim') ||
                $errors->has('nama') ||
                $errors->has('id_jurusan') ||
                $errors->has('id_prodi') ||
                $errors->has('password_confirmation') ||
                $errors->has('no_telepon') ||
                $errors->has('id_bidang') ||
                $errors->has('foto')
            )
                // Pastikan elemen #container ada sebelum menjalankan skrip ini
                if (document.getElementById('container')) {
                    document.getElementById('container').classList.add("right-panel-active");
                }
            @endif
        @endif

        // Logika untuk pesan status sukses (tetap sama)
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('status') }}',
                confirmButtonColor: '#007bff',
                customClass: { // Tambahkan customClass di sini
                    title: 'swal-title-custom',
                    htmlContainer: 'swal-html-container-custom',
                    confirmButton: 'swal-confirm-button-custom'
                }
            });
        @endif
</script>

</html>