<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    public function get(Request $request, $mode = ''){
        if(empty($mode)) $mode = 'datatable';
        $user = session('userdata');

        $satkerid = $user->satkerid;
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,   
            'search' => $search,
        ];
        
        if($satkerid) $body['satker'] = $satkerid;
        $res = API::get('bidang',$body);
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
                $d[] = $row->nama_bidang;
                $buttons = '<a href="'. route('bidang.show', [$satkerid,$row->id_satker_bidang]) .'" class="btn btn-icon btn-info" title="Data Pegawai"><i data-feather="users"></i> </a>';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }
    
            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Satker'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_satker_bidang,
                    'text' => $row->nama_bidang
                ];
            }
        }

        return response()->json($result);
    }
}
