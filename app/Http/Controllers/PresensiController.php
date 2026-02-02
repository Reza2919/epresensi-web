<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\Help;
use App\Helpers\SIAP;
use Illuminate\Http\Request;
use App\Models\JobGeneratePresensi;

class PresensiController extends Controller
{
    public function get(Request $request, $mode = '',$id){
        if(empty($mode)) $mode = 'datatable';
        $user = session('userdata');
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;
        $bulan = @$request->bulan;
        $tahun = @$request->tahun;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search['value'],
        ];
        if($bulan && $tahun){
            $body['bulan'] = $bulan;
            $body['tahun'] = $tahun;
        }

        $id = Help::decrypt_decode($id);

        $res = API::get('presensi/'. $id.'?'.http_build_query($body));
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
                $d[] = $row->tanggal;
                $d[] = $row->jam_masuk;
                $d[] = $row->jam_keluar;
                $d[] = $row->sistem_kerja->nama_sistem_kerja;
                $buttons = '<a class="btn btn-info btn-sm" href="'.route('pegawai.jurnal',[$row->id_presensi]).'"><i data-feather="list"></i> </a> ';
                if($user->role == 'admin'){
                    $buttons .= '<a href="'. route('presensi.delete', [$row->id_presensi]) .'" class="btn btn-sm btn-danger btn-delete-presensi" title="Hapus"><i data-feather="trash"></i> </a>';
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
                    'id' => $row->id_presensi,
                    'text' => $row->nama_pegawai
                ];
            }
        }

        return response()->json($result);
    }

    public function getPresensiLog(Request $request){
        if(empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;
        $tanggal = @$request->tanggal;
        $id_satker = @$request->satkerid;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search['value'],
        ];
        if($tanggal){
            $body['tanggal'] = $tanggal;
        }
        if($id_satker){
            $body['id_satker'] = $id_satker;
        }
        $res = API::get('presensi-change?'.http_build_query($body));
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
                $d[] = \Carbon\Carbon::parse($row->createdAt)->setTimeZone('Asia/Jakarta')->format('d-m-Y H:i:s');
                $d[] = \Carbon\Carbon::parse($row->presensi->tanggal)->setTimeZone('Asia/Jakarta')->format('d-m-Y');
                $d[] = $row->presensi->nama_satker;
                $d[] = $row->user;
                $d[] = $row->presensi->nama_pegawai;
                $d[] = $row->presensi_change_from->nama_sistem_kerja.' -> '.$row->presensi_change_to->nama_sistem_kerja;
                $d[] = $row->potongan_awal;
                $d[] = $row->potongan_akhir;
                $buttons = '<a class="btn btn-info btn-sm" href="'.route('pegawai.jurnal',[$row->id_presensi]).'"><i data-feather="list"></i> Detail</a> ';
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
                    'id' => $row->id_presensi,
                    'text' => $row->presensi->nama_pegawai
                ];
            }
        }

        return response()->json($result);
    }
    public function edit($id){
        $res = API::get('presensi/'.$id.'/detail');
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            $dayName = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
            $hari = $dayName[\Carbon\Carbon::parse($data->data->tanggal)->dayOfWeek];
            return view('presensi.form')->with([
                'presensi' => $data->data,
                'hari' => $hari,
                'pegawai' => $this->getDataPegawai($data->data->id_pegawai)
            ]);
        }
    }
    public function presensiLog(){
        return view('presensi-change.index');
    }

    public function getDataPegawai($id){
        $body['pegawaiid'] = $id;
        $res = SIAP::post('ws_naker_data/data_pns',$body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $data = @$content->data;
        return $data[0];
    }

    public function save(Request $request,$id){
        $id_sistem_kerja = $request->id_sistem_kerja;
        $res = API::post('presensi/'.$id.'/update',['id_sistem_kerja' => $id_sistem_kerja]);
        if($res->getStatusCode() == 200 ){
            $body = $res->getBody()->getContents();
            $data = json_decode($body);
            if($request->id_cuti){
                $res = API::post('presensi-cuti/create',['id_presensi' => $id,'id_cuti'=>$request->id_cuti]);
                if($res->getStatusCode() == 200 ){
                    return redirect('jurnal/'.$id)->with('success', $data->message);
                }else{
                    return redirect('jurnal/'.$id)->with('error', "Terjadi kesalahan saat menambahkan data cuti");
                }
            }else{
                return redirect('jurnal/'.$id)->with('success', $data->message);
            }

        }else{
            $body = $res->getBody()->getContents();
            $data = json_decode($body);
            return redirect()->back()->with([
                'error' => $data->message,
            ]);
        }

    }

    public function setTidakPresensi(Request $request,$id){
        $res = API::post('presensi/'.$id.'/set-tidak-presensi',[]);
        if($res->getStatusCode() == 200 ){
            $body = $res->getBody()->getContents();
            $data = json_decode($body);
            return response()->json([
                'code' => $data->code,
                'message' => $data->message,
                'error' => $data->code == 200 ? false : true,
                'error_api' => $data->code == 200 ? null : $data->message,
                'errors_api' => $data->code == 200 ? [] : $data->errors
            ]);
        }else{
            return response()->json([
                'code' => $res->getStatusCode(),
                'message' => 'Page not found',
                'error' => 'Page not found'
            ]);
        }
    }

    public function generatePresensi(){
        return view('presensi.generate');
    }
    public function doGeneratePresensi(Request $request){
        $request->validate([
            'tanggal' => 'required|date_format:Y-m-d|before:today',
        ]);
        $res = API::post('presensi/generate/'.$request->tanggal,[]);
        if($res->getStatusCode() == 200 ){
            $body = $res->getBody()->getContents();
            $data = json_decode($body);
            return redirect('generate-presensi')->with('success', $data->message);
        }else{
            return redirect('generate-presensi')->with('error', 'Page not found');
        }
    }

    public function getListJob(Request $request) {
        $length = @$request->length;
        $start = @$request->start;
        $data = JobGeneratePresensi::limit($length)->offset($start)->orderBy('createdAt', 'desc')->get();

        $rows = !empty($data) ? $data : [];

        $data = [];
        $no = 1 + $request->start;
        foreach($rows as $row){
            $createdAt = \Carbon\Carbon::parse($row->createdAt);
            $d = [];
            $d[] = $no;
            $d[] = \Carbon\Carbon::parse($row->date)->format('d M Y');
            $d[] = $row->total_pegawai;
            if ($row->status == "Berhasil") {
                $d[] = '<span class="badge badge-light-success">'.$row->status.'</span>';
            } else if ($row->status == "Gagal") {
                $d[] = '<span class="badge badge-light-danger">'.$row->status.'</span>';
            } else {
                $d[] = '<span class="badge badge-light-warning">'.$row->status.'</span>';
            }
            $d[] = $row->message;
            $d[] = $createdAt->format('d M Y H:i');

            $data[] = $d;
            $no++;
        }
        $total = JobGeneratePresensi::all()->count();
        $result['data'] = $data;
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] = $total;

        return response()->json($result);
    }
    public function getTrendPresensi(){
        $date_start = request()->date_start;
        $date_end = request()->date_end;
        $satkerid = request()->satkerid;
        $res = API::get('presensi-trend?date_start='.$date_start.'&date_end='.$date_end.'&satkerid='.$satkerid);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($res->getStatusCode() == 200){
            return response()->json([
                'status' => 'success',
                'data' => $data->data,
                'message'=>'Success'
            ]);
        }else{
            return response()->json([
                'status' => 'failed',
                'message'=>'Error'
            ]);
        }
    }

    public function destroy($id){
        $res = API::delete('presensi/'.$id);
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
