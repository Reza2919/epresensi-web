<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TukinController extends Controller
{
    public function index(){
        return view('tukin.index');
    }
   public function get(Request $request, $mode = '')
{
    if(empty($mode)) $mode = 'datatable';

    $rows = DB::table('tukin')
        ->leftJoin('periode_tukin','tukin.id_periode','=','periode_tukin.id_periode')
        ->select(
            'tukin.*',
            'periode_tukin.periode'
        )
        ->orderBy('kelas_jabatan','desc')
        ->get();

    $result = [
        'draw' => intval($request->draw),
        'recordsTotal' => $rows->count(),
        'recordsFiltered' => $rows->count(),
        'data' => []
    ];

    if($mode == 'datatable'){

        $no = 1;

        foreach($rows as $row){

            $result['data'][] = [

                $no++,

                $row->periode,

                $row->kelas_jabatan,

                number_format($row->jumlah_tukin,2,',','.'),

                '<a href="'.route('tukin.edit',$row->id_tukin).'" class="btn btn-info btn-sm">
    Edit
</a>'

            ];
        }

    }else{

        foreach($rows as $row){

            $result['data'][] = [

                'id'=>$row->id_tukin,
                'text'=>$row->kelas_jabatan

            ];

        }

    }

    return response()->json($result);
}
   public function create()
{
    $periodes = DB::table('periode_tukin')
        ->orderBy('periode')
        ->get();

    return view('tukin.form', compact('periodes'));
}

   public function edit($id)
{
    $tukin = DB::table('tukin')
        ->where('id_tukin', $id)
        ->first();

    $periodes = DB::table('periode_tukin')
        ->orderBy('periode')
        ->get();

    return view('tukin.form', compact('tukin','periodes'));
}

    public function store(Request $request)
{
    DB::table('tukin')->insert([

        'id_tukin' => Str::uuid()->toString(),

        'id_periode' => $request->id_periode,

        'kelas_jabatan' => $request->kelas_jabatan,

        'jumlah_tukin' => str_replace(
            ['Rp','.'],
            '',
            str_replace(',','.', $request->jumlah_tukin)
        ),

        'createdAt' => now(),
        'updatedAt' => now()

    ]);

    return redirect()
        ->route('tukin.index')
        ->with('success','Data berhasil ditambahkan');
}

    public function update(Request $request,$id)
{
    DB::table('tukin')
        ->where('id_tukin',$id)
        ->update([

            'id_periode'=>$request->id_periode,

            'kelas_jabatan'=>$request->kelas_jabatan,

            'jumlah_tukin'=>str_replace(
                ['Rp','.'],
                '',
                str_replace(',','.', $request->jumlah_tukin)
            ),

            'updatedAt'=>now()

        ]);

    return redirect()
        ->route('tukin.index')
        ->with('success','Data berhasil diubah');
}
   public function destroy($id)
{
    DB::table('tukin')
        ->where('id_tukin',$id)
        ->delete();

    return response()->json([
        'error'=>false,
        'message'=>'Data berhasil dihapus'
    ]);
}

    public function getActivePeriode()
{
    return DB::table('periode_tukin')
        ->where('is_active',1)
        ->first();
}
    
    
}
