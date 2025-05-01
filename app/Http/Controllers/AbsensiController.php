<?php

namespace App\Http\Controllers;

use App\Absensi;
use App\Divisi;
use App\Jabatan;
use App\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    public function index()
    {
        // Ambil data absensi dan data terkait lainnya
        // In your controller method
        $absensis = Absensi::with(['karyawan', 'jabatan', 'divisi'])->get();
        $karyawans = Karyawan::all();
        $jabatans = Jabatan::all();
        $divisis = Divisi::all();

        // Tampilkan ke view
        return view('karyawan.absensi', compact('absensis', 'karyawans', 'jabatans', 'divisis'));
    }

    public function getJabatanAndDivisi($karyawanId)
    {
        $karyawan = Karyawan::findOrFail($karyawanId);
        return response()->json([
            'jabatan' => $karyawan->jabatan->nama_jabatan ?? '',
            'divisi' => $karyawan->divisi->nama ?? '',
            'jabatan_id' => $karyawan->jabatan_id,
            'divisi_id' => $karyawan->divisi_id,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'divisi_id' => 'required|exists:divisi,id',
            'jumlah_hari_kerja' => 'nullable|integer',
            'jumlah_hari_sakit' => 'nullable|integer',
            'jumlah_hari_izin' => 'nullable|integer',
            'jumlah_hari_alfa' => 'nullable|integer',
            'jumlah_hari_cuti' => 'nullable|integer',
            'potongan_gaji_pokok' => 'nullable|integer',
        ]);

        try {
            // Cek apakah data absensi sudah ada, jika ada update, jika tidak create baru
            $absensi = Absensi::updateOrCreate(
                ['id' => $request->id], // Kondisi pencarian untuk update
                $request->only(['karyawan_id', 'jabatan_id', 'divisi_id', 'jumlah_hari_kerja', 'jumlah_hari_sakit', 'jumlah_hari_izin', 'jumlah_hari_alfa', 'jumlah_hari_cuti', 'potongan_gaji_pokok']),
            );

            $message = $request->id ? 'Data berhasil diperbarui!' : 'Data berhasil disimpan!';

            // Jika permintaan berasal dari AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'absensi' => $absensi,
                ]);
            }

            // Jika permintaan biasa
            return redirect()->route('absensi')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan absensi: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function edit($id)
    {
        $absensi = Absensi::findOrFail($id);
        return response()->json($absensi);  // Mengembalikan data absensi dalam format JSON
    }
    

    public function destroy($id)
    {
        // Ambil data absensi berdasarkan ID
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json([
                'success' => 0,
                'pesan' => 'Data tidak ditemukan.',
            ]);
        }

        // Hapus data absensi
        $absensi->delete();
        return response()->json([
            'success' => 1,
            'pesan' => 'Data berhasil dihapus!',
        ]);
    }
}
