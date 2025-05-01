<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua Pesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .header-logo {
            text-align: left;
            margin-bottom: 20px;
        }

        .header-logo img {
            max-width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #8b4cff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .total-footer {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .total-footer p {
            margin: 5px 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-header">
        @include('layouts.heading')
    </div>

    <h2>Laporan Keseluruhan Pesanan</h2>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Nama Karyawan</th>
                <th>Tanggal Pesanan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHarga = 0;
            @endphp

            @foreach ($pesanans as $no => $pesanan)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $pesanan->user ? $pesanan->user->name : 'Tidak ada User' }}</td>
                    <td>{{ $pesanan->created_at }}</td>
                    <td>{{ 'Rp. ' . number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                </tr>
                @php
                    $totalHarga += $pesanan->total_harga ?? 0;
                @endphp
            @endforeach
        </tbody>
    </table>

    <div class="total-footer">
        <p><strong>Total Harga Keseluruhan: Rp. {{ number_format($totalHarga, 0, ',', '.') }}</strong></p>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>