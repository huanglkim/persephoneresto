<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Imports\LaporanImport;
use App\Pesanan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Display the data pesanan dengan filter
    public function index(Request $request)
    {
        $users = User::all();
        $months = Pesanan::select(DB::raw('MONTH(created_at) as month'))->distinct()->orderBy('month')->get();
        $years = Pesanan::select(DB::raw('YEAR(created_at) as year'))->distinct()->orderBy('year', 'desc')->get();

        $pesanans = Pesanan::with('user');

        if (!empty($request->user_id)) {
            $pesanans->whereHas('user', function ($q) use ($request) {
                $q->where('name', $request->user_id);
            });
        }

        if (!empty($request->month)) {
            $pesanans->whereMonth('created_at', $request->month);
        }

        if (!empty($request->year)) {
            $pesanans->whereYear('created_at', $request->year);
        }

        $dataPesanans = $pesanans->get();

        return view('actual.laporan', compact('users', 'months', 'years', 'dataPesanans'));
    }

    public function laporanexport(Request $request)
    {
        return Excel::download(new LaporanExport(
            $request->user_id,
            $request->month,
            $request->year
        ), 'laporan_pesanan.xlsx');
    }

    public function laporanimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        DB::beginTransaction();
        try {
            Excel::import(new LaporanImport(), $file);
            DB::commit();
            return response()->json(['success' => 'Data Pesanan Berhasil Ditambahkan!'], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errorMessage = [];
            foreach ($failures as $failure) {
                $row = $failure->row();
                $attribute = $failure->attribute();
                $errors = $failure->errors();
                $errorMessage[] = "Baris {$row}: Kolom {$attribute} - " . implode(', ', $errors);
            }
            return response()->json(['errors' => $errorMessage], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Data Pesanan Gagal: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat import data pesanan. Silakan coba lagi.'], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'created_at' => 'required|date',
            'total_harga' => 'required|numeric',
        ]);

        $pesanan = Pesanan::create($request->all());

        return response()->json([
            'success' => 'Data Pesanan Berhasil Ditambahkan!',
            'data' => $pesanan,
        ]);
    }

    public function print(Request $request)
    {
        $userId = $request->input('user_id');
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        $pesanans = Pesanan::with('user')
            ->when($userId && $userId != 'Semua', function ($query) use ($userId) {
                return $query->whereHas('user', function ($q) use ($userId) {
                    $q->where('name', $userId);
                });
            })
            ->when($selectedMonth && $selectedMonth != 'Semua', function ($query) use ($selectedMonth) {
                return $query->whereMonth('created_at', $selectedMonth);
            })
            ->when($selectedYear && $selectedYear != 'Semua', function ($query) use ($selectedYear) {
                return $query->whereYear('created_at', $selectedYear);
            })
            ->get();

        return view('actual.print', compact('pesanans', 'selectedMonth', 'selectedYear', 'userId'));
    }

    // public function printAll()
    // {
    //     $pesanans = Pesanan::with('user')->get();
    //     return view('actual.print_all', compact('pesanans'));
    // }

    // Function to generate chart for data pesanan
    public function chartlaporan(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
    
        $pesanans = Pesanan::with('user')
            ->when($month, fn($query) => $query->whereMonth('created_at', $month))
            ->when($year, fn($query) => $query->whereYear('created_at', $year))
            ->get()
            ->groupBy('user.name')
            ->map(fn($group) => $group->sum('total_harga'));
    
        $labels = $pesanans->keys()->toArray();
        $data = $pesanans->values()->toArray();
    
        return view('actual.doughnutlaporan', compact('labels', 'data'));
    }
    
}