<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapPresensiBulananExport implements FromView
{
    protected $data = [];
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('report.rekap-presensi', [
            'data' => $this->data
        ]);
    }
}