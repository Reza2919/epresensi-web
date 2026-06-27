<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;

class SatkerSettingController extends Controller
{
    public function index(){
        $user = session('userdata');
        $res = API::get('satker-setting/'.$user->satkerid);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return view('satker-setting.index')->with([
                'satker_setting' => $data->data,
            ]);
        }
    }
   public function store(Request $request)
{
    return response()->json([
        'success' => true,
        'message' => 'Data Satker berhasil disimpan'
    ]);
}

    public function getSatkerSettingById($idsatker){
        $res = API::get('satker-setting/'.$idsatker);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        return response()->json([
            'success'=>true,
            'data'=> $data->data,
            'message' => $data->message
        ]);
    }
}
