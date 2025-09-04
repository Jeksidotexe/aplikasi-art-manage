<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Terverifikasi</title>
    <link rel="icon" href="{{ asset('kaiadmin-lite-1.2.0/assets/img/logo-usb1.jpg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f6f9;
            font-family: 'Public Sans', sans-serif;
            color: #495057;
        }

        .card {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .icon-wrapper {
            font-size: 4rem;
            color: #198754;
            /* Warna hijau untuk sukses */
            margin-bottom: 20px;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .message {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .btn-login {
            display: inline-block;
            background-color: #0d6efd;
            color: #fff;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="icon-wrapper">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Verifikasi Berhasil!</h1>
        <p class="message">
            Alamat email Anda telah berhasil diverifikasi. <br>
            Saat ini, akun Anda sedang menunggu persetujuan dari Admin. Anda akan menerima notifikasi email lebih lanjut
            setelah akun Anda disetujui.
        </p>
        <a href="{{ route('login') }}" class="btn-login">Kembali ke Halaman Login</a>
    </div>

</body>

</html>