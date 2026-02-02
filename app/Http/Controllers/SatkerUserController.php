<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class SatkerUserController extends Controller
{
    public function store(Request $request){
        $input = $request->all();
        if(strlen($input['id_satker']) > 6){
            $input['id_satker'] = substr($input['id_satker'],0,6);
        }
        
        $custom_msg = [
            'id_pegawai.required' => 'Harap pilih pegawai!.',
            'role.required' => 'Role harus diisi.',
            'id_satker.required' => 'Satker harus dipilih.',
        ];
        $validation = \Validator::make($input, [
            "role" => "required",
            "id_pegawai" => "required",
            "id_satker" => "required",
        ], $custom_msg);

        if($validation->fails()) return response()->json($validation->errors());
        
        $res = API::post('satker-user/create', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($res->getStatusCode() == 200){
            return response()->json([
                'success'=>true,
                'message' => $data->message
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message' => $res->getStatusCode()
            ]);
        }
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

    public function getBySatkerId($id){
        $res = API::get('satker-user/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return $data->data;
        }
        return [];
    }


    public function destroy($id){
        $res = API::delete('satker-user/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        return response()->json([
            'code' => $data->code,
            'message' => $data->message,
            'error' => $data->code == 200 ? false : true,
            'error_api' => $data->code == 200 ? null : $data->message,
            'errors_api' => $data->code == 200 ? [] : $data->errors
        ]);
    }
}
