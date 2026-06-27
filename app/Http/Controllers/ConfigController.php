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

       $rows = [
    (object)[
        'id_config' => 1,
        'name' => 'jam_masuk',
        'value' => '08:00',
        'desc' => 'Jam masuk kerja'
    ],
    (object)[
        'id_config' => 2,
        'name' => 'jam_pulang',
        'value' => '17:00',
        'desc' => 'Jam pulang kerja'
    ],
    (object)[
        'id_config' => 3,
        'name' => 'toleransi_telat',
        'value' => '15',
        'desc' => 'Toleransi keterlambatan'
    ]
];

$result['draw'] = intval($request->draw);
$result['recordsTotal'] = count($rows);
$result['recordsFiltered'] = count($rows);

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
           $result['recordsTotal'] = count($data);
           $result['recordsFiltered'] = count($data);
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

    public function edit($id)
{
    $dummy = (object)[
        'id_config' => $id,
        'name' => 'jam_masuk',
        'value' => '08:00',
        'desc' => 'Jam masuk kerja'
    ];

    return view('config.form')->with([
        'config' => $dummy
    ]);
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

        if(@$request->is_redirect == "false"){
    return response()->json([
        'code' => 200,
        'message' => 'Config berhasil diupdate'
    ]);
}

return redirect()
    ->route('config.index')
    ->with('success', 'Config berhasil diupdate');
    }

    public function show($name)
{
    return view('config.form-info')->with([
        'config' => (object)[
            'id_config' => 1,
            'name' => $name,
            'value' => 'Dummy Content',
            'desc' => 'Demo Mode'
        ]
    ]);
}

public function kebijakanPrivasi()
{
    return view('config.form-info')->with([
        'config' => (object)[
            'id_config' => 99,
            'name' => 'kebijakan-privasi',
            'value' => 'Ini adalah kebijakan privasi aplikasi e-Presensi versi demo.',
            'desc' => 'Demo Mode'
        ]
    ]);
}


        
    

    public function destroy($id)
{
    return response()->json([
        'code' => 200,
        'message' => 'Config berhasil dihapus',
        'error' => false
    ]);
}
}
