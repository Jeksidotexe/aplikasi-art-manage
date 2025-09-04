<!DOCTYPE html>
<html>

<head>
    <title>Data Peminjaman Alat</title>
    <link rel="icon" href="{{ asset('kaiadmin-lite-1.2.0/assets/img/logo-usb1.jpg') }}" type="image/x-icon" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            /* Ukuran font disesuaikan untuk lebih banyak kolom */
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 18px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #3498db;
            color: white;
        }

        th {
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #2980b9;
        }

        td {
            padding: 7px 6px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #f1f8fe;
        }

        /* Helper class untuk perataan */
        .text-center {
            text-align: center;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        @media print {
            body {
                padding: 0;
                font-size: 9px;
            }

            h2 {
                font-size: 16px;
            }

            table {
                box-shadow: none;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }

            th,
            td {
                padding: 5px 4px;
            }
        }
    </style>
</head>

<body>
    <h2>Daftar Peminjaman Alat</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Anggota</th>
                <th>Nama Alat</th>
                <th>Bidang</th>
                <th class="text-center">Jumlah</th>
                <th>Tgl. Pinjam</th>
                <th>Tgl. Hrs. Kembali</th>
                <th>Tgl. Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjaman_alat as $no => $item)
            <tr>
                <td>{{ $no + 1 }}</td>
                <td>{{ $item->users->nim ?? '-' }}</td>
                <td>{{ $item->users->nama ?? 'Data Pengguna Dihapus' }}</td>
                <td>{{ $item->alat->nama_alat ?? 'Data Alat Dihapus' }}</td>
                <td>{{ $item->alat->bidang->nama_bidang ?? '-' }}</td>
                <td class="text-center">{{ $item->jumlah_pinjam }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_harus_kembali)->translatedFormat('d M Y') }}</td>
                <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y')
                    : '-' }}</td>
                <td class="text-capitalize">{{ $item->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data yang dipilih untuk dicetak.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>