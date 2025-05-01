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
        $totalGaji = $jabatan->gaji_pokok + $jabatan->tunjangan_jabatan - ($absensi ? $absensi->potongan_gaji_pokok : 0);

        // Check if we're updating an existing entry
        try {
            // Check if the request contains an ID, meaning we want to update the data
            if ($request->has('id') && $request->id) {
                // Update existing data
                $gaji = Gaji::findOrFail($request->id);
                $gaji->update($request->only(['karyawan_id', 'absensi_id', 'jabatan_id', 'keterangan']));
                $gaji->total_gaji = $totalGaji; // Update total_gaji
                $gaji->save(); // Save the changes
                $message = 'Data berhasil diperbarui!';
                $updatedGaji = $gaji;
            } else {
                // Store new data
                $gaji = new Gaji($request->only(['karyawan_id', 'absensi_id', 'jabatan_id', 'keterangan']));
                $gaji->total_gaji = $totalGaji; // Set total_gaji
                $gaji->save(); // Save the new entry
                $message = 'Data berhasil disimpan!';
                $newGaji = $gaji;
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'updatedGaji' => isset($updatedGaji) ? $updatedGaji : null,
                    'newGaji' => isset($newGaji) ? $newGaji : null,
                ]);
            }

            // Redirect with success message
            return redirect()->route('gaji')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan gaji: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function edit($id)
    {
        // Ambil data absensi berdasarkan ID
        $gaji = Gaji::find($id);
        if (!$gaji) {
            return redirect()->route('gaji')->with('error', 'Data tidak ditemukan.');
        }

        // Kirim data gaji ke view modal dalam format JSON
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
    $totalGaji = $jabatan->gaji_pokok + $jabatan->tunjangan_jabatan - ($absensi ? $absensi->potongan_gaji_pokok : 0);

    $gaji = Gaji::findOrFail($id); // Find the model instance to update
    $gaji->update($request->only(['karyawan_id', 'absensi_id', 'jabatan_id', 'keterangan']));
    $gaji->total_gaji = $totalGaji;
    $gaji->save();

    $message = 'Data berhasil diperbarui!';

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'updatedGaji' => $gaji,
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
        $gaji = Gaji::with(['karyawan', 'absensi', 'jabatan'])->find($id);
        return view('karyawan.invoice', compact('gaji'));
    }
}