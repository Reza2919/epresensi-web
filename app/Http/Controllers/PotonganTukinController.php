<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PotonganTukinController extends Controller
{
   public function get(Request $request, $mode = '', $id)
{
    if($mode == 'datatable'){

    $rows = DB::table('potongan_tukin')
        ->where('id_pegawai', $id)
        ->orderBy('tanggal', 'desc')
        ->get();

    $data = [];
    $no = 1;

    foreach($rows as $row){

        $data[] = [
            $no++,
            date('d-m-Y', strtotime($row->tanggal)),
            number_format($row->jumlah_potongan,0,',','.'),
            $row->keterangan,

            '<button class="btn btn-warning btn-sm btn-edit"
                data-id="'.$row->id_potongan_tukin.'"
                data-tanggal="'.$row->tanggal.'"
                data-jumlah_potongan="'.$row->jumlah_potongan.'"
                data-keterangan="'.$row->keterangan.'">
                Edit
            </button>'
        ];
    }

    return response()->json([
        'draw' => intval($request->draw ?? 1),
        'recordsTotal' => count($data),
        'recordsFiltered' => count($data),
        'data' => $data
    ]);
}

    return response()->json([
        'data' => []
    ]);
}
    public function getRekap()
{
    return response()->json([
        'tukin' => 5000000,
        'potongan' => 75000,
        'total' => 4925000  
    ]);
}
     
    public function store(Request $request)
{
    return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Potongan berhasil disimpan (dummy)'
    ]);
}
    

    public function destroy($id)
{
    return response()->json([
        'code' => 200,
        'message' => 'Potongan berhasil dihapus (dummy)',
        'error' => false,
        'error_api' => null,
        'errors_api' => []
    ]);
}
}
