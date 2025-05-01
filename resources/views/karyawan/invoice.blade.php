<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Gaji</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 20px;
            background-color: #f4f7fc;
            color: #333;
            font-size: 12px;
        }

        .invoice {
            max-width: 700px;
            margin: 0 auto;
            padding: 25px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.5px solid #ccc;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .invoice-header img {
            max-height: 60px;
        }

        .invoice-header h1 {
            font-size: 20px;
            color: #FF7F32;
            margin: 0;
        }

        .info-section p {
            font-size: 12px;
            margin: 6px 0;
        }

        .info-section span.label {
            font-weight: 600;
            width: 160px;
            display: inline-block;
        }

        .total-gaji {
            margin-top: 25px;
            font-weight: bold;
            font-size: 14px;
            color: #FF7F32;
            text-align: right;
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
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <div class="invoice">

        @include('layouts.heading')

        <div style="margin-top: 12px;">
            <strong style="font-size: 14px;">Slip Gaji Karyawan</strong><br>
            <span style="font-size: 12px;">Tanggal: {{ \Carbon\Carbon::parse($gaji->tanggal)->format('d M Y') }}</span>
        </div>

        <div class="info-section">
            <p><span class="label">Nama</span> {{ $gaji->karyawan->nama_karyawan }}</p>
            <p><span class="label">Jabatan</span> {{ $gaji->jabatan->nama_jabatan }}</p>
            <p><span class="label">Jumlah Hari Kerja</span> {{ $gaji->absensi->jumlah_hari_kerja }}</p>
            <p><span class="label">Jumlah Hari Sakit</span> {{ $gaji->absensi->jumlah_hari_sakit }}</p>
            <p><span class="label">Jumlah Hari Izin</span> {{ $gaji->absensi->jumlah_hari_izin }}</p>
            <p><span class="label">Jumlah Hari Alfa</span> {{ $gaji->absensi->jumlah_hari_alfa }}</p>
            <p><span class="label">Jumlah Hari Cuti</span> {{ $gaji->absensi->jumlah_hari_cuti }}</p>
            <p><span class="label">Gaji Pokok</span> Rp. {{ number_format($gaji->jabatan->gaji_pokok, 0, ',', '.') }}
            </p>
            <p><span class="label">Tunjangan Jabatan</span> Rp.
                {{ number_format($gaji->jabatan->tunjangan_jabatan, 0, ',', '.') }}</p>
            <p><span class="label">Potongan Gaji</span> Rp.
                {{ number_format($gaji->absensi->potongan_gaji_pokok, 0, ',', '.') }}</p>
            <p><span class="label">Tanggal</span> {{ \Carbon\Carbon::parse($gaji->tanggal)->format('d M Y') }}</p>
            <p><span class="label">Keterangan</span> {{ $gaji->keterangan }}</p>

            <hr style="margin: 20px 0; border: 1px solid #ccc;">

            <p style="font-weight: bold;">
                <span class="label">Total Gaji</span> Rp. {{ number_format($gaji->total_gaji, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <script>
        window.print();
    </script>

</body>

</html>
