<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengingat Batas Pengembalian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            Pengingat Batas Pengembalian
        </div>
        <div class="content">
            <p>Yth. <strong>{{ $peminjaman->users->nama }}</strong>,</p>
            <p>
                Sekadar mengingatkan bahwa peminjaman alat berikut akan mencapai batas waktu pengembalian **hari ini**:
            </p>
            <ul>
                <li><strong>Nama Alat:</strong> {{ $peminjaman->alat->nama_alat }}</li>
                <li><strong>Jumlah:</strong> {{ $peminjaman->jumlah_pinjam }} unit</li>
                <li><strong>Tanggal Pinjam:</strong> {{
                    \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</li>
                <li><strong>Jatuh Tempo:</strong> <strong style="color: #007bff;">{{
                        \Carbon\Carbon::parse($peminjaman->tanggal_harus_kembali)->translatedFormat('d F Y') }}</strong>
                </li>
            </ul>
            <p>
                Mohon untuk mempersiapkan pengembalian alat tepat waktu untuk menghindari keterlambatan.
            </p>
            <p>Terima kasih atas perhatiannya.</p>
        </div>
        <div class="footer">
            <p>Hormat kami,<br>Admin UKM Seni & Budaya</p>
            <p><em>(Ini adalah email otomatis, mohon untuk tidak membalas email ini)</em></p>
        </div>
    </div>
</body>

</html>