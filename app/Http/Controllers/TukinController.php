<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class TukinController extends Controller
{
    public function index(){
        return view('tukin.index');
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
        
        $res = API::get('tukin',$body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;
        
        $rows = !empty($sources) ? $sources->rows : [];
        
        if($mode == 'datatable'){
            $data = [];
            $no = 1 + $request->start;
            $test = '';
            foreach($rows as $row){
                $d = [];
                $d[] = $no;
                $d[] = $row->periode->periode;
                $d[] = $row->kelas_jabatan;
                $d[] = number_format($row->jumlah_tukin,2,',','.');
                $buttons = '<a href="'. route('tukin.edit', [$row->id_tukin]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>
                            <a href="'. route('tukin.destroy', [$row->id_tukin]) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"></i> </a>';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }
    
            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Tukin'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_tukin,
                    'text' => $row->jumlah_tukin
                ];
            }
        }

        return response()->json($result);
    }
    public function create(){
        return view('tukin.form');
    }

    public function edit($id){
        $res = API::get('tukin/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('tukin.form')->with([
                'tukin' => $data->data
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function store(Request $request){
        $input = $request->all();
        
        $custom_msg = [
            'kelas_jabatan.required' => 'Kelas jabatan harus diisi.',
            'jumlah_tukin.required' => 'Jumlah tukin harus diisi',
            'jumlah_tukin.min' => 'Jumlah tukin harus lebih dari 0 diisi',
        ];
        $input['jumlah_tukin'] = (int) str_replace(['Rp','.',',00'],'',$input['jumlah_tukin']);
        $validation = \Validator::make($input, [
            "kelas_jabatan" => "required",
            "jumlah_tukin" => "required|min:0",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        $input['id_periode'] = $this->getActivePeriode()->id_periode;

        $res = API::post('tukin/create', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('tukin.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function update(Request $request,$id){
        $input = $request->all();
        
        $input['jumlah_tukin'] = (int) str_replace(['Rp','.',',00'],'',$input['jumlah_tukin']);
        $custom_msg = [
            'kelas_jabatan.required' => 'Kelas jabatan harus diisi.',
            'jumlah_tukin.required' => 'Jumlah tukin harus diisi',
            'jumlah_tukin.number' => 'Jumlah tukin harus berupa angka.',
            'jumlah_tukin.min' => 'Jumlah tukin harus lebih dari 0 diisi',
        ];

        $validation = \Validator::make($input, [
            "kelas_jabatan" => "required",
            "jumlah_tukin" => "required|min:0",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        
        $input['id_periode'] = $this->getActivePeriode()->id_periode;
        
        $res = API::post('tukin/'.$id, $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('tukin.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function destroy($id){
        $res = API::delete('tukin/'.$id);
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

    public function getActivePeriode(){
        $res = API::get('periode-active');
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return $data->data;
        }
        return [];
    }
    
    
}
