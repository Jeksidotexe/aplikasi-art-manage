<!DOCTYPE html>
<html>

<head>
    <title>Data Anggota</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
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
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            border: 1px solid #2980b9;
        }

        td {
            padding: 8px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #f1f8fe;
        }

        @media print {
            body {
                padding: 0;
                font-size: 10px;
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
                padding: 6px 4px;
            }
        }
    </style>
</head>

<body>
    <h2>Daftar Anggota</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Prodi</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Bidang</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($anggota as $no => $anggota)
            <tr>
                <td>{{ $no + 1 }}</td>
                <td>{{ $anggota->nim }}</td>
                <td>{{ $anggota->nama }}</td>
                <td>{{ $anggota->jurusan->nama_jurusan ?? '-' }}</td>
                <td>{{ $anggota->prodi->nama_prodi ?? '-' }}</td>
                <td>{{ $anggota->email }}</td>
                <td>{{ $anggota->no_telepon }}</td>
                <td>{{ $anggota->bidang->nama_bidang ?? '-' }}</td>
                <td>{{ $anggota->tanggal_daftar }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>