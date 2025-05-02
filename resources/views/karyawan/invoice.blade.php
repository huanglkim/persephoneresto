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
        <div style="text-align: center;">
            <strong style="font-size: 14px;">Slip Gaji Karyawan</strong><br>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-top: 12px;">
            <div style="text-align: left;">
                <span style="font-size: 12px;">Tanggal Masuk: {{ ($gaji->karyawan->tanggal_masuk ?? null) ? \Carbon\Carbon::parse($gaji->karyawan->tanggal_masuk)->format('d M Y') : '-' }}</span><br>
                <span style="font-size: 12px;">Tanggal Penggajian: {{ \Carbon\Carbon::parse($gaji->tanggal ?? now())->format('d M Y') }}</span><br>
                <span style="font-size: 12px;">Jumlah Hari Kerja: {{ $gaji->absensi->jumlah_hari_kerja ?? '-' }}</span>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 12px;">Nama Karyawan: {{ ($gaji->karyawan->nama_karyawan ?? '-') }}</span><br>
                <span style="font-size: 12px;">Divisi: {{ $gaji->karyawan->divisi->nama ?? '-' }}</span><br>
                <span style="font-size: 12px;">Jabatan: {{ $gaji->jabatan->nama_jabatan ?? '-' }}</span>
            </div>
        </div>
        <hr>
        <div class="info-section">
            <p><span class="label">Gaji Pokok</span> Rp. {{ number_format($gaji->jabatan->gaji_pokok ?? 0, 0, ',', '.') }}
            </p>
            <p><span class="label">Tunjangan Jabatan</span> Rp.
                {{ number_format($gaji->jabatan->tunjangan_jabatan ?? 0, 0, ',', '.') }}</p>
            <p><span class="label">Potongan Gaji</span> Rp.
                {{ number_format($gaji->absensi->potongan_gaji_pokok ?? 0, 0, ',', '.') }}</p>
            <p><span class="label">Keterangan</span> {{ $gaji->keterangan ?? '-' }}</p>

            <hr style="margin: 20px 0; border: 1px solid #ccc;">

            <p style="font-weight: bold;">
                <span class="label">Total Gaji</span> Rp. {{ number_format($gaji->total_gaji ?? 0, 0, ',', '.') }}
            </p>
        </div>
        
        <div style="margin-top: 30px; display: flex; justify-content: space-between;">
            <div style="text-align: center;">
                <span style="font-weight: bold;">Owner</span><br><br><br><br>
                <span>Ale Huang</span><br>
                <div style="width: 150px; border-bottom: 1px solid #000; margin-top: 5px; margin-left: auto; margin-right: auto;"></div>
            </div>
            <div style="text-align: center;">
                <span style="font-weight: bold;">Karyawan</span><br><br><br><br>
                <span>{{ $gaji->karyawan->nama_karyawan ?? '-' }}</span><br>
                <div style="width: 150px; border-bottom: 1px solid #000; margin-top: 5px; margin-left: auto; margin-right: auto;"></div>
            </div>
        </div>
    </div>


    <script>
        window.print();
    </script>

</body>

</html>