<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class GolonganPajakController extends Controller
{
    public function index(){
        return view('golongan_pajak.index');
    }
   public function get(Request $request, $mode = '')
{
    if(empty($mode)) $mode = 'datatable';

    $rows = DB::table('golongan_pajak')
        ->orderBy('golongan')
        ->get();

    if($mode == 'datatable'){

        $data = [];
        $no = 1;

        foreach($rows as $row){

            $data[] = [

                $no++,

                $row->golongan,

                $row->pajak_persen.' %',

                '<a href="'.route('golongan-pajak.edit',$row->id_golongan_pajak).'" class="btn btn-info btn-sm">
                    Edit
                </a>

                <a href="'.url('golongan-pajak/'.$row->id_golongan_pajak).'" class="btn btn-danger btn-sm btn-delete">
                    Hapus
                </a>'

            ];
        }

        return response()->json([
            'draw'=>intval($request->draw),
            'recordsTotal'=>$rows->count(),
            'recordsFiltered'=>$rows->count(),
            'data'=>$data
        ]);
    }

    $result = [];

    foreach($rows as $row){

        $result['data'][]=[

            'id'=>$row->id_golongan_pajak,
            'text'=>$row->golongan

        ];
    }

    return response()->json($result);
}
   public function create()
{
    return view('golongan_pajak.form');
}

    public function edit($id)
{
    $golongan_pajak = DB::table('golongan_pajak')
        ->where('id_golongan_pajak',$id)
        ->first();

    return view('golongan_pajak.form',compact('golongan_pajak'));
}

    public function store(Request $request)
{
    DB::table('golongan_pajak')->insert([

        'id_golongan_pajak'=>Str::uuid()->toString(),

        'golongan'=>$request->golongan,

        'pajak_persen'=>$request->pajak_persen,

        'createdAt'=>now(),

        'updatedAt'=>now()

    ]);

    return redirect()
        ->route('golongan-pajak.index')
        ->with('success','Data berhasil ditambahkan');
}
public function show($id)
{
    $dummy = (object)[
        'id_golongan_pajak' => $id,
        'golongan' => 'Golongan I',
        'pajak_persen' => 5
    ];

    return view('golongan_pajak.form')->with([
        'golongan_pajak' => $dummy,
        'golongan' => [
            (object)['golongan'=>'Golongan I'],
            (object)['golongan'=>'Golongan II'],
            (object)['golongan'=>'Golongan III'],
            (object)['golongan'=>'Golongan IV'],
            (object)['golongan'=>'Golongan V']
        ]
    ]);
}
   public function destroy($id)
{
    DB::table('golongan_pajak')
        ->where('id_golongan_pajak',$id)
        ->delete();

    return response()->json([
        'error'=>false,
        'message'=>'Data berhasil dihapus'
    ]);
}

public function update(Request $request,$id)
{
    DB::table('golongan_pajak')
        ->where('id_golongan_pajak',$id)
        ->update([

            'golongan'=>$request->golongan,

            'pajak_persen'=>$request->pajak_persen,

            'updatedAt'=>now()

        ]);

    return redirect()
        ->route('golongan-pajak.index')
        ->with('success','Data berhasil diubah');
}
    
    private function getGolongan(){
        $res = SIAP::POST('ws_naker_data/data_master_golongan',[]);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $rows = $content->data;
        
        if(!empty($rows)) return $rows;
        else return [];
        
    }
    
}
