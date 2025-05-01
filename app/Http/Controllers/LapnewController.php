<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Laporan;

class LapnewController extends Controller
{
    public function laporanpjbaru()
    {
        $users = User::all();
        return view('actual.laporanpjbaru', compact(['users']));
    }
    public function datalaporanpjbaru(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $user_id = $request->user_id;
        $lg = Laporan::where('user_id', $user_id)
        ->first();
        return $user_id;
        // User::with(['laporans' => function($query) {
        //     $query->orderBy('tanggal', 'desc');
        // }])->get();
        return view('actual.datalaporanpjbaru', compact(['laporansGrouped']));
    }
}
