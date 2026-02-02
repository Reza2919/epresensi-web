<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use Illuminate\Http\Request;

class SatkerKoordinatController extends Controller
{
    public function getSatkerKoordinat(Request $request,$id){
        $res = API::get('satker-koordinat/'.$id.'/get-by-satker');
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($data->code == 200){
            return $data->data;
        }
        return [];
    }
    public function store(Request $request){
        
        $user = session('userdata');
        $input = $request->all();
        
        $custom_msg = [
            'keterangan.required' => 'Keterangan harus diisi!.',
            'lat.required' => 'Latiude harus diisi.',
            'long.required' => 'Longitude harus diisi',
        ];

        $validation = \Validator::make($input, [
            "keterangan" => "required",
            "lat" => "required",
            "long" => "required",
        ], $custom_msg);

        if($validation->fails()) return redirect()->back()->withInput()->with('errors', $validation->errors());
        if(!$request->id_satker){
            $satkerid = $user->satkerid;
        }else{
            $satkerid = $request->id_satker;
        }
        $res = API::post('satker-koordinat/create', [
            'id_satker' => $satkerid,
            'keterangan' => $request->keterangan,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if($res->getStatusCode() == 200){
            return response()->json([
                'success'=>true,
                'message' => $data->message
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message' => $res->getStatusCode()
            ]);
        }
    }
    public function destroy($id){
        $res = API::delete('satker-koordinat/'.$id);
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
