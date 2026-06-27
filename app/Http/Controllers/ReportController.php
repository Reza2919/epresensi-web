<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('report.index', [
            'jobs' => []
        ]);
    }

    public function getSatker()
    {
        $res = SIAP::get('ws_naker_data/get_satker');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);

        return $content->data ?? [];
    }

    public function print(Request $request)
    {
        return redirect('/laporan-presensi')
            ->with('success', 'Dummy laporan berhasil dibuat');
    }

   public function getListJob(Request $request)
{
    $jobs = DB::table('job_generate_laporan_presensi')
        ->orderBy('id', 'desc')
        ->get();

    $data = [];

    foreach ($jobs as $job) {

        $badge = $job->status == 'Berhasil'
            ? '<span class="badge badge-success">Berhasil</span>'
            : '<span class="badge badge-warning">' . $job->status . '</span>';

        $data[] = [
            $job->id,
            $job->satker,
            $job->jenis_laporan,
            $job->bulan,
            $job->tahun,
            $badge,
            $job->message,
            date('d M Y H:i', strtotime($job->created_at)),
            '<a href="'.url('download-laporan-presensi/'.$job->id).'" class="btn btn-primary btn-sm">Download</a>'
        ];
    }

    return response()->json([
        'draw' => intval($request->draw),
        'recordsTotal' => count($data),
        'recordsFiltered' => count($data),
        'data' => $data
    ]);
}

public function downloadFileJob($id)
{
    $job = DB::table('job_generate_laporan_presensi')
        ->where('id', $id)
        ->first();

    if (!$job || !$job->path) {
        return redirect()->back()
            ->with('error', 'File tidak ditemukan');
    }

    $file = storage_path('app/' . $job->path);

    if (!file_exists($file)) {
        return redirect()->back()
            ->with('error', 'File tidak ada di server');
    }

    return response()->download($file);

}

    public function destroyListJob($id)
    {
        return response()->json([
            'success' => true
        ]);
    }
}
