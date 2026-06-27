<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index()
    {
        return view('cuti.index');
    }

   public function get(Request $request, $mode = '')
{
    if(empty($mode)) $mode = 'datatable';

    $rows = DB::table('cuti')
        ->orderBy('nama_cuti')
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

                $row->nama_cuti,

                $row->keterangan,

                $row->nilai_persen.'%',

                '
                <a href="'.route('cuti.edit',$row->id_cuti).'" class="btn btn-info btn-sm">
                    Edit
                </a>

                <a href="'.url('cuti/'.$row->id_cuti).'" class="btn btn-danger btn-sm btn-delete">
                    Hapus
                </a>
                '

            ];

        }

    }else{

        foreach($rows as $row){

            $result['data'][] = [
                'id'=>$row->id_cuti,
                'text'=>$row->nama_cuti
            ];

        }

    }

    return response()->json($result);
}

   public function create()
{
    return view('cuti.form');
}
    public function edit($id)
{
    $cuti = DB::table('cuti')
        ->where('id_cuti',$id)
        ->first();

    return view('cuti.form',compact('cuti'));
}

    public function store(Request $request)
{
    DB::table('cuti')->insert([

        'id_cuti' => Str::uuid()->toString(),

        'nama_cuti' => $request->nama_cuti,

        'keterangan' => $request->keterangan,

        'nilai_persen' => $request->nilai_persen,

        'createdAt' => now(),
        'updatedAt' => now()

    ]);

    return redirect()
        ->route('cuti.index')
        ->with('success','Data berhasil ditambahkan');
}

   public function update(Request $request,$id)
{
    DB::table('cuti')
        ->where('id_cuti',$id)
        ->update([

            'nama_cuti' => $request->nama_cuti,

            'keterangan' => $request->keterangan,

            'nilai_persen' => $request->nilai_persen,

            'updatedAt' => now()

        ]);

    return redirect()
        ->route('cuti.index')
        ->with('success','Data berhasil diubah');
}

    public function show($id)
    {
        $dummy = (object)[
            'id_cuti' => $id,
            'nama_cuti' => 'Cuti Tahunan',
            'keterangan' => 'Hak cuti tahunan pegawai',
            'nilai_persen' => 100
        ];

        return view('cuti.show')->with([
            'cuti' => $dummy
        ]);
    }

    public function destroy($id)
{
    DB::table('cuti')
        ->where('id_cuti',$id)
        ->delete();

    return response()->json([
        'error'=>false,
        'message'=>'Data berhasil dihapus'
    ]);
}
}