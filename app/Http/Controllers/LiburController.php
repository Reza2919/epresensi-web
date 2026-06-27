<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LiburController extends Controller
{
    public function index()
    {
        return view('libur.index');
    }
    public function get(Request $request, $mode = '')
{
    if(empty($mode)) $mode = 'datatable';

    

    if($mode == 'google-calendar'){

        $rows = [

            (object)['nama_libur'=>'Tahun Baru Masehi','tanggal'=>'2026-01-01'],
            (object)['nama_libur'=>'Isra Mikraj Nabi Muhammad SAW','tanggal'=>'2026-01-16'],
            (object)['nama_libur'=>'Tahun Baru Imlek 2577 Kongzili','tanggal'=>'2026-02-17'],
            (object)['nama_libur'=>'Hari Suci Nyepi','tanggal'=>'2026-03-19'],
            (object)['nama_libur'=>'Idul Fitri 1447 H (Hari Pertama)','tanggal'=>'2026-03-20'],
            (object)['nama_libur'=>'Idul Fitri 1447 H (Hari Kedua)','tanggal'=>'2026-03-21'],
            (object)['nama_libur'=>'Wafat Isa Al Masih','tanggal'=>'2026-04-03'],
            (object)['nama_libur'=>'Hari Buruh Internasional','tanggal'=>'2026-05-01'],
            (object)['nama_libur'=>'Hari Raya Waisak','tanggal'=>'2026-05-20'],
            (object)['nama_libur'=>'Kenaikan Isa Al Masih','tanggal'=>'2026-05-29'],
            (object)['nama_libur'=>'Hari Lahir Pancasila','tanggal'=>'2026-06-01'],
            (object)['nama_libur'=>'Idul Adha 1447 H','tanggal'=>'2026-06-16'],
            (object)['nama_libur'=>'Tahun Baru Islam 1448 H','tanggal'=>'2026-06-26'],
            (object)['nama_libur'=>'Hari Kemerdekaan RI','tanggal'=>'2026-08-17'],
            (object)['nama_libur'=>'Maulid Nabi Muhammad SAW','tanggal'=>'2026-09-05'],
            (object)['nama_libur'=>'Hari Natal','tanggal'=>'2026-12-25']

        ];

        $data = [];

        foreach($rows as $row){

            $exist = DB::table('libur')
                ->where('tanggal',$row->tanggal)
                ->exists();

            if(!$exist){

                $data[] = [

                    '<input type="checkbox"
                        data-nama_libur="'.$row->nama_libur.'"
                        data-tanggal="'.$row->tanggal.'"
                        checked>',

                    $row->nama_libur,

                    \Carbon\Carbon::parse($row->tanggal)->format('d M Y')

                ];

            }

        }

        return response()->json([
            'draw'=>intval($request->draw),
            'recordsTotal'=>count($data),
            'recordsFiltered'=>count($data),
            'data'=>$data
        ]);
    }



    $rows = DB::table('libur')
        ->orderBy('tanggal')
        ->get();

    $result = [
        'draw'=>intval($request->draw),
        'recordsTotal'=>$rows->count(),
        'recordsFiltered'=>$rows->count(),
        'data'=>[]
    ];

    if($mode == 'datatable'){

        $no = 1;

        foreach($rows as $row){

            $result['data'][] = [

                $no++,

                $row->nama_libur,

                \Carbon\Carbon::parse($row->tanggal)->format('d M Y'),

                '
                <a href="'.route('libur.edit',$row->id_libur).'" class="btn btn-icon btn-outline-info">
                    <i data-feather="edit-3"></i>
                </a>

                <a href="'.route('libur.delete',$row->id_libur).'" class="btn btn-icon btn-outline-danger">
                    <i data-feather="delete"></i>
                </a>
                '

            ];

        }

    }else{

        foreach($rows as $row){

            $result['data'][] = [
                'id'=>$row->id_libur,
                'text'=>$row->nama_libur
            ];

        }

    }

    return response()->json($result);
}

    public function create()
    {
        return view('libur.form');
    }

    public function edit($id)
{
    $libur = DB::table('libur')
        ->where('id_libur',$id)
        ->first();

    return view('libur.form',compact('libur'));
}

   public function store(Request $request)
{
    DB::table('libur')->insert([

        'id_libur'=>Str::uuid()->toString(),

        'nama_libur'=>$request->nama_libur,

        'tanggal'=>$request->tanggal,

        'createdAt'=>now(),
        'updatedAt'=>now()

    ]);

    return redirect()
        ->route('libur.index')
        ->with('success','Data berhasil ditambahkan');
}

public function show($id)
{
    return redirect()->route('libur.edit', $id);
}
 

public function destroy($id)
{
    DB::table('libur')
        ->where('id_libur',$id)
        ->delete();

    return redirect()
        ->route('libur.index')
        ->with('success','Data libur berhasil dihapus');
}

    public function store_bulk(Request $request)
{
    foreach($request->libur as $item){

        $cek = DB::table('libur')
            ->where('tanggal',$item['tanggal'])
            ->first();

        if(!$cek){

            DB::table('libur')->insert([

                'id_libur'=>Str::uuid()->toString(),

                'nama_libur'=>$item['nama_libur'],

                'tanggal'=>$item['tanggal'],

                'createdAt'=>now(),

                'updatedAt'=>now()

            ]);

        }

    }

    return response()->json([

        'error'=>false,

        'message'=>'Sinkronisasi berhasil'

    ]);
}

public function update(Request $request,$id)
{
    DB::table('libur')
        ->where('id_libur',$id)
        ->update([

            'nama_libur'=>$request->nama_libur,

            'tanggal'=>$request->tanggal,

            'updatedAt'=>now()

        ]);

    return redirect()
        ->route('libur.index')
        ->with('success','Data berhasil diubah');
}


}
