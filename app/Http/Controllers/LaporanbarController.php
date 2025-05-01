<?php

namespace App\Http\Controllers;

use App\Pesanan;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanbarController extends Controller
{
    public function index()
    {
        return view('actual.laporanbar'); // Asumsi nama view
    }

    public function filterPendapatan(Request $request)
    {
        $bulan_awal = Carbon::createFromFormat('Y-m', $request['bulan_awal']);
        $bulan_akhir = Carbon::createFromFormat('Y-m', $request['bulan_akhir']);

        // Ambil daftar user yang memiliki pesanan dalam rentang waktu tersebut
        $users = User::whereIn('id', Pesanan::whereBetween('created_at', [$bulan_awal->startOfMonth(), $bulan_akhir->endOfMonth()])
            ->pluck('user_id')
            ->unique()
        )->get();

        // Buat daftar bulan dalam rentang waktu tersebut
        $bulan = [];
        $startDateClone = clone $bulan_awal;
        while ($startDateClone->lte($bulan_akhir)) {
            $bulan[] = $startDateClone->format('F Y');
            $startDateClone->addMonth();
        }

        // Inisialisasi array sales untuk menyimpan total harga setiap user per bulan
        $sales = [];

        foreach ($users as $user) {
            $username = $user->name;
            $datapd = array_fill(0, count($bulan), 0); // Inisialisasi semua bulan dengan 0

            foreach ($bulan as $index => $periode) {
                $tanggal = Carbon::createFromFormat('F Y', $periode);

                // Query untuk mengambil total harga per user per bulan
                $pd = Pesanan::where('user_id', $user->id)
                    ->whereMonth('created_at', $tanggal->month)
                    ->whereYear('created_at', $tanggal->year)
                    ->sum('total_harga');

                $datapd[$index] = $pd ?: 0; // Jika tidak ada total harga, set ke 0
            }

            $sales[] = [
                'label' => $username,
                'data' => $datapd,
            ];
        }

        // **Tambahkan semua bulan ke data meskipun tidak ada user yang memiliki pesanan**
        $data = [];
        foreach ($bulan as $index => $periode) {
            $data[$periode] = [];

            foreach ($sales as $sale) {
                $data[$periode][$sale['label']] = $sale['data'][$index] ?? 0;
            }

            // **Tambahkan bulan kosong jika tidak ada user sama sekali**
            if (empty($sales)) {
                $data[$periode]['Tidak Ada Data'] = 0;
            }
        }

        return response()->json(['data' => $data]);
    }
}