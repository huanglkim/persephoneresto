<?php

namespace App\Http\Controllers;

use App\Pesanan;
use App\PesananDetail;
use App\Produk;
use App\ProdukStatistik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;


class CheckoutSuksesController extends Controller
{
    public function index()
    {
        try {
            // Ambil pesanan dengan status '1' (sudah dibayar) yang terbaru untuk user yang sedang login
            $pesanan = Pesanan::where('user_id', Auth::id())
                ->where('status', 1)
                ->latest()
                ->firstOrFail();

            // Eager load relasi untuk efisiensi
            $pesanan_details = PesananDetail::with(['produk']) // Hanya eager load 'produk'
                ->where('pesanan_id', $pesanan->id)
                ->get();

            DB::transaction(function () use ($pesanan, $pesanan_details) {
                $now = Carbon::now();

                foreach ($pesanan_details as $detail) {
                    if ($detail->produk_id) { // Jika ini adalah produk
                        $produk = Produk::find($detail->produk_id);
                        if ($produk) {
                            // Kurangi stok produk
                            $produk->stok -= $detail->jumlah_pesanan;
                            $produk->save();

                            // Tambahkan atau update statistik produk
                            ProdukStatistik::updateOrCreate(
                                [
                                    'produk_id' => $produk->id,
                                    'bulan' => $now->month,
                                    'tahun' => $now->year,
                                ],
                                [
                                    'jumlah_terjual' => DB::raw('jumlah_terjual + ' . $detail->jumlah_pesanan),
                                ]
                            );
                        }
                    }
                    // Tidak ada penanganan untuk topping lagi
                }

                // Hapus detail pesanan setelah stok diproses
                PesananDetail::where('pesanan_id', $pesanan->id)->delete();

                // Update status pesanan menjadi selesai (2)
                $pesanan->status = 2;
                $pesanan->save();
            });

            return view('menu.checkout_sukses', compact('pesanan', 'pesanan_details'));
        } catch (ModelNotFoundException $e) {
            // Handle jika pesanan tidak ditemukan
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        } catch (\Exception $e) {
            // Handle exception lainnya
            Log::error('Error in CheckoutSuksesController: ' . $e->getMessage());
            DB::rollback();
            return redirect()->route('home')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
