<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Produk;
use Illuminate\Http\Request;


class ProdukController extends Controller
{
    public function index($menuId = null, Request $request)
    {
        $search = $request->input('search');

        $produks = Produk::when($search, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        })
        ->when($menuId, function ($query) use ($menuId) {
            return $query->where('menu_id', $menuId);
        })
        ->paginate(8);

        $menu = $menuId ? Menu::find($menuId) : null;
        $title = $menu ? 'List Menu: ' . $menu->nama : 'Semua Menu';

        $menus = Menu::all();

        return view('menu.produk-index', [
            'menus' => $menus,
            'produks' => $produks,
            'menu' => $menu,
            'title' => $title,
        ]);
    }

    public function tambah(Request $request, $id = null)
    {
        $semuaProduk = Produk::all();
        $produkEdit = null;

        if ($id) {
            $produkEdit = Produk::findOrFail($id);
        }

        return view('menu.tambah-produk', [
            'semuaProduk' => $semuaProduk,
            'produkEdit' => $produkEdit,
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',
            'menu_id' => 'required|exists:menus,id',
            'is_ready' => 'required|boolean',
            'jenis' => 'required',
            'porsi' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $gambarName = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('assets/produk'), $gambarName);
            $gambarPath = $gambarName;
        }

        Produk::create([
            'nama' => $request->nama,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'menu_id' => $request->menu_id,
            'is_ready' => $request->is_ready,
            'jenis' => $request->jenis,
            'porsi' => $request->porsi,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('produks.tambah')->with('success', 'Item berhasil disimpan!');
    }

    public function edit($id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return redirect()->route('produks.tambah')->with('error', 'Item Tidak Ditemukan');
        }
        return view('menu.edit-produk', ['produk' => $produk]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',
            'menu_id' => 'required|exists:menus,id',
            'is_ready' => 'required|boolean',
            'jenis' => 'nullable|string',
            'porsi' => 'nullable|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->only(['nama', 'stok', 'harga', 'menu_id', 'is_ready', 'jenis', 'porsi']));

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && file_exists(public_path('assets/produk/' . $produk->gambar))) {
                unlink(public_path('assets/produk/' . $produk->gambar));
            }
            $gambar = $request->file('gambar');
            $namaGambar = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('assets/produk'), $namaGambar);
            $produk->gambar = $namaGambar;
        }

        $produk->save();
        return redirect()->route('produks.tambah')->with('success', 'Item berhasil diperbarui!');
    }

    public function delete($id)
    {
        try {
            $produk = Produk::find($id);
            if ($produk && $produk->gambar && file_exists(public_path('assets/produk/' . $produk->gambar))) {
                unlink(public_path('assets/produk/' . $produk->gambar));
            }

            Produk::destroy($id);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus item. Silakan coba lagi.'], 500);
        }
    }
}
