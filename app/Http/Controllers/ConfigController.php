<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use Illuminate\Http\Request;
use PDO;

class ConfigController extends Controller
{
    public function index(){
        return view('config.index');
    }
    public function get(Request $request, $mode = ''){
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

        $res = API::get('config', $body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        $rows = !empty($sources) ? $sources->rows : [];

        if($mode == 'datatable'){
            $data = [];
            $no = 1 + $request->start;
            $info_count = 0;
            foreach($rows as $row){
                $info = ['kebijakan-privasi','syarat-ketentuan','tentang-aplikasi'];
                if(!in_array($row->name,$info)){
                    $d = [];
                    $d[] = $no;
                    $d[] = $row->name;
                    $d[] = $row->value;
                    $d[] = $row->desc;
                    $buttons = '<a href="'. route('config.edit', [$row->id_config]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>';
                    $d[] = $buttons;
                    $data[] = $d;
                    $no++;
                }else{
                    $info_count ++;
                }
            }

            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count-$info_count;
            $result['recordsFiltered'] = $sources->count-$info_count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Sistem Kerja'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_config,
                    'text' => $row->name
                ];
            }
        }

        return response()->json($result);
    }

    public function edit($id){
        $res = API::get('config/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('config.form')->with([
                'config' => $data->data
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function update(Request $request,$id){
        $input = $request->all();

        $custom_msg = [
            'value.required' => 'Value harus diisi!.',
        ];

        $validation = \Validator::make($input, [
            "value" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());

        $res = API::post('config/'.$id, $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            if(@$request->is_redirect == "false"){
                return response()->json($data);
            }
            return redirect()->route('config.index')->with('success', $data->message);
        }
        if(@$request->is_redirect == 'false'){
            return response()->json([
                'error_api' => $data->message ?? '',
                'errors_api' => $data->errors ?? []
            ]);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function show($name){
        $res = API::get('config/search/'.$name);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('config.form-info')->with([
                'config' => $data->data
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function kebijakanPrivasi(){
        $res = API::get('config/search/kebijakan-privasi');
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('config.kebijakan-privasi')->with([
                'config' => $data->data
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function destroy($id){
        $res = API::delete('config/'.$id);
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
