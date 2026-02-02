<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class SistemKerjaController extends Controller
{
    public function index(){
        return view('sistem-kerja.index');
    }
    public function get(Request $request, $mode = ''){
        $user = session('userdata');
        if(empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        // $length = @$request->length;
        // $start = @$request->start;


        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => 0,
            'limit' => 2000,   
            'search' => $search,
        ];
        
        $res = API::get('sistem-kerja', $body);
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
                $d[] = $row->nama_sistem_kerja;
                $d[] = $row->toleransi;
                $d[] = $row->toleransi_pulang;
                $d[] = $row->potongan_telat;
                $d[] = $row->potongan_pulang;
                $d[] = $row->potongan_tukin."%";

                $status = "<i class='badge badge-".($row->is_active == 1 ? 'success' : 'warning')."'>".($row->is_active == 1 ? 'Aktif' : 'Tidak Aktif')."</i> ";
                if($row->is_in_area == 1) $status .= "<i class='badge badge-success'>Dalam Radius</i> ";
                else if($row->is_in_area == 0) $status .= "<i class='badge badge-warning'>Luar Radius</i> ";
                else $status .= "<i class='badge badge-info'>Luar & dalam Radius</i> ";

                if($row->is_lembur == 1 && $row->is_everyday == 0) $status .= "<i class='badge badge-danger'>Lembur</i> ";
                else if($row->is_everyday == 1 && $row->is_lembur == 1) $status .= "<i class='badge badge-danger'>Lembur & Hari Kerja</i> ";
                if(@$row->is_cuti == 1) $status .= "<i class='badge badge-primary'>Cuti</i> ";

                $d[] = $status;
                $buttons = '<a href="'. route('sistem-kerja.edit', [$row->id_sistem_kerja]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>';
                $buttons .= '
                <a href="'. route('sistem-kerja.show', [$row->id_sistem_kerja]) .'" class="btn btn-icon btn-outline-success" title="Detail"><i data-feather="clock"></i> </a>';
                if($row->is_removable == 1){
                    $buttons .= '
                    <a href="'. route('sistem-kerja.destroy', [$row->id_sistem_kerja]) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"> </i> </a>';
                }
                if($row->is_active == 1){
                    $buttons .= '
                    <a href="'. route('sistem-kerja.set-active', [$row->id_sistem_kerja]) .'" class="btn btn-icon btn-outline-warning btn-active" title="Nonaktifkan"><i data-feather="x"></i> </a>';
                }else{
                    $buttons .= '
                    <a href="'. route('sistem-kerja.set-active', [$row->id_sistem_kerja]) .'" class="btn btn-icon btn-outline-success btn-active" title="Aktifkan"><i data-feather="check"></i> </a>';
                }
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }
    
            $result['data'] = $data;
            $result['recordsTotal'] = count($rows);
            $result['recordsFiltered'] = count($rows);
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Sistem Kerja'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_sistem_kerja,
                    'text' => $row->nama_sistem_kerja
                ];
            }
        }

        return response()->json($result);
    }
    
    public function create(){
        $data['satkers'] = $this->getSatker();
        return view('sistem-kerja.form',$data);
    }

    public function getById($id){
        $res = API::get('sistem-kerja/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return response()->json($data->data);
        }
    }
    public function edit($id){
        $res = API::get('sistem-kerja/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('sistem-kerja.form')->with([
                'sistem_kerja' => $data->data,
                'satkers' => $this->getSatker()
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function store(Request $request){
        $input = $request->all();
        $input["jam_masuk"] = "00:00";
        $input["jam_keluar"] = "00:00";
        if(!$request->is_lembur){
            $input['is_lembur'] = 0;
        }
        if(!$request->is_cuti){
            $input['is_cuti'] = 0;
        }
        if(!$request->is_everyday){
            $input['is_everyday'] = 0;
        }
        $custom_msg = [
            'nama_sistem_kerja.required' => 'Nama sistem kerja harus diisi!.',
            'jam_masuk.required' => 'Jam masuk harus diisi.',
            'jam_keluar.required' => 'Jam keluar harus diisi.',
            'toleransi.required' => 'Toleransi harus diisi.',
            'potongan_tukin.required' => 'Potongan tukin harus diisi.',
            'toleransi.number' => 'Toleransi harus berupa angka.',
            'is_in_area.required' => 'Area harus dipilih.'
        ];

        $validation = \Validator::make($input, [
            "nama_sistem_kerja" => "required",
            "jam_masuk" => "required",
            "jam_keluar" => "required",
            "toleransi" => "required",
            "potongan_tukin" => "required",
            "is_in_area" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        
        $res = API::post('sistem-kerja/create', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('sistem-kerja.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function update(Request $request,$id){
        $input = $request->all();
        $input["jam_masuk"] = "00:00";
        $input["jam_keluar"] = "00:00";
        
        if(!$request->is_lembur){
            $input['is_lembur'] = 0;
            $input['is_everyday'] = 0;
        }
        if(!$request->is_cuti){
            $input['is_cuti'] = 0;
        }
        if(!$request->is_everyday){
            $input['is_everyday'] = 0;
        }
        $custom_msg = [
            'nama_sistem_kerja.required' => 'Nama sistem kerja harus diisi!.',
            'jam_masuk.required' => 'Jam masuk harus diisi.',
            'jam_keluar.required' => 'Jam keluar harus diisi.',
            'toleransi.required' => 'Toleransi harus diisi.',
            'toleransi.number' => 'Toleransi harus berupa angka.',
            'potongan_tukin.required' => 'Potongan tukin harus diisi.',
            'is_in_area.required' => 'Area harus dipilih.'
        ];

        $validation = \Validator::make($input, [
            "nama_sistem_kerja" => "required",
            "jam_masuk" => "required",
            "jam_keluar" => "required",
            "toleransi" => "required",
            "potongan_tukin" => "required",
            "is_in_area" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        $res = API::post('sistem-kerja/'.$id, $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('sistem-kerja.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function updateDetail(Request $request,$id){
        $data = [];
        $days = ['Senin'=>'Monday','Selasa'=>'Tuesday','Rabu'=>'Wednesday','Kamis'=>'Thursday','Jumat'=>'Friday','Sabtu'=>'Saturday','Minggu'=>'Sunday'];
        foreach ($request->jam_masuk as $hari => $jam_masuk) {
            $data[] = [
                'hari' => $hari,
                'hari_eng'=> $days[$hari],
                'jam_masuk' => $jam_masuk,
                'jam_keluar' => $request->jam_keluar[$hari],
                'total_jam_kerja' => $request->total_jam_kerja[$hari],
            ];
        }
        $payload = [
            'data' => $data
        ];
        $res = API::post('sistem-kerja-detail/'.$id, $payload);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect('sistem-kerja')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function destroy($id){
        $res = API::delete('sistem-kerja/'.$id);
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
        $res = API::post('sistem-kerja/'.$id.'/set-active',[]);
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
    public function getSatker(){
        $res = SIAP::get('ws_naker_data/get_satker');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $rows = $content->data;
        
        if(!empty($rows)) return $rows;
        else return [];
        
    }

    public function show($id){
        $res = API::get('sistem-kerja/'.$id);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sistem_kerja = $content->data;
        $details = [];
        if(@$sistem_kerja->sistem_kerja_detail){
            foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']  as $key => $value) {
                foreach ($sistem_kerja->sistem_kerja_detail as $k => $detail) {
                    if($detail->hari == $value){
                        $details[$value] = $detail;
                    }
                }
            }
        }
        $data = [
            'sistem_kerja' => $sistem_kerja,
            'detail' => $details
        ];
        return view('sistem-kerja.show',$data);
    }
}
