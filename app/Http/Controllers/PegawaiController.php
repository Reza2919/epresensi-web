<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\Help;
use App\Helpers\SIAP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index(){
        return view('pegawai.index', [
            'satkers' => $this->getSatker()
        ]);
    }

    /**
     * =========================
     * LIST + SEARCH + DATATABLE
     * =========================
     */
    public function get(Request $request, $mode = '')
    {   
    
        if(empty($mode)) $mode = 'datatable';

        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        if($length == 0) $length = 10;

        // SEARCH FIX
        $search = $request->search['value'] ?? $request->search ?? null;

       $rows = DB::table('pegawai')->get()->toArray();
       $totalData = count($rows);

     
        if(empty($rows)){

   
}

if(!empty($search)){

    $rows = collect($rows)->filter(function($row) use ($search){

        return
            stripos($row->nama, $search) !== false ||
            stripos($row->nip, $search) !== false ||
            stripos($row->jabatan, $search) !== false ||
            stripos($row->satker, $search) !== false;

    })->values()->toArray();

    $totalData = count($rows);
}

        if($mode == 'datatable'){

            $data = [];
            $no = 1 + $start;

            foreach($rows as $row){

    $id = Help::encrypt_encode($row->id);

    $data[] = [
        $no++,

        '<div class="d-flex align-items-center">
            <img src="'.asset('assets/app-assets/images/profile/default-user.jpg').'" width="35" class="mr-2 rounded-circle">
            '.$row->nama.'
        </div>',

        $row->nip ?? '-',
        $row->jabatan ?? '-',
        $row->satker ?? '-',
        $row->status ?? 'Aktif',

                    // ACTION FULL CRUD
                    '<div class="btn-group">

                        <a href="'.url("pegawai/".$id).'" class="btn btn-sm btn-info">
                            Detail
                        </a>

                        <a href="'.url("pegawai/".$id."/edit").'" class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <a href="'.url("pegawai/".$id."/delete").'" class="btn btn-sm btn-danger btn-delete">
                            Hapus
                        </a>

                    </div>'
                ];
            }

            return response()->json([
                'data' => $data,
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalData
            ]);
        }

        $result = [
    'data' => []
];

foreach($rows as $row){

    $result['data'][] = [
    'id' => $row->id.'|'.$row->nama,
    'text' => $row->nama
];
}

return response()->json($result);
    }

    /**
     * =========================
     * DETAIL
     * =========================
     */
   public function show($id)
{
    $id = Help::decrypt_decode($id);
    $data = $this->getDataPegawai($id);

    return view('pegawai.detail', [
        'pegawai' => $data,
        'id' => Help::encrypt_encode($id),
        'id_pegawai' => $id
    ]);
}

    /**
     * =========================
     * EDIT VIEW
     * =========================
     */
  public function edit($id)
{
    $id = Help::decrypt_decode($id);

    $pegawai = DB::table('pegawai')
        ->where('id', $id)
        ->first();

    return view('pegawai.edit', compact('pegawai'));
}

    /**
     * =========================
     * UPDATE (DUMMY READY)
     * =========================
     */
    public function update(Request $request, $id)
{
    $id = Help::decrypt_decode($id);

    DB::table('pegawai')
        ->where('id', $id)
        ->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'satker' => $request->satker,
            'status' => $request->status,
            'updated_at' => now()
        ]);

    return redirect('/pegawai')
        ->with('success', 'Pegawai berhasil diupdate');
}

    /**
     * =========================
     * DELETE (DUMMY SAFE)
     * =========================
     */
   public function delete($id)
{
    $id = Help::decrypt_decode($id);

    DB::table('pegawai')
        ->where('id', $id)
        ->delete();

    return redirect('/pegawai')
        ->with('success', 'Pegawai berhasil dihapus');
}

    /**
     * =========================
     * GET DETAIL PEGAWAI
     * =========================
     */
  public function getDataPegawai($id)
{
    return DB::table('pegawai')
        ->where('id', $id)
        ->first();
}

    /**
     * =========================
     * SATKER
     * =========================
     */
    public function getSatker()
    {
        $res = SIAP::get('ws_naker_data/get_satker');
        $content = json_decode($res->getBody()->getContents());

        return $content->data ?? [];
    }

    /**
     * =========================
     * VALIDASI
     * =========================
     */
    public function hasSpecialCharacters($s)
    {
        return preg_match('/[<>\'\"\/&]/', $s);
    }
   public function jurnal($id)
{
    $pegawai = (object)[
        'pegawaiid' => 1,
        'nama' => 'Muhammad Reza',
        'namajabatan' => 'Programmer',
        'satker' => 'Pusat Data dan Teknologi Informasi Ketenagakerjaan',
        'eselon' => '-',
        'gol' => 'III/A',
        'nik' => '317xxxxxxxxx',
        'nip' => '198501010001',
        'tempatlahir' => 'Jakarta',
        'tgllahir' => '01 Januari 2005',
        'alamat' => 'Jakarta',
        'grade' => '8'
    ];

    $presensi = (object)[
        'id_presensi' => $id,
        'tanggal' => now()->toDateString(),
        'jam_masuk' => '08:00',
        'jam_keluar' => '17:00',
        'lokasi_masuk' => 'Kantor Pusat',
        'lokasi_keluar' => 'Kantor Pusat',
        'foto_masuk' => '',
        'foto_keluar' => '',
        'sum_potongan' => 0,

        'sistem_kerja' => (object)[
            'nama_sistem_kerja' => 'WFO',
            'is_cuti' => 0
        ],

        'potongan_tukin' => [],

        'jurnal' => [
            (object)[
                'judul' => 'Presensi Masuk',
                'keterangan' => 'Masuk kantor tepat waktu',
                'createdAt' => now()
            ]
        ],

        'presensi_change' => [
            (object)[
                'createdAt' => now(),
                'user' => 'Admin',
                'potongan_awal' => 0,
                'potongan_akhir' => 0,
                'presensi_change_from' => (object)[
                    'nama_sistem_kerja' => 'WFH'
                ],
                'presensi_change_to' => (object)[
                    'nama_sistem_kerja' => 'WFO'
                ]
            ]
        ]
    ];

    $hari = now()->translatedFormat('l');

    return view('pegawai.jurnal', compact(
        'pegawai',
        'presensi',
        'hari'
    ));
}
public function create()
{
    return view('pegawai.create');
}
public function store(Request $request)
{
    DB::table('pegawai')->insert([
        'nama' => $request->nama,
        'nip' => $request->nip,
        'jabatan' => $request->jabatan,
        'satker' => $request->satker,
        'status' => $request->status,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect('/pegawai')
        ->with('success','Pegawai berhasil ditambahkan');
}
}