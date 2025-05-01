<?php

namespace App\Http\Controllers;

use App\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::all(); // Mendapatkan semua data Divisi
        return view('karyawan.divisi', compact('divisis')); // Menampilkan data divisi di view
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        try {
            if ($request->has('id') && $request->id) {
                // Update existing data
                $divisi = Divisi::findOrFail($request->id);
                $divisi->update($request->only(['nama']));
                $message = 'Data berhasil diperbarui!';
                $updatedDivisi = $divisi; // Return updated divisi data
            } else {
                // Store new data
                $divisi = Divisi::create($request->only(['nama']));
                $message = 'Data berhasil disimpan!';
                $newDivisi = $divisi; // Return new divisi data
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => $message,
                    'updatedDivisi' => isset($updatedDivisi) ? $updatedDivisi : null,
                    'newDivisi' => isset($newDivisi) ? $newDivisi : null,
                ]);
            }

            // If regular request
            return redirect()->route('divisi')->with('success', $message);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function edit($id)
    {
        $divisi = Divisi::find($id);
        if (!$divisi) {
            return redirect()->route('divisi')->with('error', 'Data tidak ditemukan.');
        }

        return response()->json($divisi);
    }

    public function destroy($id)
    {
        $divisi = Divisi::find($id);
        if (!$divisi) {
            return response()->json([
                'success' => 0,
                'pesan' => 'Data tidak ditemukan.',
            ]);
        }

        $divisi->delete();
        return response()->json([
            'success' => 1,
            'pesan' => 'Data berhasil dihapus!',
        ]);
    }
}
