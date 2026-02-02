<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class SatkerController extends Controller
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
            // 'page' => $mode == 'datatable' ? ($start/$length) + 1 : 1,
            // 'limit' => $length, 
            'page' =>  1,
            'limit' => 1000, 
            'satker' => $search['value']
        ];
        
        $res = SIAP::get('ws_naker_data/get_satker?page='.$params['page'].'&limit='.$params['limit'].'&satker='.$params['satker']);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;
        $totalData = $content->total_data;
        $rows = !empty($sources) ? $sources : [];
        
        if($mode == 'datatable'){
            $data = [];
            $no = 1 + $request->start;
            foreach($rows as $row){
                if(strlen($row->satkerid) <= '6'){
                    $satker_user = $this->getSatkerUser($row->satkerid);
                    $d = [];
                    $d[] = $no;
                    $d[] = $row->satkerid;
                    $d[] = $row->satker;
                    $buttons = '';
                    $buttons .= '<button class="btn btn-sm btn-primary btn-detail" data-id="'.$row->satkerid.'" title="Satker User"><i data-feather="list"></i></button>';
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-setting" data-satker="'.$row->satker.'" data-id="'.$row->satkerid.'" title="Satker Setting"><i data-feather="clock"></i></button>';
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-koordinat" data-satker="'.$row->satker.'" data-id="'.$row->satkerid.'" title="Koordinat Setting"><i data-feather="map-pin"></i></button>';
                    $buttons .= '
                    <a href="'.url('koordinator/'.$row->satkerid.'/detail').'" class="btn btn-info btn-sm mr-1mb-1" title="Koordinator"><i data-feather="users"></i></a>';
                    if($satker_user->count > 0){
                        $role = [];
                        foreach ($satker_user->rows as $key => $user) {
                            if(!@$role[$user->role]){
                                $buttons .= '
                                <span class="badge badge-success"><i data-feather="check"></i> '.strtoupper($user->role).'</span>';

                                $role[$user->role] = '
                                <span class="badge badge-success"><i data-feather="check"></i> '.strtoupper($user->role).'</span>';
                            }
                        }
                    }
                    $d[] = $buttons;
                    $data[] = $d;
                    $no++;
                }
                
            }
    
            $result['data'] = $data;
            $result['recordsTotal'] = $no;
            $result['recordsFiltered'] = $no;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Satker'];
            foreach($rows as $row){
                if(strlen($row->satkerid) <= '6'){
                    $result['data'][] = [
                        'id' => $row->satkerid,
                        'text' => $row->satker
                    ];
                }
            }
        }

        return response()->json($result);
    }
    
    private function getSatkerUser($id){
        $res = API::get('satker-user/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return $data->data;
        }
        return [];

    }
    
}
