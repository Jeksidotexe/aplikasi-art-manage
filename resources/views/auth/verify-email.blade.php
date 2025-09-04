<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Anda</title>
    <link rel="icon" href="{{ asset('kaiadmin-lite-1.2.0/assets/img/logo-usb1.jpg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

        .verification-card {
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
            color: #0d6efd;
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

        .resend-text {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #badbcc;
        }

        .btn-resend {
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

        .btn-resend:hover {
            background-color: #0b5ed7;
        }

        .logout-link {
            display: block;
            margin-top: 25px;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="verification-card">
        <div class="icon-wrapper">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h1>Verifikasi Alamat Email Anda</h1>

        @if (session('status') == 'verification-link-sent')
        <div class="alert-success">
            Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.
        </div>
        @endif

        <p class="message">
            Terima kasih telah mendaftar! Sebelum melanjutkan, silakan periksa kotak masuk email Anda untuk link
            verifikasi.
        </p>

        <p class="resend-text">
            Jika Anda tidak menerima email, klik tombol di bawah ini untuk mengirim ulang.
        </p>

        <form action="{{ route('verification.send') }}" method="POST" style="display: inline-block;">
            @csrf
            <button type="submit" class="btn-resend">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <a class="logout-link" href="#"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Kembali ke halaman
            login</a>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</body>

</html>