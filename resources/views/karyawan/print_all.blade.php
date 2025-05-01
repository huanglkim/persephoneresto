<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua Laporan Gaji</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f4f7fc;
            color: #333;
        }

        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 20px 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .header img {
            max-width: 100px;
        }

        .header-title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #FF7F32;
            line-height: 1.4;
        }

        .date {
            text-align: right;
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #eee;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #FF7F32;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #fef6f0;
        }

        tr:hover {
            background-color: #fbe9dd;
        }

        .total-footer {
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            color: #FF7F32;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                margin: 0;
                padding: 0;
                background: none;
            }

            .container {
                box-shadow: none;
                padding: 10px;
                margin: 0;
                width: 100%;
                max-width: none;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            tr, table {
                page-break-inside: avoid;
            }

            .total-footer {
                page-break-before: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        @include('layouts.heading')

        <div style="margin-top: 12px;">
            <strong style="font-size: 14px;">Laporan Gaji Keseluruhan</strong><br>
            <span style="font-size: 12px;"> Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y') }}</span>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Hari Kerja</th>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan</th>
                    <th>Potongan Gaji</th>
                    <th>Total Gaji</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalGaji = 0;
                @endphp

                @foreach ($gajis as $no => $gaji)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $gaji->karyawan->nama_karyawan }}</td>
                        <td>{{ $gaji->jabatan->nama_jabatan }}</td>
                        <td>{{ $gaji->absensi->jumlah_hari_kerja }}</td>
                        <td>{{ 'Rp. ' . number_format($gaji->jabatan->gaji_pokok, 0, ',', '.') }}</td>
                        <td>{{ 'Rp. ' . number_format($gaji->jabatan->tunjangan_jabatan, 0, ',', '.') }}</td>
                        <td>{{ 'Rp. ' . number_format($gaji->absensi->potongan_gaji_pokok, 0, ',', '.') }}</td>
                        <td>{{ 'Rp. ' . number_format($gaji->total_gaji, 0, ',', '.') }}</td>
                        <td>{{ $gaji->keterangan }}</td>
                    </tr>
                    @php
                        $totalGaji += $gaji->total_gaji;
                    @endphp
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="total-footer">
            Total Gaji Keseluruhan: Rp. {{ number_format($totalGaji, 0, ',', '.') }}
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
