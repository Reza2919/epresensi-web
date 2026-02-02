<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class StatusPegawaiController extends Controller
{
    public function index(){
        return view('satker.index');
    }
    public function get(Request $request, $mode = ''){
        if(empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];
        $params = [
            'page' => $mode == 'datatable' ? ($start/$length) + 1 : 1,
            'limit' => $length
        ];
        
        $res = SIAP::post('ws_naker_data/data_master_statuspegawai',$params);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;
        $totalData = $content->count;
        $rows = !empty($sources) ? $sources : [];
        
        if($mode == 'datatable'){
            $data = [];
            $no = 1 + $request->start;
            foreach($rows as $row){
                if(in_array($row->statuspegawaiid,[1,2])){
                    $d = [];
                    $d[] = $no;
                    $d[] = $row->statuspegawai;
                    $buttons = '';
                    $d[] = $buttons;
                    $data[] = $d;
                    $no++;
                }else{
                    $totalData--;
                }
            }
            $result['data'] = $data;
            $result['recordsTotal'] = $totalData;
            $result['recordsFiltered'] = $totalData;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '-', 'text' => 'Semua Status'];
            foreach($rows as $row){
                if(in_array($row->statuspegawaiid,[1,2])){
                    $result['data'][] = [
                        'id' => $row->statuspegawaiid,
                        'text' => $row->statuspegawai
                    ];
                }
            }
        }

        return response()->json($result);
    }
    
    
}
