<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Produk;
use App\ProdukStatistik;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $produks = Produk::where('jenis', 'Special')->paginate(4);
        $menus = Menu::all();

        // Ambil bulan dan tahun sekarang
        $now = Carbon::now();
        $bulan = $now->month;
        $tahun = $now->year;

        // Ambil top 6 produk berdasarkan jumlah_terjual bulan ini
        $topProduk = ProdukStatistik::with('produk')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderByDesc('jumlah_terjual')
            ->take(6)
            ->get();

        return view('home', compact('produks', 'menus', 'topProduk', 'bulan', 'tahun'));
    }
}
