<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\Help;
use Illuminate\Http\Request;

class PotonganTukinController extends Controller
{
    public function get(Request $request, $mode = '',$id){
        if(empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;
        $bulan = @$request->bulan;
        $tahun = @$request->tahun;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search,
        ];
        if($bulan && $tahun){
            $body['bulan'] = $bulan;
            $body['tahun'] = $tahun;
        }
        $id = Help::decrypt_decode($id);
        $res = API::post('potongan-tukin/'. $id,$body);
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
                $d[] = $row->tanggal;
                $d[] = number_format($row->jumlah_potongan,2,',','.');
                $d[] = $row->keterangan;
                $buttons = '';
                if(session('userdata')->role == 'admin'){
                    $buttons = '<button type="button" class="btn btn-info btn-sm btn-edit" data-id="'.$row->id_potongan_tukin.'" data-tanggal="'.$row->tanggal.'" data-jumlah_potongan="'.$row->jumlah_potongan.'" data-keterangan="'.$row->keterangan.'"><i data-feather="edit"></i></button>
                    <a href="'.url('potongan-tukin/'.$row->id_potongan_tukin).'" type="button" class="btn btn-warning btn-sm btn-delete"><i data-feather="trash"></i></a>';
                }
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Presensi'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_potongan_tukin,
                    'text' => $row->keterangan
                ];
            }
        }

        return response()->json($result);
    }

    public function getRekap(){
        $body['bulan'] = @request()->bulan;
        $body['tahun'] = @request()->tahun;
        $body['id_pegawai'] = @request()->id_pegawai;
        $body['id_pegawai'] = Help::decrypt_decode($body['id_pegawai']);
        $res = API::post('rekap-tukin/',$body);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return response()->json($data->data);
        }
        return response()->json([]);
    }

    public function store(Request $request){
        $input = $request->all();

        $custom_msg = [
            'jumlah_potongan.required' => 'Jumlah potongan harus diisi!.',
            'jumlah_potongan.number' => 'Jumlah potongan harus berupa angka!.',
            'keterangan.required' => 'Keterangan harus diisi!.',
        ];

        $validation = \Validator::make($input, [
            "jumlah_potongan" => "required",
            "keterangan" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        $res = API::post($request->id_potongan_tukin == '' ? 'potongan-tukin' : 'potongan-tukin/'.$request->id_potongan_tukin.'/update', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return response()->json([
                'success'=>true,
                'data'=> $data->data,
                'message' => $data->message
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message' => $data->message
            ]);
        }
    }

    public function destroy($id){
        $res = API::delete('potongan-tukin/'.$id);
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
