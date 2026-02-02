<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class PeriodeTukinController extends Controller
{
    public function index(){
        return view('periode.index');
    }

    public function get(Request $request, $mode = ''){
        $user = session('userdata');
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

        $res = API::get('periode', $body);
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
                $d[] = $row->periode;
                $d[] = "<i class='badge badge-".($row->is_active == 1 ? 'success' : 'warning')."'>".($row->is_active == 1 ? 'Aktif' : 'Tidak Aktif')."</i> ";
                $buttons = '<a href="'. route('periode.edit', [$row->id_periode]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>';
                $buttons .= '
                    <a href="'. route('periode.destroy', [$row->id_periode]) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"> </i> </a>';
                if($row->is_active == 1){
                    $buttons .= '
                    <a href="'. route('periode.set-active', [$row->id_periode]) .'" class="btn btn-icon btn-outline-warning btn-active" title="Nonaktifkan"><i data-feather="x"></i> </a>';
                }else{
                    $buttons .= '
                    <a href="'. route('periode.set-active', [$row->id_periode]) .'" class="btn btn-icon btn-outline-success btn-active" title="Aktifkan"><i data-feather="check"></i> </a>';
                }
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Periode Tukin'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_periode,
                    'text' => $row->periode
                ];
            }
        }

        return response()->json($result);
    }

    public function create(){
        return view('periode.form');
    }

    public function edit($id){
        $res = API::get('periode/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('periode.form')->with([
                'periode' => $data->data,
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function store(Request $request){
        $input = $request->all();

        $custom_msg = [
            'periode.required' => 'Nama sistem kerja harus diisi!.',
        ];

        $validation = \Validator::make($input, [
            "periode" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());

        $res = API::post('periode/create', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('periode.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function update(Request $request,$id){
        $input = $request->all();

        $custom_msg = [
            'periode.required' => 'Nama sistem kerja harus diisi!.',
        ];

        $validation = \Validator::make($input, [
            "periode" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());

        $res = API::post('periode/'.$id, $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('periode.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function destroy($id){
        $res = API::delete('periode/'.$id);
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

    public function setActive($id){
        $res = API::post('periode/'.$id.'/set-active',[]);
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
