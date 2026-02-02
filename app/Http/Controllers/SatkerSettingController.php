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
    public function store(Request $request){
        $input = $request->all();
        $custom_msg = [
            'zona_waktu.required' => 'Zona waktu harus diisi.',
            // 'long.required' => 'Longitude harus diisi.',
        ];

        $validation = \Validator::make($input, [
            "zona_waktu" => "required",
        ], $custom_msg);

        if($validation->fails()) return response()->json($validation->errors());
        if($request->id_satker_setting != null) {
            $res = API::post('satker-setting/set-geotagging', $input);
        }else{
            $res = API::post('satker-setting/create', $input);
        }

        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return response()->json([
                'success'=>true,
                'data'=> $data->data,
                'message' => $data->message
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message' => $data->message
            ]);
        }
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
