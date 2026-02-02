<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class GolonganPajakController extends Controller
{
    public function index(){
        return view('golongan_pajak.index');
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
        
        $res = API::get('golongan-pajak',$body);
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
                $d[] = $row->golongan;
                $d[] = $row->pajak_persen;
                $buttons = '<a href="'. route('golongan-pajak.edit', [$row->id_golongan_pajak]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>
                            <a href="'. route('golongan-pajak.destroy', [$row->id_golongan_pajak]) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"></i> </a>';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }
    
            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua GolonganPajak'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_golongan_pajak,
                    'text' => $row->golongan
                ];
            }
        }

        return response()->json($result);
    }
    public function create(){
        $data['golongan'] = $this->getGolongan();
        return view('golongan_pajak.form',$data);
    }

    public function edit($id){
        $res = API::get('golongan-pajak/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('golongan_pajak.form')->with([
                'golongan_pajak' => $data->data,
                'golongan' => $this->getGolongan()
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function store(Request $request){
        $input = $request->all();
        
        $custom_msg = [
            'golongan.required' => 'Harap pilih golonngan!',
            'pajak_persen.required' => 'Jumlah pajak persen harus diisi',
        ];
        $validation = \Validator::make($input, [
            "golongan" => "required",
            "pajak_persen" => "required|min:0",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());

        $res = API::post('golongan-pajak/create', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('golongan-pajak.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function update(Request $request,$id){
        $input = $request->all();
        
        $custom_msg = [
            'golongan.required' => 'Harap pilih golonngan!',
            'pajak_persen.required' => 'Jumlah pajak persen harus diisi',
        ];
        $validation = \Validator::make($input, [
            "golongan" => "required",
            "pajak_persen" => "required|min:0",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());

        $res = API::post('golongan-pajak/'.$id, $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('golongan-pajak.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function destroy($id){
        $res = API::delete('golongan-pajak/'.$id);
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
    
    private function getGolongan(){
        $res = SIAP::POST('ws_naker_data/data_master_golongan',[]);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $rows = $content->data;
        
        if(!empty($rows)) return $rows;
        else return [];
        
    }
    
}
