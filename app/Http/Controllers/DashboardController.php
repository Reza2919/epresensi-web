<?php

namespace App\Http\Controllers;
use App\Helpers\API;
use Session;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.dashboard');
    }
    public function getStatistic(){
        $date_start = request()->date_start;
        $date_end = request()->date_end;
        $satkerid = request()->satkerid;
        $res = API::get('presensi-statistic-filter?date_start='.$date_start.'&date_end='.$date_end.'&satkerid='.$satkerid);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        $d = [];
        if($res->getStatusCode() == 200){
            return response()->json($data->data);
        }else{
            return response()->json([]);
        }
    }
}
