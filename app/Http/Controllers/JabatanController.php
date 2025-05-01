<?php

namespace App\Http\Controllers;

use App\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('karyawan.jabatan', compact('jabatans'));
    }
    
    public function getJabatanDetail($id): JsonResponse
    {
        $jabatan = Jabatan::findOrFail($id);
        return response()->json([
            'gaji_pokok' => $jabatan->gaji_pokok,
            'tunjangan_jabatan' => $jabatan->tunjangan_jabatan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|string|max:255',
            'tunjangan_jabatan' => 'required|string|max:255',
        ]);

        try {
            if ($request->has('id') && $request->id) {
                $jabatan = Jabatan::findOrFail($request->id);
                $jabatan->update($request->only(['nama_jabatan', 'gaji_pokok', 'tunjangan_jabatan']));
                $message = 'Data berhasil diperbarui!';
                $updatedJabatan = $jabatan;
            } else {
                $jabatan = Jabatan::create($request->only(['nama_jabatan', 'gaji_pokok', 'tunjangan_jabatan']));
                $message = 'Data berhasil disimpan!';
                $newJabatan = $jabatan;
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => $message,
                    'updatedJabatan' => isset($updatedJabatan) ? $updatedJabatan : null,
                    'newJabatan' => isset($newJabatan) ? $newJabatan : null,
                ]);
            }

            return redirect()->route('jabatan')->with('success', $message);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function edit($id)
    {
        $jabatan = Jabatan::find($id);
        if (!$jabatan) {
            return redirect()->route('jabatan')->with('error', 'Data tidak ditemukan.');
        }

        return response()->json($jabatan);
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::find($id);
        if (!$jabatan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }

        $jabatan->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus!',
        ]);
    }
}
