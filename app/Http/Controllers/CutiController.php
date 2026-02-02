<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index(){
        return view('cuti.index');
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
        
        $res = API::get('cuti', $body);
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
                $d[] = $row->nama_cuti;
                $d[] = $row->keterangan;
                $d[] = $row->nilai_persen;
                $buttons = '<a href="'. route('cuti.edit', [$row->id_cuti]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>
                            <a href="'. route('cuti.destroy', [$row->id_cuti]) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"></i> </a>';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }
    
            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Cuti'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_cuti,
                    'text' => $row->nama_cuti
                ];
            }
        }

        return response()->json($result);
    }
    
    public function create(){
        return view('cuti.form');
    }

    public function edit($id){
        $res = API::get('cuti/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('cuti.form')->with([
                'cuti' => $data->data
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function store(Request $request){
        $input = $request->all();
        
        $custom_msg = [
            'nama_cuti.required' => 'Nama sistem kerja harus diisi!.',
            'keterangan.required' => 'Keterangan harus diisi.',
            'nilai_persen.required' => 'Nilai persen harus diisi',
            'nilai_persen.number' => 'Nilai persen harus berupa angka',
        ];

        $validation = \Validator::make($input, [
            "nama_cuti" => "required",
            "keterangan" => "required",
            "nilai_persen" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        
        $res = API::post('cuti/create', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('cuti.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function update(Request $request,$id){
        $input = $request->all();
        
        $custom_msg = [
            'nama_cuti.required' => 'Nama sistem kerja harus diisi!.',
            'keterangan.required' => 'Keterangan harus diisi.',
            'nilai_persen.required' => 'Nilai persen harus diisi',
            'nilai_persen.number' => 'Nilai persen harus berupa angka',
        ];

        $validation = \Validator::make($input, [
            "nama_cuti" => "required",
            "keterangan" => "required",
            "nilai_persen" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        
        $res = API::post('cuti/'.$id, $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('cuti.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function destroy($id){
        $res = API::delete('cuti/'.$id);
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
