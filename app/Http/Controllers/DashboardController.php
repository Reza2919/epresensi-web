<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard');
    }

    public function getStatistic()
    {
        return response()->json([
            [
                'nama_sistem_kerja' => 'WFO',
                'count' => 4
            ],
            [
                'nama_sistem_kerja' => 'WFH',
                'count' => 2
            ],
            [
                'nama_sistem_kerja' => 'Hybrid',
                'count' => 2
            ],
            [
                'nama_sistem_kerja' => 'Dinas Luar',
                'count' => 1
            ],
            [
                'nama_sistem_kerja' => 'Cuti',
                'count' => 1
            ]
        ]);
    }
}