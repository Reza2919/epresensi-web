<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SistemKerjaController extends Controller
{
    public function index(){
        return view('sistem-kerja.index');
    }
    public function get(Request $request, $mode = '')
{
    if(empty($mode)){
        $mode = 'datatable';
    }

    $query = DB::table('sistem_kerja');

    if($mode == 'datatable'){

        if(!empty($request->search['value'])){

            $keyword = $request->search['value'];

            $query->where(function($q) use ($keyword){
                $q->where('nama_sistem_kerja','ILIKE',"%{$keyword}%");
            });

        }

        $rows = $query
            ->orderBy('nama_sistem_kerja')
            ->get();

        $data = [];
        $no = 1;

        foreach($rows as $row){

            $status = $row->is_active
                ? "<span class='badge badge-success'>Aktif</span>"
                : "<span class='badge badge-danger'>Tidak Aktif</span>";

            $buttons = '
                <a href="'.route('sistem-kerja.edit',$row->id_sistem_kerja).'"
                    class="btn btn-sm btn-warning">
                    Edit
                </a>

                <a href="'.route('sistem-kerja.show',$row->id_sistem_kerja).'"
                    class="btn btn-sm btn-info">
                    Detail
                </a>

                <a href="'.url('sistem-kerja/'.$row->id_sistem_kerja).'"
                    class="btn btn-sm btn-danger btn-delete">
                    Hapus
                </a>
            ';

            $data[] = [
                $no++,
                $row->nama_sistem_kerja,
                $row->toleransi,
                $row->toleransi_pulang,
                $row->potongan_telat,
                $row->potongan_pulang,
                $row->potongan_tukin.'%',
                $status,
                $buttons
            ];
        }

        return response()->json([
            'draw' => intval($request->draw ?? 1),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ]);
    }

    $rows = $query
        ->orderBy('nama_sistem_kerja')
        ->get();

    $result = [
        'data' => []
    ];

    foreach($rows as $row){

        $result['data'][] = [
            'id' => $row->id_sistem_kerja,
            'text' => $row->nama_sistem_kerja
        ];

    }

    return response()->json($result);
}
    
   public function create()
{
    return view('sistem-kerja.form')->with([
        'sistem_kerja' => null,
        'satkers' => []
    ]);
}

    public function getById($id)
{
    return response()->json(
        DB::table('sistem_kerja')
            ->where('id_sistem_kerja',$id)
            ->first()
    );
}
   public function edit($id)
{
    $sistem_kerja = DB::table('sistem_kerja')
        ->where('id_sistem_kerja',$id)
        ->first();

    return view('sistem-kerja.form')->with([
        'sistem_kerja' => $sistem_kerja,
        'satkers' => []
    ]);
}

 public function store(Request $request)
{
    DB::table('sistem_kerja')->insert([

        'id_sistem_kerja' => Str::uuid()->toString(),

        'nama_sistem_kerja' => $request->nama_sistem_kerja,
        'jam_masuk' => $request->jam_masuk,
        'jam_keluar' => $request->jam_keluar,

        'toleransi' => $request->toleransi,
        'toleransi_pulang' => $request->toleransi_pulang,

        'potongan_telat' => $request->potongan_telat,
        'potongan_pulang' => $request->potongan_pulang,
        'potongan_tukin' => $request->potongan_tukin,

        'is_active' => $request->has('is_active') ? 1 : 0,
        'is_in_area' => $request->is_in_area,
        'is_lembur' => $request->has('is_lembur') ? 1 : 0,
        'is_everyday' => $request->has('is_everyday') ? 1 : 0,
        'is_cuti' => $request->has('is_cuti') ? 1 : 0,
        'is_removable' => 1,

        'createdAt' => now(),
        'updatedAt' => now()

    ]);

    return redirect()
        ->route('sistem-kerja.index')
        ->with('success','Data berhasil ditambahkan');
}

  public function update(Request $request,$id)
{
    DB::table('sistem_kerja')
        ->where('id_sistem_kerja',$id)
        ->update([

            'nama_sistem_kerja' => $request->nama_sistem_kerja,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,

            'toleransi' => $request->toleransi,
            'toleransi_pulang' => $request->toleransi_pulang,

            'potongan_telat' => $request->potongan_telat,
            'potongan_pulang' => $request->potongan_pulang,
            'potongan_tukin' => $request->potongan_tukin,

            'is_active' => $request->has('is_active') ? 1 : 0,
            'is_in_area' => $request->is_in_area,
            'is_lembur' => $request->has('is_lembur') ? 1 : 0,
            'is_everyday' => $request->has('is_everyday') ? 1 : 0,
            'is_cuti' => $request->has('is_cuti') ? 1 : 0,
            'is_removable' => 1,
            'updatedAt' => now(),

        
            

        ]);

    return redirect()
        ->route('sistem-kerja.index')
        ->with('success','Data sistem kerja berhasil diubah');
}

   public function updateDetail(Request $request,$id)
{
    return redirect()
        ->route('sistem-kerja.index')
        ->with('success','Ketentuan jam kerja berhasil disimpan');
}

   public function destroy($id)
{
    DB::table('sistem_kerja')
        ->where('id_sistem_kerja', $id)
        ->delete();

    return response()->json([
        'error' => false,
        'message' => 'Data berhasil dihapus'
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

  public function show($id)
{
    $sistem_kerja = (object)[
        'id_sistem_kerja' => $id,
        'nama_sistem_kerja' => 'WFO',
        'toleransi' => 15,
        'toleransi_pulang' => 0,
        'potongan_telat' => 0,
        'potongan_pulang' => 0,
        'potongan_tukin' => 0,
        'is_active' => 1,
        'is_in_area' => 1,
        'is_removable' => 1,
        'is_lembur' => 0,
        'is_everyday' => 0,
        'is_cuti' => 0,
    ];

    $details = [
        (object)[
            'hari' => 'Senin',
            'jam_masuk' => '08:00',
            'jam_keluar' => '17:00',
            'total_jam_kerja' => '8 Jam'
        ],
        (object)[
            'hari' => 'Selasa',
            'jam_masuk' => '08:00',
            'jam_keluar' => '17:00',
            'total_jam_kerja' => '8 Jam'
        ],
    ];

    return view('sistem-kerja.show')->with([
    'sistem_kerja' => $sistem_kerja,
    'details' => $details
]);
}
}
