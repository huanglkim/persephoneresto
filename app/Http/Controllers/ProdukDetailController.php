<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Pesanan;
use App\PesananDetail;
use App\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProdukDetailController extends Controller
{
    public function index($id)
    {
        $produk = Produk::findOrFail($id);

        return view('menu.produk-detail', [
            'produk' => $produk,
            'menus' => Menu::all(),
        ]);
    }

    public function addToCart(Request $request, $id)
    {
        $request->validate([
            'jumlah_pesanan' => 'required|integer|min:1',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $produk = Produk::findOrFail($id);

        if ($produk->stok < $request->jumlah_pesanan) {
            return redirect()->route('produk.detail', $id)->with('error', 'Stok tidak mencukupi.');
        }

        $total_harga_pesanan = $request->jumlah_pesanan * $produk->harga;

        $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 0)->first();

        if (empty($pesanan)) {
            $kodePemesanan = 'PS-' . Carbon::now()->format('YmdHis');
            $pesanan = Pesanan::create([
                'user_id' => Auth::user()->id,
                'nama_pemesan' => Auth::user()->name,
                'no_meja' => 0,
                'total_harga' => $total_harga_pesanan,
                'total_bayar' => $total_harga_pesanan,
                'kembalian' => 0,
                'status' => 0,
                'kode_pemesanan' => $kodePemesanan,
                'metode_pembayaran' => 'Tunai', // Atau nilai default lainnya
            ]);
        } else {
            $pesanan->total_harga += $total_harga_pesanan;
            $pesanan->save();
        }

        PesananDetail::create([
            'produk_id' => $produk->id,
            'jumlah_pesanan' => $request->jumlah_pesanan,
            'total_harga' => $total_harga_pesanan,
            'pesanan_id' => $pesanan->id,
        ]);

        $cart = session()->get('cart', []);
        $cartKey = 'produk_' . $produk->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['jumlah'] += $request->jumlah_pesanan;
            $cart[$cartKey]['total_harga'] = $cart[$cartKey]['jumlah'] * $cart[$cartKey]['harga'];
        } else {
            $cart[$cartKey] = [
                'nama' => $produk->nama,
                'stok' => $produk->stok,
                'harga' => $produk->harga,
                'jumlah' => $request->jumlah_pesanan,
                'total_harga' => $total_harga_pesanan,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('produk.detail', $id)->with('success', 'Menu berhasil ditambahkan ke dalam Pesanan!');
    }
}