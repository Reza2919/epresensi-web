<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatkerController extends Controller
{
    public function index()
{
    return view('satker.index');
}
   public function get(Request $request, $mode = '')
{
    if (empty($mode)) {
        $mode = 'datatable';
    }

    $query = DB::table('satker');

$search = $request->search['value'] ?? '';

if (!empty($search)) {

    $query->where(function($q) use ($search){

        $q->where('kode_satker', 'ILIKE', "%{$search}%")
          ->orWhere('nama_satker', 'ILIKE', "%{$search}%");

    });

}

$rows = $query->get();

    if ($mode == 'datatable') {

        $data = [];
        $no = 1;

        foreach ($rows as $row) {

            if (strlen($row->kode_satker) <= 6) {

                $buttons = '
                <button class="btn btn-sm btn-primary btn-detail"
                    data-id="'.$row->kode_satker.'"
                    title="Satker User">
                    <i data-feather="list"></i>
                </button>

                <button class="btn btn-sm btn-warning btn-setting"
                    data-id="'.$row->kode_satker.'"
                    data-satker="'.$row->nama_satker.'"
                    title="Setting">
                    <i data-feather="clock"></i>
                </button>

                <button class="btn btn-sm btn-danger btn-koordinat"
                    data-id="'.$row->kode_satker.'"
                    data-satker="'.$row->nama_satker.'"
                    title="Koordinat">
                    <i data-feather="map-pin"></i>
                </button>
                ';

                $data[] = [
                    $no++,
                    $row->kode_satker,
                    $row->nama_satker,
                    $buttons
                ];
            }
        }

        return response()->json([
            'draw' => intval($request->draw ?? 1),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ]);
    }

    $result = [
        'data' => []
    ];

    foreach ($rows as $row) {

        if (strlen($row->kode_satker) <= 6) {

            $result['data'][] = [
                'id' => $row->kode_satker,
                'text' => $row->nama_satker
            ];
        }
    }

    return response()->json($result);
}
}