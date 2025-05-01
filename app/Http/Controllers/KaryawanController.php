<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Divisi;
use App\Jabatan;
use App\Karyawan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{
    // Display the list of karyawans
    public function index()
    {
        $karyawans = Karyawan::all(); // Fetch all data
        $divisis = Divisi::all();
        $jabatans = Jabatan::all();
        return view('karyawan.karyawan', compact(['karyawans', 'divisis', 'jabatans']));
    }

    public function karyawanexport()
    {
        return Excel::download(new KaryawanExport(), 'karyawan.xlsx');
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
        Log::info('KaryawanController@store - Request Data:', $request->all());
    
        if ($request->id) {
            // Find the real User ID
            $karyawan = Karyawan::findOrFail($request->id);
            $userId = $karyawan->user_id;
    
            // Validation for UPDATE
            $request->validate([
                'nama_karyawan' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($userId),
                ],
                'tanggal_masuk' => 'required|date',
                'divisi_id' => 'required|exists:divisi,id',
                'jabatan_id' => 'required|exists:jabatan,id',
                'role' => 'required|in:karyawan,admin',
                'is_active' => 'required|boolean',
                'password' => 'nullable', // allow password NULL on update
            ]);
        } else {
            // Validation for CREATE
            $request->validate([
                'nama_karyawan' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'tanggal_masuk' => 'required|date',
                'divisi_id' => 'required|exists:divisi,id',
                'jabatan_id' => 'required|exists:jabatan,id',
                'role' => 'required|in:karyawan,admin',
                'is_active' => 'required|boolean',
            ]);
        }
    
        try {
            DB::beginTransaction();
    
            if ($request->id) {
                // Update existing karyawan and user
                $karyawan = Karyawan::findOrFail($request->id);
                Log::info('KaryawanController@store - Updating Karyawan ID: ' . $karyawan->id);
    
                $karyawan->nama_karyawan = $request->nama_karyawan;
                $karyawan->tanggal_masuk = $request->tanggal_masuk;
                $karyawan->divisi_id = $request->divisi_id;
                $karyawan->jabatan_id = $request->jabatan_id;
                $karyawan->save();
    
                $user = $karyawan->user;
                if ($user) {
                    $user->email = $request->email;
                    $user->role = $request->role;
                    $user->is_active = $request->is_active;
                    if ($request->filled('password')) {
                        $user->password = Hash::make($request->password);
                    }
                    $user->save();
                }
                Log::info('KaryawanController@store - Karyawan and User updated');
            } else {
                // Create new karyawan and user
                $user = new User();
                $user->name = $request->nama_karyawan;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->role = $request->role;
                $user->jabatan_id = $request->jabatan_id;
                $user->is_active = $request->is_active;
                $user->save();
                Log::info('KaryawanController@store - New User created ID: ' . $user->id);
    
                $karyawan = new Karyawan();
                $karyawan->nama_karyawan = $request->nama_karyawan;
                $karyawan->tanggal_masuk = $request->tanggal_masuk;
                $karyawan->divisi_id = $request->divisi_id;
                $karyawan->jabatan_id = $request->jabatan_id;
                $karyawan->user_id = $user->id;
                $karyawan->save();
                Log::info('KaryawanController@store - New Karyawan created ID: ' . $karyawan->id);
            }
    
            DB::commit();
            Log::info('KaryawanController@store - Transaction committed');
            return response()->json(['success' => true, 'pesan' => 'Data karyawan berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KaryawanController@store - Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    public function edit($id)
    {
        $karyawan = Karyawan::find($id);
        if (!$karyawan) {
            return redirect()->route('karyawan')->with('error', 'Data tidak ditemukan.');
        }

        $user = $karyawan->user;
        if (!$user) {
            Log::error('KaryawanController@edit - User not found for Karyawan ID: ' . $karyawan->id);
            return response()->json(['success' => false, 'pesan' => 'User not found for this Karyawan'], 500);
        }

        return response()->json([
            'id' => $karyawan->id,
            'nama_karyawan' => $karyawan->nama_karyawan,
            'tanggal_masuk' => $karyawan->tanggal_masuk,
            'divisi_id' => $karyawan->divisi_id,
            'jabatan_id' => $karyawan->jabatan_id,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
        ]);
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);
        if (!$karyawan) {
            return response()->json([
                'success' => 0,
                'pesan' => 'Data tidak ditemukan.',
            ]);
        }

        DB::beginTransaction();
        try {
            if ($karyawan->user_id) {
                $user = User::find($karyawan->user_id);
                if ($user) {
                    $user->delete();
                }
            }
            $karyawan->delete();
            DB::commit();
            return response()->json([
                'success' => 1,
                'pesan' => 'Data berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KaryawanController@destroy - Error deleting karyawan: ' . $e->getMessage());
            return response()->json([
                'success' => 0,
                'pesan' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
            ]);
        }
    }
}
