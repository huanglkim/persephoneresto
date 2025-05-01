<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Pesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .invoice-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .invoice-header .logo {
            flex: 1;
            /* Agar logo tetap di kiri */
        }

        .invoice-header .details {
            flex: 2;
            /* Agar teks menyesuaikan */
            text-align: right;
            /* Supaya teks rata kanan */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        .total-pendapatan {
            margin-top: 20px;
            font-weight: bold;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .invoice,
            .invoice * {
                visibility: visible;
            }

            .invoice {
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="invoice">
        <div class="invoice-header">
            @include('layouts.heading')
        </div>
        <div class="row">
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
                    @foreach ($pesanans as $no => $pesanan)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $pesanan->user ? $pesanan->user->name : 'Tidak ada User' }}</td>
                            {{-- Check if user exists --}}
                            <td>{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y') }}</td>

                            <td>{{ 'Rp ' . number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="total-pendapatan">
        <h3>Total Pendapatan: Rp. {{ number_format($pesanans->sum('total_harga') ?? 0, 0, ',', '.') }}</h3>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>