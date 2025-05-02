<?php

namespace App\Http\Controllers;

use App\Exports\GajiExport;
use App\Imports\GajiImport;
use App\Gaji;
use App\Karyawan;
use App\Jabatan;
use App\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class GajiController extends Controller
{
    public function index()
    {
        $gajis = Gaji::all();
        $karyawans = Karyawan::all();
        $jabatans = Jabatan::all();
        $absensis = Absensi::all();

        return view('karyawan.gaji', compact(['gajis', 'karyawans', 'jabatans', 'absensis']));
    }
    public function gajiexport()
    {
        return Excel::download(new GajiExport(), 'gaji.xlsx');
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'karyawan_id' => 'required|integer',
            'absensi_id' => 'nullable|integer',
            'jabatan_id' => 'required|integer',
        ]);

        // Get Jabatan and Absensi
        $jabatan = Jabatan::findOrFail($request->jabatan_id);
        $absensi = null;
        if ($request->absensi_id) {
            $absensi = Absensi::find($request->absensi_id);
        }

        // Calculate total_gaji
        $potonganGaji = $absensi ? $absensi->potongan_gaji_pokok : 0;
        $totalGaji = $jabatan->gaji_pokok + $jabatan->tunjangan_jabatan - $potonganGaji;

        try {
            if ($request->has('id') && $request->id) {
                // Update existing data
                $gaji = Gaji::findOrFail($request->id);
                $gaji->update($request->only(['karyawan_id', 'absensi_id', 'jabatan_id', 'keterangan']));
                $gaji->total_gaji = $totalGaji;
                $gaji->save();
                $message = 'Data berhasil diperbarui!';
            } else {
                // Store new data
                $gaji = new Gaji($request->only(['karyawan_id', 'absensi_id', 'jabatan_id', 'keterangan']));
                $gaji->total_gaji = $totalGaji;
                $gaji->save();
                $message = 'Data berhasil disimpan!';
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'gaji' => $gaji, // Mengembalikan satu variabel gaji
                ]);
            }

            return redirect()->route('gaji')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan gaji: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function edit($id)
    {
        $gaji = Gaji::findOrFail($id); // Menggunakan findOrFail
        return response()->json($gaji);
    }

    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'karyawan_id' => 'required|integer',
            'absensi_id' => 'nullable|integer',
            'jabatan_id' => 'required|integer',
        ]);

        // Get Jabatan and Absensi
        $jabatan = Jabatan::findOrFail($request->jabatan_id);
        $absensi = null;
        if ($request->absensi_id) {
            $absensi = Absensi::find($request->absensi_id);
        }

        // Calculate total_gaji
        $potonganGaji = $absensi ? $absensi->potongan_gaji_pokok : 0;
        $totalGaji = $jabatan->gaji_pokok + $jabatan->tunjangan_jabatan - $potonganGaji;

        $gaji = Gaji::findOrFail($id);
        $gaji->update($request->only(['karyawan_id', 'absensi_id', 'jabatan_id', 'keterangan']));
        $gaji->total_gaji = $totalGaji;
        $gaji->save();

        $message = 'Data berhasil diperbarui!';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'gaji' => $gaji,
            ]);
        }

        return redirect()->route('gaji')->with('success', $message);
    }


    // Delete the specified gaji
    public function destroy($id)
    {
        $gaji = Gaji::find($id);
        if (!$gaji) {
            return response()->json([
                'success' => 0,
                'pesan' => 'Data tidak ditemukan.',
            ]);
        }
        $gaji->delete();
        return response()->json([
            'success' => 1,
            'pesan' => 'Data berhasil dihapus!',
        ]);
    }
    public function printall()
    {
        $gajis = Gaji::with(['karyawan', 'absensi', 'jabatan'])->get(); // Get all reports
        return view('karyawan.print_all', compact('gajis'));
    }
    public function print(Request $request, $id)
    {
        $gaji = Gaji::with(['karyawan', 'absensi', 'jabatan'])->findOrFail($id); // Gunakan findOrFail untuk memastikan data ada
        return view('karyawan.invoice', compact('gaji'));
    }
}