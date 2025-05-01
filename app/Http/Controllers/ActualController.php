<?php

namespace App\Http\Controllers;

use App\Pesanan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActualController extends Controller
{
    public function index(Request $request)
    {
        $bulanFilter = $request->bulan;
        $tahunFilter = $request->tahun;

        // Query untuk mengambil data dengan kondisi bulan dan tahun
        $query = Pesanan::select(DB::raw('MONTH(created_at) as bulan'), DB::raw('YEAR(created_at) as tahun'), 'user_id', DB::raw('SUM(total_harga) as hasil'))
            ->when($bulanFilter, function ($q) use ($bulanFilter) {
                // Mengkonversi nama bulan ke nomor bulan (misalnya 'Januari' -> 1)
                $bulanNumber = Carbon::createFromFormat('F', $bulanFilter)->month;
                $q->whereMonth('created_at', $bulanNumber);
            })
            ->when($tahunFilter, function ($q) use ($tahunFilter) {
                // Filter berdasarkan tahun
                $q->whereYear('created_at', $tahunFilter);
            })
            ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'), 'user_id')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        // Map untuk mengubah bulan menjadi nama bulan dan mendapatkan nama user
        $actuals = $query->map(function ($item) {
            // Menampilkan nama bulan (misalnya 1 -> Januari)
            $item->bulan_nama = Carbon::create()->month($item->bulan)->locale('id')->translatedFormat('F');
            // Ambil nama user berdasarkan user_id
            $item->user_nama = User::find($item->user_id)->name ?? 'Unknown';
            return $item;
        });

        // Pagination manual
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($actuals->forPage($currentPage, $perPage), $actuals->count(), $perPage, $currentPage, ['path' => request()->url(), 'query' => request()->query()]);

        return view('actual.actual', ['actuals' => $paginated]);
    }

    // Controller - chartactual method

    public function chartactual(Request $request)
    {
        $bulanFilter = $request->bulan;
        $tahunFilter = $request->tahun;

        // Array bulan dalam bahasa Indonesia dan bahasa Inggris
        $bulanIndoToEng = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December',
        ];

        // If the selected month is in Indonesian, convert it to English
        if ($bulanFilter && !is_numeric($bulanFilter) && isset($bulanIndoToEng[$bulanFilter])) {
            $bulanFilter = $bulanIndoToEng[$bulanFilter];
        }

        // If the selected month is numeric (e.g., 4 for April), keep it as is
        if ($bulanFilter && is_numeric($bulanFilter)) {
            $bulanFilter = Carbon::create()->month($bulanFilter)->format('F'); // Use the numeric month
        }

        // Generate an array for all dates in the selected month (as day numbers)
        $datesInMonth = [];
        $firstDayOfMonth = null;
        $lastDayOfMonth = null;

        if ($bulanFilter) {
            $firstDayOfMonth = Carbon::create()->month($bulanFilter)->year($tahunFilter ?: now()->year)->startOfMonth();
            $lastDayOfMonth = Carbon::create()->month($bulanFilter)->year($tahunFilter ?: now()->year)->endOfMonth();

            while ($firstDayOfMonth->lte($lastDayOfMonth)) {
                $datesInMonth[] = $firstDayOfMonth->format('d'); // Hanya ambil angka hari
                $firstDayOfMonth->addDay();
            }
        } else {
            // If no month is selected, default to the current month
            $firstDayOfMonth = now()->startOfMonth();
            $lastDayOfMonth = now()->endOfMonth();
            while ($firstDayOfMonth->lte($lastDayOfMonth)) {
                $datesInMonth[] = $firstDayOfMonth->format('d'); // Hanya ambil angka hari
                $firstDayOfMonth->addDay();
            }
            $bulanFilter = now()->format('F');
        }

        // Query the database to get sales data by day for each user in the selected month
        $query = Pesanan::select(DB::raw('DAY(created_at) as day'), DB::raw('MONTH(created_at) as bulan'), DB::raw('YEAR(created_at) as tahun'), 'user_id', DB::raw('SUM(total_harga) as hasil'))
            ->when($bulanFilter, function ($q) use ($bulanFilter) {
                $bulanNumber = Carbon::createFromFormat('F', $bulanFilter)->month;
                $q->whereMonth('created_at', $bulanNumber);
            })
            ->when($tahunFilter, function ($q) use ($tahunFilter) {
                $q->whereYear('created_at', $tahunFilter);
            })
            ->groupBy(DB::raw('DAY(created_at)'), DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'), 'user_id')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('day', 'asc') // Sort by day
            ->get();

        // Map the sales data
        $sales = $query->map(function ($item) {
            $item->bulan_nama = Carbon::create()->month($item->bulan)->locale('id')->translatedFormat('F');
            $item->hari_nama = Carbon::create()->day($item->day)->locale('id')->translatedFormat('l');
            $item->user_nama = User::find($item->user_id)->name ?? 'Unknown';
            return $item;
        });

        // Prepare data for the chart, ensuring all dates in the month are present
        // Prepare data for the chart, ensuring all dates in the month are present
        $chartData = [];
        $users = $sales->pluck('user_nama')->unique()->values();

        foreach ($users as $user) {
            $userData = [];
            foreach ($datesInMonth as $dayNumber) {
                $sale = $sales->where('user_nama', $user)->where('day', (int) $dayNumber)->first();
                $userData[] = $sale ? $sale->hasil : 0;
            }
            $chartData[$user] = $userData;
        }

        $bulan = $bulanFilter ?: 'Semua Bulan';
        return view('actual.bar', compact('chartData', 'bulan', 'datesInMonth', 'users'));
    }
}
