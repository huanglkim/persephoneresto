<?php

namespace App\Http\Controllers;

use App\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
{
    $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 0)->first();

    if (!$pesanan) {
        return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
    }

    // Total harga diambil dari objek $pesanan yang sudah diperbarui di KeranjangController
    $total_harga = $pesanan->total_harga;
    return view('menu.checkout', compact('pesanan', 'total_harga'));
}

public function bayar(Request $request)
{
    $validatedData = $request->validate([
        'nomormeja' => 'required|string',
        'namapemesan' => 'required|string',
        'metode_pembayaran' => 'required|string|in:tunai,bank',
        'total_bayar' => 'required_if:metode_pembayaran,tunai|integer|min:' . $request->total_harga,
    ]);

    try {
        DB::beginTransaction();

        $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 0)->first();

        if (!$pesanan) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        // Update pesanan dengan data yang divalidasi
        $pesanan->no_meja = $validatedData['nomormeja'];
        $pesanan->nama_pemesan = $validatedData['namapemesan'];
        $pesanan->metode_pembayaran = $validatedData['metode_pembayaran'];
        $pesanan->status = 1;

        if ($validatedData['metode_pembayaran'] === 'tunai') {
            $pesanan->total_bayar = $validatedData['total_bayar'];
            // Jika metode pembayaran tunai, hitung kembalian
            $pesanan->kembalian = $validatedData['total_bayar'] - ($pesanan->total_harga);
        } else {
            $pesanan->total_bayar = $pesanan->total_harga;
            $pesanan->kembalian = 0;
        }

        $pesanan->save();

        DB::commit();

        return redirect()->route('checkout.sukses')->with('success', 'Pesanan berhasil dibayar.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal memproses pembayaran: ' . $e->getMessage());

        // Penanganan error yang lebih spesifik
        if ($e instanceof \Illuminate\Database\QueryException) {
            return redirect()->route('checkout')->with('error', 'Terjadi kesalahan pada database.');
        } else {
            return redirect()->route('checkout')->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }
}
}
