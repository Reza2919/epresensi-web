<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class KoordinatorController extends Controller
{
    public function index(){
        return view('koordinator.index');
    }
    public function listKoordinator($satker_id){
        $data['id_satker'] = $satker_id;
        $params = [
            'page' =>  1,
            'limit' => 1, 
            'satkerid' => $satker_id
        ];
        
        $res = SIAP::get('ws_naker_data/get_satker?page='.$params['page'].'&limit='.$params['limit'].'&satkerid='.$params['satkerid']);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $data['satker'] = $content->data[0];
        return view('koordinator.list',$data);
    }
    public function create(){
        return view('koordinator.form');
    }
    public function get(Request $request, $mode = ''){
        if(empty($mode)) $mode = 'datatable';
        $user = session('userdata');

        $satkerid = @$request->id_satker ? $request->id_satker : $user->satkerid;
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
        if($user->role == 'admin'){
            $res = API::get('koordinator/'.$satkerid,$body);
        }else{
            $res = API::get('koordinator',$body);
        }
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
                $d[] = $row->nama_pegawai;
                $d[] = $row->bidang->nama_bidang;
                $buttons = '<a href="'. route('bidang.show', [$satkerid,$row->id_satker_bidang]) .'" class="btn btn-icon btn-info" title="Data Pegawai"><i data-feather="users"></i> </a>
                <a href="'. route('bidang.destroy', [$row->id_satker_bidang]) .'" class="btn btn-icon btn-danger btn-delete" title="Hapus"><i data-feather="trash"></i> </a>';
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
                    'id' => $row->id_pegawai,
                    'text' => $row->nama_pegawai
                ];
            }
        }

        return response()->json($result);
    }
    public function getBySatkerId($id){
        $res = API::get('koordinator/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return $data->data;
        }
        return [];
    }
    public function store(Request $request){
        
        $user = session('userdata');
        $input = $request->all();
        
        $custom_msg = [
            'koordinator.required' => 'Koordinator harus diisi!.',
            'pegawai.required' => 'Pegawai harus diisi.',
            'nama_bidang.required' => 'Nama bidang harus diisi',
        ];

        $validation = \Validator::make($input, [
            "koordinator" => "required",
            "pegawai" => "required",
            "nama_bidang" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        $satkerid = $user->satkerid;
        $koordinator = explode('|',$request->koordinator);
        $pegawais = $request->pegawai;
        $pegawai = [];
        foreach ($pegawais as $key => $p) {
            $arr = explode('|',$p);
            $pegawai[] = [
                'id_pegawai' => $arr[0],
                'nama_pegawai' => $arr[1],
            ];
        }
        $res = API::post('koordinator/create-all', [
            'id_satker' => $satkerid,
            'nama_bidang' => $request->nama_bidang,
            'koordinator' => [
                'id_pegawai' => $koordinator[0],
                'nama_pegawai' => $koordinator[1],
            ],
            'pegawais' => $pegawai
        ]);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('koordinator.index')->with('success', $data->message);
        }
    }
    public function storePegawai(Request $request){
        
        $input = $request->all();
        
        $custom_msg = [
            'id_satker_bidang.required' => 'Bidang harus diisi!.',
            'pegawai.required' => 'Pegawai harus diisi.'
        ];

        $validation = \Validator::make($input, [
            "id_satker_bidang" => "required",
            "pegawai" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        $pegawais = $request->pegawai;
        $pegawai = [];
        foreach ($pegawais as $key => $p) {
            $arr = explode('|',$p);
            $pegawai[] = [
                'id_pegawai' => $arr[0],
                'nama_pegawai' => $arr[1],
            ];
        }
        $res = API::post('koordinator-pegawai/createBatch', [
            'id_satker_bidang' => $request->id_satker_bidang,
            'pegawais' => $pegawai
        ]);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return response()->json([
                'success'=>true,
                'message' => $data->message
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message' => "Whoops, terjadi kesalahan!"
            ]);
        }
    }
    public function showBidang($satkerid,$bidangid){
        $res = API::get('bidang/'.$bidangid);
        $body = $res->getBody()->getContents();
        $body = json_decode($body);
        if($body->code == 200){
            $data['bidang'] = $body->data;
        }
        $data['id_satker_bidang'] = $bidangid;
        $data['satkerid'] = $satkerid;
        return view('koordinator.bidang_detail',$data);
    }

    public function getSatker(){
        $res = SIAP::get('ws_naker_data/get_satker');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $rows = $content->data;
        
        if(!empty($rows)) return $rows;
        else return [];
        
    }

    public function generate($satkerid){
        $params['id_satker'] = $satkerid;
        $res = API::post('satker/generate-koordinator',$params);
        $body = $res->getBody()->getContents();
        $body = json_decode($body);
        if($res->getStatusCode() == 200){
            return redirect('koordinator/'.$satkerid.'/detail')->with('success',$body->message);
        }else{
            return redirect('koordinator/'.$satkerid.'/detail')->with('error',$body->message);
        }
        
    }
    public function destroy($id){
        $res = API::delete('bidang/'.$id);
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
