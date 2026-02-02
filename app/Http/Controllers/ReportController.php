<?php

namespace App\Http\Controllers;

use App\Exports\RekapPresensiBulananExport;
use App\Helpers\API;
use App\Helpers\SIAP;
use App\Models\JobGenerateLaporanPresensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Validator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $jobs = [];
        $user = session('userdata');

        if ($user->role == "tu") {
            $jobs = JobGenerateLaporanPresensi::where('id_pegawai', $user->id_pegawai)->where('id_satker', $user->satkerid)->get();
        } else {
            $jobs = JobGenerateLaporanPresensi::all();
        }

        $jobs = "";

        return view('report.index', [
            'jobs' => $jobs
        ]);
    }

    public function getSatker()
    {
        $res = SIAP::get('ws_naker_data/get_satker');
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $rows = $content->data;

        if (!empty($rows)) return $rows;
        else return [];
    }
    public function print(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'satkerid' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Harap pilih satker!');
        }
        $filenames = ['presensi_per_bulan' => 'Rekap Presensi Per Bulan', 'tukin_per_bulan' => 'Rekap Tunjangan Kinerja Per Bulan', 'tukin_kelas_jabatan' => 'Rekap Tunjangan Kinerja Per Kelas Jabatan', 'tukin_rekening' => 'Rekap Tunjangan Kinerja Rekening'];
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $type = $request->type;
        $id_satker = $request->satkerid;
        $nama_satker = $request->nama_satker;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $user = session('userdata');

        $body = [
            'type' => $type,
            'id_satker' => $id_satker,
            'nama_satker' => $nama_satker,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulan_text' => $request->bulan_text,
            'tahun_text' => $request->tahun_text,
            'type_satker' => $request->type_text,
            'id_pegawai' => @$user->id_pegawai,
            'id_satker_user' => @$user->satkerid,
        ];
        $res = API::post('presensi/export', $body);
        $body = $res->getBody();

        return redirect('/laporan-presensi')->with('success', "Laporan sedang diproses silahkan cek secara berkala");
    }

    public function getListJob(Request $request)
    {
        $user = session('userdata');
        $length = @$request->length;
        $start = @$request->start;
        $draw = @$request->draw;

        if ($user->role != 'admin') {
            $data = JobGenerateLaporanPresensi::where('id_satker', $user->satkerid)->where('id_pegawai', $user->id_pegawai)
                ->skip($start)
                ->take($length)->orderBy('created_at', 'desc')->get();
        } else {
            $data = JobGenerateLaporanPresensi::skip($start)->take($length)->orderBy('created_at', 'desc')->get();
        }

        $rows = !empty($data) ? $data : [];

        $baseUrl = env('QUEUE_URL', 'http://localhost:6500/');

        $data = [];
        $no = 1 + $request->start;
        foreach ($rows as $row) {
            $createdAt = Carbon::parse($row->created_at);
            $buttons = [];
            $d = [];
            $d[] = $no;
            $d[] = $row->satker;
            $d[] = $row->jenis_laporan;
            $d[] = $row->bulan;
            $d[] = $row->tahun;
            if ($row->status == "Berhasil") {
                $d[] = '<span class="badge badge-light-success">' . $row->status . '</span>';
            } else if ($row->status == "Gagal") {
                $d[] = '<span class="badge badge-light-danger">' . $row->status . '</span>';
            } else {
                $d[] = '<span class="badge badge-light-warning">' . $row->status . '</span>';
            }
            $d[] = $row->message;
            $d[] = $createdAt->format('d M Y H:i');
            if ($row->path) {
                if ($row->from == "fe") {
                    $buttons = '<a href="download-laporan-presensi/' . $row->id . '" class="btn btn-icon btn-primary btn-download" title="Download">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                     </a>
                      <a href="list-laporan-presensi/' . $row->id . '" class="btn btn-icon btn-danger btn-delete" title="Hapus">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </a>';
                } else {
                    $buttons = '<a href="' . $baseUrl . 'download/' . $row->id . '" target="_blank" class="btn btn-icon btn-primary btn-download" title="Download">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                       </a>
                    <a href="list-laporan-presensi/' . $row->id . '" class="btn btn-icon btn-danger btn-delete" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                     </a>';
                }
            } else {
                $buttons = '<a href="list-laporan-presensi/' . $row->id . '" class="btn btn-icon btn-danger btn-delete" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> </a>';
            }
            $d[] = $buttons;
            $data[] = $d;
            $no++;
        }
        $total = JobGenerateLaporanPresensi::all()->count();
        $result['draw'] = intval($draw);
        $result['recordsFiltered'] = $total;
        $result['recordsTotal'] = $total;
        $result['data'] = $data;

        return response()->json($result);
    }

    public function downloadFileJob($id)
    {
        $data = JobGenerateLaporanPresensi::where('id', $id)->first();

        if ($data->path != null) {
            $data->delete();
            return response()->download($data->path)->deleteFileAfterSend(true);
        }

        return redirect('/laporan-presensi')->with('error', "Terjadi kesalahan");
    }

    public function destroyListJob($id)
    {
        $data = JobGenerateLaporanPresensi::where('id', $id);
        $first = $data->first();
        if ($first->path != null) {
            if ($first->from == "fe") {
                if (file_exists($first->path)) unlink(public_path($data->first()->path));
            }
        }
        $data->delete();
        return response()->json("Success");
    }
}
