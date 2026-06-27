<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use Illuminate\Http\Request;

class SatkerKoordinatController extends Controller
{
   public function getSatkerKoordinat(Request $request,$id)
{
    return [
        (object)[
            'id_satker_koordinat' => 1,
            'keterangan' => 'Kantor Pusat',
            'lat' => '-6.2088',
            'long' => '106.8456'
        ],
        (object)[
            'id_satker_koordinat' => 2,
            'keterangan' => 'Gedung Annex',
            'lat' => '-6.2100',
            'long' => '106.8465'
        ]
    ];
}
    public function store(Request $request)
{
    return response()->json([
        'success' => true,
        'message' => 'Koordinat berhasil ditambahkan'
    ]);
}
   public function destroy($id)
{
    return response()->json([
        'code' => 200,
        'message' => 'Koordinat berhasil dihapus',
        'error' => false
    ]);
}
}
