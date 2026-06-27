<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class SatkerUserController extends Controller
{
    public function store(Request $request)
{
    return response()->json([
        'success' => true,
        'message' => 'User Satker berhasil ditambahkan'
    ]);
}
    public function getSatkerUser(Request $request, $mode = ''){
        if(empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,   
            'search' => $search,
        ];
        
        $res = API::get('satker-user',$body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;
        $rows = !empty($sources) ? $sources->rows : [];
        if($mode == 'datatable'){
            $data = [];
            $no = 1 + $request->start;
            foreach($rows as $row){
                
                $d = [];
                $d[] = $no;
                $d[] = $row->nama;
                $role = ['tu' => 'Tata Usaha','pimpinan' => 'Pimpinan'];
                $d[] = $role[$row->role];
                $button ='';
                if($row->id_pegawai != session('userdata')->id_pegawai){
                    $button = '<a href="'.url('satker-user').'/'.$row->id_satker_user.'/delete" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i data-feather="trash"></a>';
                }
                $d[] = $button;
                $data[] = $d;
                $no++;
            }
    
            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Satker User'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_pegawai,
                    'text' => $row->nama
                ];
            }
        }

        return response()->json($result);
    }

    public function getBySatkerId($id)
{
    return [
        (object)[
            'id_satker_user' => 1,
            'nama' => 'Muhammad Reza',
            'role' => 'tu'
        ],
        (object)[
            'id_satker_user' => 2,
            'nama' => 'Budi Santoso',
            'role' => 'pimpinan'
        ]
    ];
}


   public function destroy($id)
{
    return response()->json([
        'code' => 200,
        'message' => 'User Satker berhasil dihapus',
        'error' => false
    ]);
}
}
