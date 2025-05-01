<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Pesanan;
use App\PesananDetail;
use Illuminate\Support\Facades\Auth;

class NavbarController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        $jumlah_pesanan = 0; // Default ke 0

        // Mengecek apakah user login atau tidak
        if (Auth::check()) {
            // Jika sudah login, ambil pesanan dari database
            $pesanan = Pesanan::where('user_id', Auth::user()->id)
                ->where('status', 0) // Status pesanan yang belum selesai
                ->first();

            if ($pesanan) {
                // Periksa apakah ada item di pesanan detail
                $pesananDetailCount = PesananDetail::where('pesanan_id', $pesanan->id)->count();

                if ($pesananDetailCount > 0 && $pesanan->status == 0) {
                    $jumlah_pesanan = $pesananDetailCount;
                } else {
                    $jumlah_pesanan = 0; // Setel jumlah pesanan menjadi 0 jika tidak ada item atau status pesanan adalah 1
                }
            }
        }

        return view('layouts1.navbar', compact('menus', 'jumlah_pesanan'));
    }
}