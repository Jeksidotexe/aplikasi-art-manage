<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Alat</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h3 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>Laporan Peminjaman Alat</h3>
        <p>
            Periode:
            <strong>{{ \Carbon\Carbon::parse($tanggalAwal)->format('d-m-Y') }}</strong>
            s/d
            <strong>{{ \Carbon\Carbon::parse($tanggalAkhir)->format('d-m-Y') }}</strong>
        </p>
    </div>

    <div class="section">
        <table style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="background-color: #e2e3e5;">Grand Total Alat</th>
                    <th style="background-color: #cfe2ff;">Total Peminjaman</th>
                    <th style="background-color: #d1e7dd;">Total Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 18px; font-weight: bold; text-align: center;">{{ $grandTotalAlat ?? 0 }} Unit
                    </td>
                    <td style="font-size: 18px; font-weight: bold; text-align: center;">{{ $totalPeminjaman ?? 0 }} Unit
                    </td>
                    <td style="font-size: 18px; font-weight: bold; text-align: center;">{{ $totalPengembalian ?? 0 }}
                        Unit</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Total Inventaris per Bidang</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Bidang</th>
                    <th width="30%">Jumlah Unit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($totalAlatPerBidang as $bidang)
                <tr>
                    <td>{{ $bidang->nama_bidang }}</td>
                    <td style="text-align: center;">{{ $bidang->alat_sum_jumlah ?? 0 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center;">Data bidang tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Alat Paling Sering Dipinjam</div>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Alat</th>
                    <th width="30%">Jumlah Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alatSeringDipinjam as $index => $alat)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $alat->nama_alat }}</td>
                    <td style="text-align: center;">{{ $alat->total_dipinjam }} kali</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data peminjaman pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Anggota Paling Aktif</div>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Anggota</th>
                    <th width="30%">Jumlah Meminjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($anggotaSeringMeminjam as $index => $anggota)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $anggota->nama }}</td>
                    <td style="text-align: center;">{{ $anggota->jumlah_peminjaman }} kali</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada anggota yang meminjam pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>