<?php

namespace App\Http\Controllers;

use App\Pesanan;
use App\PesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KeranjangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $pesanan = Pesanan::where('user_id', Auth::user()->id)
            ->where('status', 0)
            ->first();

        if ($pesanan) {
            $pesanan_details = PesananDetail::with('produk', 'topping')->where('pesanan_id', $pesanan->id)->get();

            $total_harga = 0;
            foreach ($pesanan_details as $detail) {
                if ($detail->produk) {
                    $total_harga += $detail->jumlah_pesanan * $detail->produk->harga;
                } elseif ($detail->topping) {
                    $total_harga += $detail->jumlah_pesanan * $detail->topping->harga;
                }
                // Tambahkan logika untuk harga toppingset jika ada
                if ($detail->toppingset && $detail->produk && isset($detail->produk->harga_toppingset)) {
                    $total_harga += $detail->jumlah_pesanan * $detail->produk->harga_toppingset;
                }
            }

            $pesanan->total_harga = $total_harga;
            $pesanan->save();

            return view('menu.keranjang', compact('pesanan_details', 'total_harga', 'pesanan'));
        }

        return view('menu.keranjang', ['pesanan_details' => collect(), 'total_harga' => 0, 'pesanan' => null]);
    }

    public function hapus(PesananDetail $pesanan_detail)
    {
        try {
            $pesanan = Pesanan::find($pesanan_detail->pesanan_id);

            if ($pesanan) {
                $pesanan_detail->delete();

                $pesananDetailCount = PesananDetail::where('pesanan_id', $pesanan->id)->count();

                if ($pesananDetailCount === 0) {
                    $pesanan->delete();
                }
                // Perbarui total harga pesanan setelah menghapus item
                $total_harga = 0;
                $details = PesananDetail::with('produk', 'topping')->where('pesanan_id', $pesanan->id)->get(); // Load relasi
                foreach ($details as $detail) {
                    if ($detail->produk) {
                        $total_harga += $detail->jumlah_pesanan * $detail->produk->harga;
                    } elseif ($detail->topping) {
                        $total_harga += $detail->jumlah_pesanan * $detail->topping->harga;
                    }
                    // Tambahkan logika untuk harga toppingset jika ada
                    if ($detail->toppingset && $detail->produk && isset($detail->produk->harga_toppingset)) {
                        $total_harga += $detail->jumlah_pesanan * $detail->produk->harga_toppingset;
                    }
                }
                $pesanan->total_harga = $total_harga;
                $pesanan->save();

                return redirect()->route('keranjang')->with('success', 'Pesanan berhasil dihapus.');
            } else {
                return redirect()->route('keranjang')->with('error', 'Pesanan tidak ditemukan.');
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pesanan: ' . $e->getMessage());
            return redirect()->route('keranjang')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, PesananDetail $pesanan_detail)
    {
        $request->validate([
            'jumlah_pesanan' => 'required|integer|min:1',
            'topping' => 'nullable|string|max:255'
        ]);

        try {
            $pesanan_detail->jumlah_pesanan = $request->jumlah_pesanan;
            if ($pesanan_detail->toppingset) {
                $pesanan_detail->topping = $request->topping ?? '-';
            }

            // Hitung ulang total harga termasuk topping
            $harga_produk = $pesanan_detail->produk ? $pesanan_detail->produk->harga : 0;
            $harga_topping = $pesanan_detail->topping ? $pesanan_detail->topping->harga : 0; // Ini salah, topping tidak punya relasi harga langsung di pesanan detail
            $harga_topping_set = ($pesanan_detail->toppingset && $pesanan_detail->produk && isset($pesanan_detail->produk->harga_toppingset)) ? $pesanan_detail->produk->harga_toppingset : 0;

            $total_harga_detail = $pesanan_detail->jumlah_pesanan * ($harga_produk + $harga_topping_set);
            $pesanan_detail->total_harga = $total_harga_detail; // Simpan total harga per detail
            $pesanan_detail->save();

            // Update total harga di tabel pesanan.
            $pesanan = Pesanan::find($pesanan_detail->pesanan_id);
            if ($pesanan) {
                $total_harga_pesanan = 0;
                $details = PesananDetail::with('produk', 'topping')->where('pesanan_id', $pesanan->id)->get();
                foreach ($details as $detail) {
                    if ($detail->produk) {
                        $total_harga_pesanan += $detail->jumlah_pesanan * $detail->produk->harga;
                    } elseif ($detail->topping) {
                        $total_harga_pesanan += $detail->jumlah_pesanan * $detail->topping->harga;
                    }
                    if ($detail->toppingset && $detail->produk && isset($detail->produk->harga_toppingset)) {
                        $total_harga_pesanan += $detail->jumlah_pesanan * $detail->produk->harga_toppingset;
                    }
                }
                $pesanan->total_harga = $total_harga_pesanan;
                $pesanan->save();
            }

            return redirect()->route('keranjang')->with('success', 'Pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update pesanan: ' . $e->getMessage());
            return redirect()->route('keranjang')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

class CheckoutController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 0)->first();

        if (!$pesanan) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        }

        $total_harga = $pesanan->total_harga;

        return view('menu.checkout', compact('pesanan', 'total_harga'));
    }

    public function bayar(Request $request)
    {
        $validatedData = $request->validate([
            'nomormeja' => 'required|string',
            'namapemesan' => 'required|string',
            'metode_pembayaran' => 'required|string|in:tunai,bank',
            'total_bayar' => 'required_if:metode_pembayaran,tunai|integer|min:' . ($request->total_harga),
        ]);

        try {
            DB::beginTransaction();

            $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 0)->first();

            if (!$pesanan) {
                return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
            }

            $pesanan->no_meja = $validatedData['nomormeja'];
            $pesanan->nama_pemesan = $validatedData['namapemesan'];
            $pesanan->metode_pembayaran = $validatedData['metode_pembayaran'];
            $pesanan->status = 1;

            if ($validatedData['metode_pembayaran'] === 'tunai') {
                $pesanan->total_bayar = $validatedData['total_bayar'];
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

            if ($e instanceof \Illuminate\Database\QueryException) {
                return redirect()->route('checkout')->with('error', 'Terjadi kesalahan pada database.');
            } else {
                return redirect()->route('checkout')->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
            }
        }
    }
}