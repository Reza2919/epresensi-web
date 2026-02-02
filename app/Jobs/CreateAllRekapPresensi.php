<?php

namespace App\Jobs;

use App\Models\JobGenerateLaporanPresensi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PDF;
use File;
use ZipArchive;
use App\Helpers\API;
use App\Helpers\SIAP;

class CreateAllRekapPresensi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $zip = new ZipArchive;

        $dataPegawai = $this->getDataPegawaiBySatker($this->payload->idsatker,  $this->payload->token);
        if (!file_exists(public_path('/zip'))) mkdir(public_path('/zip', 0755, true));
        $res = $zip->open(public_path('/zip/'.$this->payload->fileName), ZipArchive::CREATE);
        $files = [];
        $data = $this->payload->data;
        $body = $this->payload->body;
        $fileName = $this->payload->fileName;

        if ($res === TRUE) {
            foreach ($dataPegawai as $key => $pegawai) {
                $data['pegawai'] = $pegawai;
                $data['rekap_tukin'] = $this->getRekapTukin($pegawai->pegawaiid, $this->payload->bulan, $this->payload->tahun, $this->payload->token);
                $res = API::get('presensi/' . $pegawai->pegawaiid . '?bulan=' . $this->payload->bulan . "&tahun=" . $this->payload->tahun, [], [
                    'Authorization' => 'Bearer ' . $this->payload->token
                ]);
                $body = $res->getBody()->getContents();
                $content = json_decode($body);
                $sources = $content->data;

                $rows = !empty($sources) ? $sources->rows : [];
                $menitKerja = 0;
                foreach ($rows as $key => $p) {
                    $menitKerja += $p->menit_kerja;
                }
                $data['rekap'] = $rows;
                //get statistic
                $res = API::get('presensi-statistic?id_pegawai=' . $pegawai->pegawaiid, [], [
                    'Authorization' => 'Bearer ' . $this->payload->token
                ]);
                $body = $res->getBody()->getContents();

                $content = json_decode($body);
                if ($res->getStatusCode() == 200) {
                    $sources = $content->data;
                    $data['statistic'] = !empty($sources) ? $sources : [];
                    //calculate weekdays in a month
                    $weekdays = $this->countDays($this->payload->tahun, $this->payload->bulan, [0, 6]);
                    //end
                    $kewajiban = ($weekdays - $this->countLiburMonthly($this->payload->bulan, $this->payload->tahun, $this->payload->token)) * 7.5;
                    // dd($menitKerja);
                    $data['jam_kerja'] = [
                        'kewajiban' => $kewajiban,
                        'kekurangan' => ($menitKerja / 60) - $kewajiban,
                    ];
                    // return view('presensi.rekap-presensi',$data);
                    $pdf = PDF::loadView('presensi.rekap-presensi', $data);
                    $output = $pdf->output();
                    file_put_contents($this->payload->dir . '/' . "rekap-presensi-" . $pegawai->pegawaiid . "-" . $pegawai->nama . ".pdf", $output);
                    $zip->addFile($this->payload->dir . '/' . "rekap-presensi-" . $pegawai->pegawaiid . "-" . $pegawai->nama . ".pdf", "rekap-presensi-" . $pegawai->pegawaiid . "-" . $pegawai->nama . ".pdf");
                    $files[] = $this->payload->dir . '/' . "rekap-presensi-" . $pegawai->pegawaiid . "-" . $pegawai->nama . ".pdf";
                } else {
                    JobGenerateLaporanPresensi::where('id', $this->payload->job_id)->update(['status' => 'Gagal', 'message' => $res->getStatusCode()]);
                    Log::error(json_encode([
                        'status' => $res->getStatusCode(),
                        'message' => $res->getReasonPhrase()
                    ]));
                }
            }
        } else {
            Log::error('Gagal membuat arsip.');
            JobGenerateLaporanPresensi::where('id', $this->payload->job_id)->update(['status' => 'Gagal']);
        }

        $zip->close();

        foreach ($files as $key => $f) {
            unlink($f);
        }

        Log::info(public_path('/zip/'.$this->payload->fileName));

        if (!file_exists(public_path('/zip/'.$this->payload->fileName))) {
            Log::error('Jumlah pegawai : ' . count($dataPegawai) . '. File created : ' . count($files) . '. Gagal membuat arsip.');
            JobGenerateLaporanPresensi::where('id', $this->payload->job_id)->update(['status' => 'Gagal', 'message' => 'Jumlah pegawai : ' . count($dataPegawai) . '. File created : ' . count($files) . '. Gagal membuat arsip.']);
        } else {
            JobGenerateLaporanPresensi::where('id', $this->payload->job_id)->update(['status' => 'Berhasil', 'path' => 'zip/'.$fileName]);
        }


    }

//    public function failed($exception)
//    {
//        JobGenerateLaporanPresensi::where('id', $this->payload->job_id)
//            ->update(['status' => 'Gagal', 'message' => $exception->getMessage()]);
//    }

    public function getDataPegawaiBySatker($idsatker, $token)
    {
        $res = API::get('pegawai/bysatker/' . $idsatker, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        if ($res->getStatusCode() == 200) {
            $data = $content->data;
            return $data;
        } else {
            return [];
        }
    }

    public function getRekapTukin($id, $bulan, $tahun, $token)
    {
        $body = [
            'id_pegawai' => $id,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ];
        $res = API::post('rekap-tukin', $body,  [
            'Authorization' => 'Bearer ' . $token
        ]);
        $body = $res->getBody()->getContents();
        $content = json_decode($body);
        $data = @$content->data;
        return $data;
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

    private function countLiburMonthly($bulan, $tahun, $token)
    {
        $res = API::get('libur-count?bulan=' . $bulan . '&tahun=' . $tahun, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $body = $res->getBody()->getContents();
        $data = json_decode($body);
        if ($data->code == 200) {
            return $data->data->count;
        } else {
            return 0;
        }
    }
}
