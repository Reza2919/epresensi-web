<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\Help;
use App\Helpers\SIAP;
use Illuminate\Http\Request;
use App\Models\JobGeneratePresensi;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
   public function get(Request $request, $mode = '', $id)
{
    if($mode == 'datatable'){

        $rows = DB::table('presensi')
            ->where('id_pegawai', $id)
            ->orderBy('tanggal','desc')
            ->get();

        $data = [];
        $no = 1;

        foreach($rows as $row){

            $data[] = [
                $no++,
                date('d-m-Y', strtotime($row->tanggal)),
                $row->jam_masuk,
                $row->jam_keluar,
                'WFO',
                '<button class="btn btn-info btn-sm">Detail</button>'
            ];

        }

        return response()->json([
            'draw' => intval($request->draw ?? 1),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ]);
    }

    return response()->json([
        'data' => []
    ]);
}

    public function getPresensiLog(Request $request){
        if(empty($mode)) $mode = 'datatable';
        $search = @$request->search;
        $length = @$request->length;
        $start = @$request->start;
        $tanggal = @$request->tanggal;
        $id_satker = @$request->satkerid;

        if($mode == 'select2') $search = ['value' => !empty(@$request->q) ? @$request->q : @$request->search];

        $body = [
    'offset' => $start,
    'limit' => $length,
    'search' => $search['value'] ?? '',
];

if($tanggal){
    $body['tanggal'] = $tanggal;
}

if($id_satker){
    $body['id_satker'] = $id_satker;
}

try {

   $rows = DB::table('presensi_change')->get();

    $responseBody = $res->getBody()->getContents();

    $content = json_decode($responseBody);

    if(!$content || !isset($content->data)){
        $sources = (object)[
            'rows' => []
        ];
    }else{
        $sources = $content->data;
    }

} catch (\Exception $e){

    $sources = (object)[
        'rows' => []
    ];

}

$rows = !empty($sources->rows)
    ? $sources->rows
    : [];

if (empty($rows)) {

    $rows = [

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 0,
        'potongan_akhir' => 0,
        'id_presensi' => 1,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Pusat Data dan Teknologi Informasi Ketenagakerjaan',
            'nama_pegawai' => 'Muhammad Reza'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'WFH'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'WFO']
    ],
    

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 1,
        'potongan_akhir' => 0,
        'id_presensi' => 2,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Pusat Data dan Teknologi Informasi Ketenagakerjaan',
            'nama_pegawai' => 'Budi Santoso'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'Hybrid'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'WFO']
    ],

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 0,
        'potongan_akhir' => 0,
        'id_presensi' => 3,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Biro Organisasi dan Sumber Daya Manusia Aparatur',
            'nama_pegawai' => 'Siti Aulia'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'WFH'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'Hybrid']
    ],

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 1,
        'potongan_akhir' => 1,
        'id_presensi' => 4,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Biro Organisasi dan Sumber Daya Manusia Aparatur',
            'nama_pegawai' => 'Andi Saputra'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'WFO'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'WFH']
    ],

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 0,
        'potongan_akhir' => 0,
        'id_presensi' => 5,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Biro Keuangan dan Barang Milik Negara',
            'nama_pegawai' => 'Dwi Hartono'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'WFH'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'WFO']
    ],

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 2,
        'potongan_akhir' => 1,
        'id_presensi' => 6,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Biro Keuangan dan Barang Milik Negara',
            'nama_pegawai' => 'Rina Lestari'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'Hybrid'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'WFO']
    ],

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 0,
        'potongan_akhir' => 0,
        'id_presensi' => 7,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Biro Umum',
            'nama_pegawai' => 'Fajar Nugroho'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'WFH'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'WFO']
    ],

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 1,
        'potongan_akhir' => 0,
        'id_presensi' => 8,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Biro Umum',
            'nama_pegawai' => 'Nina Maharani'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'Hybrid'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'WFO']
    ],

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 2,
        'potongan_akhir' => 1,
        'id_presensi' => 9,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Biro Perencanaan dan Manajemen Kinerja',
            'nama_pegawai' => 'Rizky Pratama'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'WFO'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'Cuti']
    ],

    (object)[
        'createdAt' => now()->toDateTimeString(),
        'user' => 'Admin',
        'potongan_awal' => 0,
        'potongan_akhir' => 0,
        'id_presensi' => 10,
        'presensi' => (object)[
            'tanggal' => now()->toDateString(),
            'nama_satker' => 'Biro Perencanaan dan Manajemen Kinerja',
            'nama_pegawai' => 'Yusuf Ramadhan'
        ],
        'presensi_change_from' => (object)['nama_sistem_kerja' => 'WFH'],
        'presensi_change_to' => (object)['nama_sistem_kerja' => 'WFO']
    ]

];
}
if(!empty($search['value'])){

    $keyword = strtolower($search['value']);

    $rows = collect($rows)->filter(function($row) use ($keyword){

        return
            str_contains(strtolower($row->presensi->nama_pegawai), $keyword) ||
            str_contains(strtolower($row->presensi->nama_satker), $keyword) ||
            str_contains(strtolower($row->user), $keyword) ||
            str_contains(
                strtolower(
                    $row->presensi_change_from->nama_sistem_kerja .
                    ' ' .
                    $row->presensi_change_to->nama_sistem_kerja
                ),
                $keyword
            );

    })->values()->toArray();
}

        if($mode == 'datatable'){
            $data = [];
            $no = 1 + $request->start;
            foreach($rows as $row){
                $d = [];
                $d[] = $no;
                $d[] = \Carbon\Carbon::parse($row->createdAt)->setTimeZone('Asia/Jakarta')->format('d-m-Y H:i:s');
                $d[] = \Carbon\Carbon::parse($row->presensi->tanggal)->setTimeZone('Asia/Jakarta')->format('d-m-Y');
                $d[] = $row->presensi->nama_satker;
                $d[] = $row->user;
                $d[] = $row->presensi->nama_pegawai;
                $d[] = $row->presensi_change_from->nama_sistem_kerja.' -> '.$row->presensi_change_to->nama_sistem_kerja;
                $d[] = $row->potongan_awal;
                $d[] = $row->potongan_akhir;
                $buttons = '-';
                $d[] = $buttons;
                $data[] = $d;
                $no++;
            }

            $result['data'] = $data;
$result['recordsTotal'] = count($data);
$result['recordsFiltered'] = count($data);
        } else {
            if($request->optionAll) $result['data'][] = ['id' => '0', 'text' => 'Semua Presensi'];
            foreach($rows as $row){
                $result['data'][] = [
                    'id' => $row->id_presensi,
                    'text' => $row->presensi->nama_pegawai
                ];
            }
        }

        return response()->json($result);
    }
  public function edit($id)
{
    $pegawai = (object)[
        'pegawaiid' => 1,
        'nama' => 'Muhammad Reza',
        'namajabatan' => 'Programmer',
        'satker' => 'Pusat Data dan Teknologi Informasi Ketenagakerjaan',
        'eselon' => '-',
        'gol' => 'III/A',
        'nip' => '198501010001',
        'tempatlahir' => 'Jakarta',
        'tgllahir' => '01 Januari 2005',
        'alamat' => 'Jakarta'
    ];

    $presensi = (object)[
        'id_presensi' => $id,
        'id_pegawai' => 1,
        'id_sistem_kerja' => 1,
        'tanggal' => now()->toDateString(),
        'jam_masuk' => '08:00',
        'jam_keluar' => '17:00',
        'lokasi_masuk' => 'Kantor Pusat',
        'lokasi_keluar' => 'Kantor Pusat',
        'foto_masuk' => '',
        'foto_keluar' => '',
        'sum_potongan' => 0,
        'potongan_tukin' => [],
        'sistem_kerja' => (object)[
            'nama_sistem_kerja' => 'WFO'
        ]
    ];

    $hari = 'Senin';

    return view('presensi.form', compact(
        'pegawai',
        'presensi',
        'hari'
    ));
}
    public function presensiLog(){
        return view('presensi-change.index');
    }

    public function getDataPegawai($id){
        $body['pegawaiid'] = $id;
        $res = SIAP::post('ws_naker_data/data_pns',$body);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $data = @$content->data;
        return $data[0];
    }

   public function save(Request $request, $id)
{
    return redirect('/jurnal/'.$id)
        ->with('success', 'Data presensi berhasil disimpan');
}

    public function setTidakPresensi(Request $request,$id){
        $res = API::post('presensi/'.$id.'/set-tidak-presensi',[]);
        if($res->getStatusCode() == 200 ){
            $body = $res->getBody()->getContents();
            $data = json_decode($body);
            return response()->json([
                'code' => $data->code,
                'message' => $data->message,
                'error' => $data->code == 200 ? false : true,
                'error_api' => $data->code == 200 ? null : $data->message,
                'errors_api' => $data->code == 200 ? [] : $data->errors
            ]);
        }else{
            return response()->json([
                'code' => $res->getStatusCode(),
                'message' => 'Page not found',
                'error' => 'Page not found'
            ]);
        }
    }

    public function generatePresensi(){
        return view('presensi.generate');
    }
    public function doGeneratePresensi(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date_format:Y-m-d',
    ]);

    JobGeneratePresensi::create([
        'satker' => 'Kementerian Ketenagakerjaan',
        'jenis_laporan' => 'Rekap Presensi',
        'bulan' => date('m', strtotime($request->tanggal)),
        'tahun' => date('Y', strtotime($request->tanggal)),
        'status' => 'Berhasil',
        'message' => 'Generate berhasil',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect('generate-presensi')
        ->with('success', 'Generate berhasil');
}

    public function getListJob(Request $request) {
        $length = @$request->length;
        $start = @$request->start;
        $data = JobGeneratePresensi::limit($length)->offset($start)->orderBy('created_at', 'desc')->get();

        $rows = !empty($data) ? $data : [];

        $data = [];
        $no = 1 + $request->start;
        foreach($rows as $row){
            $createdAt = \Carbon\Carbon::parse($row->created_at);
            $d = [];
            $d[] = $no;
           $d[] = $row->bulan . '/' . $row->tahun;
            $d[] = '10';
            if ($row->status == "Berhasil") {
                $d[] = '<span class="badge badge-light-success">'.$row->status.'</span>';
            } else if ($row->status == "Gagal") {
                $d[] = '<span class="badge badge-light-danger">'.$row->status.'</span>';
            } else {
                $d[] = '<span class="badge badge-light-warning">'.$row->status.'</span>';
            }
            $d[] = $row->message;
            $d[] = $createdAt->format('d M Y H:i');

            $data[] = $d;
            $no++;
        }
        $total = JobGeneratePresensi::all()->count();

$result['draw'] = intval($request->draw);
$result['data'] = $data;
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

return response()->json($result);

        return response()->json($result);
    }
  public function getTrendPresensi()
{
    return response()->json([
        'status' => 'success',
        'data' => [
            'label' => [
                '16 Jun',
                '17 Jun',
                '18 Jun',
                '19 Jun',
                '20 Jun'
            ],
            'hadir' => [8, 9, 8, 10, 9],
            'tidak_hadir' => [2, 1, 2, 0, 1]
        ],
        'message' => 'Success'
    ]);
}

    public function destroy($id){
        $res = API::delete('presensi/'.$id);
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
