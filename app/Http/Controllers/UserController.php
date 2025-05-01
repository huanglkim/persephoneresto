<?php

namespace App\Http\Controllers;

use App\User;
use App\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function edit()
    {
        $user = User::with('jabatan')->find(Auth::id());
        $jabatans = Jabatan::all(); // ambil semua jabatan untuk dropdown
        return view('auth.profile', compact('user', 'jabatans'));
    }

    public function update(Request $request)
    {
        /** @var \App\User $user */
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'jabatan_id' => 'nullable|exists:jabatan,id',
            'gambar' => 'nullable|image|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Proses upload gambar baru jika ada
        $fileName = $user->gambar;

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($user->gambar && file_exists(public_path('assets/users/' . $user->gambar))) {
                File::delete(public_path('assets/users/' . $user->gambar));
            }

            $file = $request->file('gambar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/users'), $fileName);
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update data user lainnya
        $user->name = $request->name;
        $user->email = $request->email;
        $user->alamat = $request->alamat ?? null;
        $user->nohp = $request->nohp ?? null;
        $user->jabatan_id = $request->jabatan_id ?? null;
        $user->gambar = $fileName;
        $user->save();

        return redirect()->route('profil.edit')->with('success', 'Profil berhasil diperbarui');
    }
}
