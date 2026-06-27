<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PeriodeTukinController extends Controller
{
    public function index(){
        return view('periode.index');
    }

   public function get(Request $request, $mode = '')
{
    if(empty($mode)){
        $mode = 'datatable';
    }

    $query = DB::table('periode_tukin');

    if(!empty($request->search['value'])){

        $query->where(
            'periode',
            'ILIKE',
            '%'.$request->search['value'].'%'
        );

    }

    $rows = $query
        ->orderBy('periode')
        ->get();

    if($mode == 'datatable'){

        $data = [];
        $no = 1;

        foreach($rows as $row){

            $status =
                "<span class='badge badge-".
                ($row->is_active ? 'success' : 'warning').
                "'>".
                ($row->is_active ? 'Aktif' : 'Tidak Aktif').
                "</span>";

            $buttons='
                <a href="'.route('periode.edit',$row->id_periode).'"
                    class="btn btn-info btn-sm">
                    Edit
                </a>

                <a href="'.url('periode/'.$row->id_periode).'"
                    class="btn btn-success btn-sm">
                    Detail
                </a>

                <a href="'.url('periode/'.$row->id_periode).'"
                    class="btn btn-danger btn-sm btn-delete">
                    Hapus
                </a>
            ';

            $data[]=[
                $no++,
                $row->periode,
                $status,
                $buttons
            ];

        }

        return response()->json([
            'draw'=>intval($request->draw??1),
            'recordsTotal'=>count($data),
            'recordsFiltered'=>count($data),
            'data'=>$data
        ]);
    }

    $result=['data'=>[]];

    foreach($rows as $row){

        $result['data'][]=[
            'id'=>$row->id_periode,
            'text'=>$row->periode
        ];

    }

    return response()->json($result);
}

    public function create(){
        return view('periode.form');
    }

    public function edit($id)
{
    $periode = DB::table('periode_tukin')
        ->where('id_periode',$id)
        ->first();

    return view('periode.form')->with([
        'periode'=>$periode
    ]);
}

public function show($id)
{
    $periode = DB::table('periode_tukin')
        ->where('id_periode', $id)
        ->first();

    return view('periode.show', compact('periode'));
}

   public function store(Request $request)
{
    DB::table('periode_tukin')->insert([

        'id_periode' => Str::uuid()->toString(),

        'periode' => $request->periode,

        'is_active' => 0,

        'createdAt' => now(),
        'updatedAt' => now()

    ]);

    return redirect()
        ->route('periode.index')
        ->with('success','Periode Tukin berhasil ditambahkan');
}

   public function update(Request $request,$id)
{
    DB::table('periode_tukin')
        ->where('id_periode',$id)
        ->update([

            'periode' => $request->periode,

            'updatedAt' => now()

        ]);

    return redirect()
        ->route('periode.index')
        ->with('success','Periode Tukin berhasil diperbarui');
}

    public function destroy($id)
{
    DB::table('periode_tukin')
        ->where('id_periode',$id)
        ->delete();

    return response()->json([
        'error'=>false,
        'message'=>'Data berhasil dihapus'
    ]);
}

   public function setActive($id)
{
    DB::table('periode_tukin')
        ->update([
            'is_active'=>0,
            'updatedAt'=>now()
        ]);

    DB::table('periode_tukin')
        ->where('id_periode',$id)
        ->update([
            'is_active'=>1,
            'updatedAt'=>now()
        ]);

    return response()->json([
        'error'=>false,
        'message'=>'Periode aktif berhasil diubah'
    ]);
}
}
