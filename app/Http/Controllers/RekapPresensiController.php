<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\Help;
use App\Helpers\SIAP;
use App\Jobs\CreateAllRekapPresensi;
use App\Models\JobGenerateLaporanPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;
use File;
use stdClass;
use ZipArchive;

class RekapPresensiController extends Controller
{
    public function index()
    {
        $data['satkers'] = $this->getSatker();
        return view('presensi.index', $data);
    }

    public function get(Request $request, $mode = '')
    {
        $user = session('userdata');
        if (empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;
        $idsatker = $user->satkerid;
        // $bulan = @$request->bulan;
        // $tahun = @$request->tahun;
        $tanggal = @$request->tanggal;
        $id_satker_bidang = @$request->id_satker_bidang;

        if ($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
            'offset' => $start,
            'limit' => $length,
            'search' => $search,
            'id_satker' => $idsatker
        ];

        if ($tanggal) {
            $body['tanggal'] = $tanggal;
            // $body['tahun'] = $tahun;
        }
        if ($id_satker_bidang) {
            $body['id_satker_bidang'] = $id_satker_bidang;
            // $body['tahun'] = $tahun;
        }
        $res = API::get('presensi', $body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;
        $rows = !empty($sources) ? $sources->rows : [];
        if ($mode == 'datatable') {
            $data = [];
            $no = 1 + $request->start;
            foreach ($rows as $row) {
                $d = [];
                $d[] = $no;
                $d[] = $row->bidang_pegawai->bidang->nama_bidang ?? '-';
                $d[] = $row->nama_pegawai;
                $d[] = $row->tanggal;
                $d[] = $row->jam_masuk;
                $d[] = $row->jam_keluar;
                $d[] = $row->sistem_kerja->nama_sistem_kerja;
                $buttons = '<a href="' . route("pegawai.jurnal", [$row->id_presensi]) . '" class="btn btn-icon btn-info btn-detail mb-1" title="Detail Presensi"><i data-feather="list"></i> </a> <a href="#" class="btn btn-icon btn-primary btn-show" data-foto-masuk="'.env('API_URL').'/'.$row->foto_masuk.'" data-foto-keluar="'.env('API_URL').'/'.$row->foto_keluar.'" data-jam-masuk="'.$row->jam_masuk.'" data-jam-keluar="'.$row->jam_keluar.'" title="Detail Foto"><i data-feather="eye"></i> </a>';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
            $result['recordsTotal'] = $sources->count;
            $result['recordsFiltered'] = $sources->count;
        } else {
            if ($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Periode Tukin'];
            foreach ($rows as $row) {
                $result['data'][] = [
                    'id' => $row->id_periode,
                    'text' => $row->periode
                ];
            }
        }

        return response()->json($result);
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

    public function show($id)
    {
        $data = $this->getDataPresensi($id);
        return view('presensi.detail', ['presensi' => $data, 'id_presensi' => $id]);
    }

    public function rekapPresensiPdf(Request $request, $id)
    {
        $id = Help::decrypt_decode($id);
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $isDownload = $request->is_download;
        $data['pegawai'] = $this->getDataPegawai($id);
        $data['rekap_tukin'] = $this->getRekapTukin($id, $bulan, $tahun);

        $body['bulan'] = $bulan;
        $body['tahun'] = $tahun;
        $res = API::get('presensi/' . $id . '?' . http_build_query($body));
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $content = json_decode($body);

$rows = [];

if (!isset($content->data)
    || !isset($content->data->rows)
    || empty($content->data->rows)) {

    $rows = [];

} else {
    $rows = $content->data->rows;
}
        $menitKerja = 0;
        foreach ($rows as $key => $p) {
            $menitKerja += $p->menit_kerja;
        }
        $data['rekap'] = $rows;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        //get statistic
        $res = API::get('presensi-statistic?id_pegawai='.$id.'&bulan='.$bulan.'&tahun='.$tahun);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        if ($res->getStatusCode() == 200) {
            $sources = $content->data;
            $data['statistic'] = !empty($sources) ? $sources : [];
            //calculate weekdays in a month
            $weekdays = $this->countDays($tahun, $bulan, [0, 6]);
            //end
            $kewajiban = ($weekdays - $this->countLiburMonthly($bulan, $tahun)) * 7.5;
            // dd($menitKerja);
            $data['jam_kerja'] = [
                'kewajiban' => $kewajiban,
                'kekurangan' => ($menitKerja / 60) - $kewajiban,
            ];
             //return view('presensi.rekap-presensi',$data);
            $pdf = PDF::loadView('presensi.rekap-presensi', $data);
            if ($isDownload == 1) return $pdf->download('rekap-presensi-' . $id . '-' . $bulan . '-' . $tahun . '.pdf');
            return $pdf->stream('rekap-presensi-' . $id . '-' . $bulan . '-' . $tahun . '.pdf');
        } else {
            return view('errors.error', [
                'status' => $res->getStatusCode(),
                'message' => $res->getReasonPhrase()
            ]);
        }

    }

    public function rekapPresensiAllPdf(Request $request, $idsatker)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $user = session('userdata');
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $body['bulan'] = $bulan;
        $body['tahun'] = $tahun;
        $fileName = $request->type_text. '-'. $request->bulan_text.'-'.$request->tahun_text.'-'. $request->nama_satker. ".zip";
        $dir = public_path() . '/rekap-presensi-' . $bulan . "-" . $tahun . "-" . $idsatker;
        if (!file_exists($dir)) mkdir($dir, 0755, true);

        $payload = new stdClass();
        $payload->fileName = $fileName;
        $payload->bulan = $bulan;
        $payload->bulan_text = $request->bulan_text;
        $payload->tahun_text = $request->tahun_text;
        $payload->type_satker = $request->type_text;
        $payload->nama_satker = $request->nama_satker;
        $payload->tahun = $tahun;
        $payload->dir = $dir;
        $payload->idsatker = $idsatker;
        $payload->id_pegawai = @$user->id_pegawai;
        $payload->id_satker_user = @$user->satkerid;
        $payload->token = session('token');
        $payload->data = $data;
        $payload->body = $body;

        $jobModel = JobGenerateLaporanPresensi::create([
            'satker' => $payload->nama_satker,
            'jenis_laporan' => $payload->type_satker,
            'bulan' => $payload->bulan_text,
            'tahun' => $payload->tahun_text,
            'status' => "Dalam Proses",
            'path' => null,
            'from' => 'fe',
            'id_pegawai' => $payload->id_pegawai,
            'id_satker' => $payload->id_satker_user,
        ]);

        $payload->job_id = $jobModel->id;

        CreateAllRekapPresensi::dispatch($payload);
        return redirect('/laporan-presensi')->with('success', "Laporan sedang diproses silahkan cek secara berkala");
    }

    public function getDataPegawaiBySatker($idsatker)
    {

        $res = API::get('pegawai/bysatker/' . $idsatker);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        if ($res->getStatusCode() == 200) {
            $data = $content->data;
            return $data;
        } else {
            return [];
        }
    }

    public function rekapTukinPdf(Request $request, $id)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $isDownload = $request->is_download;
        $pegawai = $this->getDataPegawai($id);
        $rekapTukin = $this->getRekapTukin($id, $bulan, $tahun);
        $data['pegawai'] = $pegawai;
        $data['rekap_tukin'] = $rekapTukin;
        $body['bulan'] = $bulan;
        $body['tahun'] = $tahun;
        $res = API::get('presensi/' . $id . '?' . http_build_query($body));
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $sources = $content->data;

        $rows = !empty($sources) ? $sources->rows : [];
        $data['presensi'] = $rows;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $pdf = PDF::loadView('presensi.rekap-tukin', $data);
        if ($isDownload == 1) return $pdf->download('rekap-tukin-' . $id . '-' . $bulan . '-' . $tahun . '.pdf');
        return $pdf->stream('rekap-tukin-' . $id . '-' . $bulan . '-' . $tahun . '.pdf');
    }

    public function getDataPegawai($id)
    {
        $body['pegawaiid'] = $id;
        $res = SIAP::post('ws_naker_data/data_pns', $body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $data = @$content->data;
        return $data[0];
    }


    public function getRekapTukin($id, $bulan, $tahun)
    {
        $body = [
            'id_pegawai' => $id,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ];
        $res = API::post('rekap-tukin', $body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $data = @$content->data;
        return $data;
    }

    private function countLiburMonthly($bulan, $tahun)
    {
        $res = API::get('libur-count?bulan=' . $bulan . '&tahun=' . $tahun);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if ($data->code == 200) {
            return $data->data->count;
        } else {
            return 0;
        }
    }

    private function countDays($year, $month, $ignore)
    {
        $count = 0;
        $counter = mktime(0, 0, 0, $month, 1, $year);
        while (date("n", $counter) == $month) {
            if (in_array(date("w", $counter), $ignore) == false) {
                $count++;
            }
            $counter = strtotime("+1 day", $counter);
        }
        return $count;
    }
    public function getPresensiLatLong()
    {
    return response()->json([
    'rows' => []
]);
}
}
