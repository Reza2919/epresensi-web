<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\Help;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(){
        $data['satkers'] = $this->getSatker();
        return view('pegawai.index',$data);
    }

    public function get(Request $request, $mode = ''){
        if(empty($mode)) $mode = 'datatable';
        $satkerid = @$request->satkerid;
        $search_by = @$request->search_by;
        $statuspegawai = @$request->statuspegawai;
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if ($this->hasSpecialCharacters(@$request->search) == 1) {
            return response()->json(['error' => 'Bad Request!'], 400);
        }

        $body = [
            'page' => $mode == 'datatable' ? ($start/$length) + 1 : 1,
            'limit' => $length,
        ];

        if($search_by == 'nip') $body['nip'] = $search;
        else if($search_by == 'nik') $body['nik'] = $search;
        else if($search_by == 'nama') $body['nama'] = $search;
        if($mode == 'select2') $body['nama'] = !empty(@$request->q) ? @$request->q : @$request->search;

        if($satkerid) $body['satker'] = $satkerid;
        if($statuspegawai) $body['statuspegawai'] = $statuspegawai;
        else $body['statuspegawai'] = '1,2';

        $res = SIAP::post('ws_naker_data/data_pns',$body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;
        $totalData = $content->total_data;
        $rows = !empty($sources) ? $sources : [];

        if($mode == 'datatable'){
            $data = [];
            $no = 1 + $request->start;
            foreach($rows as $row){
                $row->pegawaiid = Help::encrypt_encode($row->pegawaiid);
                $d = [];
                $d[] = $no;
                $d[] = '<div class="avatar avatar-lg"><img src="'.($row->foto ? $row->foto : asset('assets/app-assets/images/profile/default-user.jpg')).'" alt="avatar"></div> '.$row->nama;
                $d[] = $row->nip ?? '-';
                $d[] = $row->namajabatan;
                $d[] = $row->satker;
                $d[] = $row->statuspegawai;
                $buttons = '<a href="'.route("pegawai.show",[$row->pegawaiid]).'" class="btn btn-icon btn-info btn-detail" title="Data Pegawai"><i data-feather="user"></i> </a><button data-id_pegawai="'.$row->pegawaiid.'" data-nama="'.$row->nama.'"  data-id_satker="'.$row->satkerid.'" class="btn btn-icon btn-warning mt-1 btn-assign" title="Assign"><i data-feather="user-plus"></i> </button>';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
            $result['recordsTotal'] = $totalData;
            $result['recordsFiltered'] = $totalData;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Pegawai'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => @$request->valueWithText == 1 ? $row->pegawaiid.'|'.$row->nama : $row->pegawaiid,
                    'text' => $row->nama.' - '.$row->namajabatan
                ];
            }
        }

        return response()->json($result);
    }
    public function getPegawaiBidang(Request $request, $mode = ''){
        if(empty($mode)) $mode = 'datatable';
        $id_satker_bidang = @$request->id_satker_bidang;
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search,
            'id_satker_bidang'=> $id_satker_bidang
        ];

        $res = API::get('koordinator-pegawai',$body);
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
                $buttons = '';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Pegawai'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_pegawai,
                    'text' => $row->nama_pegawai
                ];
            }
        }

        return response()->json($result);
    }
    public function getSatker(){
        $res = SIAP::get('ws_naker_data/get_satker');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $rows = $content->data;

        if(!empty($rows)) return $rows;
        else return [];

    }
    public function show($id){
        if ($this->hasSpecialCharacters($id)) return view('errors.404');
        $id = Help::decrypt_decode($id);
        $data = $this->getDataPegawai($id);

        $id = Help::encrypt_encode($id);

        return view('pegawai.detail',['pegawai'=> $data,'id_pegawai'=> $id]);
    }
    public function jurnal($id){
        $res = API::get('presensi/'.$id.'/detail');
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            $dayName = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
            $hari = $dayName[(\Carbon\Carbon::parse($data->data->tanggal)->format('l'))];
            return view('pegawai.jurnal')->with([
                'presensi' => $data->data,
                'hari'=> $hari,
                'pegawai' => $this->getDataPegawai($data->data->id_pegawai),
            ]);
        }

    }
    public function deleteKoordinatorPegawai($id){
        $res = API::delete('koordinator-pegawai/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        return response()->json([
            'code' => $data->code,
            'message' => $data->message,
            'error' => $data->code == 200 ? false : true,
            'error_api' => $data->code == 200 ? null : $data->message,
            'errors_api' => $data->code == 200 ? [] : @$data->errors
        ]);
    }

    public function getDataPegawai($id){
        $body['pegawaiid'] = $id;
        $res = SIAP::post('ws_naker_data/data_pns',$body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $data = @$content->data;
        return $data[0];
    }


    public function hasSpecialCharacters($s) {
        $pattern = '/[<>\'\"\/&]/';
        return preg_match($pattern, $s);
    }

}
