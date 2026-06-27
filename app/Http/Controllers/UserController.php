<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function index(){
        return view('user.index');
    }
   public function get(Request $request, $mode = '')
{
    if(empty($mode)){
        $mode = 'datatable';
    }

    $rows = DB::table('users')->get();

    if($mode == 'datatable'){

        $data = [];
        $no = 1;

        foreach($rows as $row){

    $buttons = '
    <a href="'.route('user.edit', $row->id_user).'" class="btn btn-sm btn-warning mr-1">
        Edit
    </a>

    <a href="'.url('user/'.$row->id_user).'" class="btn btn-sm btn-danger btn-delete">
        Hapus
    </a>
';

$data[] = [
    $no++,
    $row->name,
    $row->email,
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

    $result = [
        'data' => []
    ];

    foreach($rows as $row){

        $result['data'][] = [
            'id' => $row->id_user,
            'text' => $row->name
        ];
    }

    return response()->json($result);
}
    
    public function create(){
        return view('user.form');
    }

    public function edit($id)
{
    $user = DB::table('users')
        ->where('id_user',$id)
        ->first();

    return view('user.form')->with([
        'u' => $user
    ]);
}

   public function store(Request $request)
{
    DB::table('users')->insert([
        'id_user' => (string) Str::uuid(),
        'name' => $request->name,
        'email' => $request->email,
        'level' => $request->level ?? 2,
        'password' => Hash::make($request->password),
        'createdAt' => now(),
        'updatedAt' => now()
    ]);

    return redirect()
        ->route('user.index')
        ->with('success','User berhasil ditambahkan');
}

   public function update(Request $request, $id)
{
    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'level' => $request->level,
        'updatedAt' => now()
    ];

    if(!empty($request->password)){
        $data['password'] = Hash::make($request->password);
    }

    DB::table('users')
        ->where('id_user',$id)
        ->update($data);

    return redirect()
        ->route('user.index')
        ->with('success','User berhasil diupdate');
}

  public function destroy($id)
{
    DB::table('users')
        ->where('id_user', $id)
        ->delete();

    return response()->json([
        'error' => false,
        'message' => 'User berhasil dihapus'
    ]);
}
    
}
