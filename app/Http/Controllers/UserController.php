<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return view('user.index');
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
        
        $res = API::get('user', $body);
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
                $d[] = $row->name .(session('userdata')->id_user == $row->id_user ? ' <span class="badge badge-info">You</span>' : '' );
                $d[] = $row->email;
                $buttons = '';

                if(session('userdata')->id_user != $row->id_user) $buttons = '<a href="'. route('user.destroy', [$row->id_user]) .'" class="btn btn-icon btn-outline-danger btn-delete" title="Hapus"><i data-feather="delete"></i> </a>';
                $buttons .= '<a href="'. route('user.edit', [$row->id_user]) .'" class="btn btn-icon btn-outline-info" title="Edit"><i data-feather="edit-3"></i> </a>';

                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }
    
            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua User'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_user,
                    'text' => $row->name
                ];
            }
        }

        return response()->json($result);
    }
    
    public function create(){
        return view('user.form');
    }

    public function edit($id){
        $res = API::get('user/'.$id);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('user.form')->with([
                'u' => $data->data
            ]);
        }
        return redirect()->back()->with('error', $data->message);
    }

    public function store(Request $request){
        $input = $request->all();
        $custom_msg = [
            'name.required' => 'Nama harus diisi!.',
            'email.required' => 'Email harus diisi.',
            "password" => "Password harus diisi.",
            'password_confirmation.same' => 'Konfirmasi password tidak sesuai.',
        ];

        $validation = \Validator::make($input, [
            "name" => "required",
            "email" => "required",
            "password" => "required|confirmed",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        $input['password'] = Hash::make($input['password']);
        $res = API::post('user', $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('user.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function update(Request $request,$id){
        $input = $request->all();
        
        $custom_msg = [
            'name.required' => 'Nama harus diisi!.',
            'email.required' => 'Email harus diisi.',
            "password" => "Password harus diisi.",
            'password_confirmation.same' => 'Konfirmasi password tidak sesuai.',
        ];

        $validation = \Validator::make($input, [
            "name" => "required",
            "email" => "required",
            "password" => "required|confirmed",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        
        $input['password'] = Hash::make($input['password']);
        $res = API::post('user/'.$id, $input);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return redirect()->route('user.index')->with('success', $data->message);
        }
        return redirect()->back()->with([
            'error_api' => $data->message ?? '',
            'errors_api' => $data->errors ?? []
        ]);
    }

    public function destroy($id){
        $res = API::delete('user/'.$id);
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
