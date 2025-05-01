<?php

namespace App\Http\Controllers;
use App\ProdukStatistik;

use Illuminate\Http\Request;

class ProdukStatistikController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar tahun unik dari tabel untuk dropdown filter
        $tahunList = ProdukStatistik::select('tahun')->distinct()->pluck('tahun');

        // Default bulan dan tahun ke sekarang jika tidak diset
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // Ambil data statistik sesuai filter
        $statistiks = ProdukStatistik::with('produk')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderByDesc('jumlah_terjual')
            ->get();

        return view('actual.favitem', compact('statistiks', 'bulan', 'tahun', 'tahunList'));
    }
}