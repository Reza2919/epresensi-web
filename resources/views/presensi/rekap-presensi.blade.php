<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Presensi Pegawai</title>
    <style>
        body{
            padding: 0;
            margin: 0;
        }
        table {
            border-collapse: collapse;
        }

        th,td {
            padding: 3px 2px;
        }
        thead{
            background-color: #f3f2f7;
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }
        table{
            width: 100% !important;
            clear: both;
            /* margin-top: 6px!important;
            margin-bottom: 6px !important; */
            max-width: none !important;
            border-collapse: separate !important;
            border-spacing: 0;
            color: #6e6b7b;
            font-size: 8pt;
        }
        tr{
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        td{
            border-top: 1px solid #ebe9f1;
            padding: 0.72rem 1.5rem;
            vertical-align: middle;
        }
        img{
            width: 200px;
        }
        .center{
            text-align: center;
        }
        * {
            font-family: Arial, Helvetica, sans-serif
        }
        .borderless{
            border: none;
        }
        .borderless th, .borderless td{
            border: none;
            padding: 4px 2px;
        }
    </style>
</head>
<body>
    <div class="center">
        <img src="{{ public_path('assets/app-assets/images/logo/logo-text.png') }}" alt=""></span>
        <h4 style="margin-top: 4px;margin-bottom:0px;">LAPORAN DETAIL PRESENSI HARIAN</h4>
        <h5 style="margin-top: 0px">{{ \Carbon\Carbon::create()->day(1)->month($bulan)->locale('id_ID')->monthName }}  {{$tahun}}</h5>
    </div>
    <table class="borderless" style="font-size: 8pt; padding: 0px">
        <tr>
            <td style="padding: 0px;vertical-align:middle">
                <table class="borderless" style="font-size: 8pt;">
                    <tr>
                        <td colspan="3" align="left"><strong>Informasi Pegawai</strong></td>
                    </tr>
                    <tr>
                        <td style="width: 40%">NIP</td>
                        <td style="width: 10%">:</td>
                        <td style="width: 59%">{{$pegawai->nip}}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Nama Pegawai</td>
                        <td style="width: 1%">:</td>
                        <td style="width: 59%">{{$pegawai->nama}}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Jabatan</td>
                        <td style="width: 1%">:</td>
                        <td style="width: 59%">{{$pegawai->namajabatan}}</td>
                    </tr>
                </table>
            </td>
            <td style="width:10%"></td>
            <td style="padding: 0px;vertical-align:middle">
                <table class="borderless" style="font-size: 8pt;">
                    <tr>
                        <td colspan="3" align="left"><strong>Summary Presensi</strong></td>
                    </tr>
                    @foreach ($statistic as $item)
                        <tr>
                            <td style="width: 40%">{{ $item->nama_sistem_kerja }}</td>
                            <td style="width: 1%">:</td>
                            <td style="width: 59%">{{ $item->count }} Hari</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 0px;vertical-align:middle">
                <table class="borderless" style="font-size: 8pt;">
                    <tr>
                        <td colspan="3" align="left"><strong>Summary Tunjangan Kinerja</strong></td>
                    </tr>
                    @php
                        $tukin = $rekap_tukin->summary->tukin;
                        $potongan_persen = ($rekap_tukin->summary->potongan_harian+$rekap_tukin->summary->potongan_lainnya)*-1;
                        $potongan = $potongan_persen/100*$tukin;
                        $tunjangan_kotor = $tukin-$potongan;
                        $pajak_persen = 0;
                        $tunjangan_pajak = ($pajak_persen/100*$tunjangan_kotor);
                        $bruto = $tunjangan_kotor-$tunjangan_pajak;
                        $tunjangan_bersih = $bruto+$tunjangan_pajak;
                    @endphp
                    <tr>
                        <td style="width: 40%">Besar Tunjangan</td>
                        <td style="width: 10%">:</td>
                        <td style="width: 59%">Rp{{number_format($rekap_tukin->summary->tukin)}}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Potongan</td>
                        <td style="width: 10%">:</td>
                        <td style="width: 59%">({{ $potongan_persen }}%) Rp{{number_format($potongan)}}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Tunjangan Kotor</td>
                        <td style="width: 10%">:</td>
                        <td style="width: 59%">Rp{{number_format($tunjangan_kotor)}}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Pajak</td>
                        <td style="width: 10%">:</td>
                        <td style="width: 59%">0%</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Jumlah Bruto</td>
                        <td style="width: 10%">:</td>
                        <td style="width: 59%">Rp{{number_format($bruto)}}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Tunjangan Pajak</td>
                        <td style="width: 10%">:</td>
                        <td style="width: 59%">Rp{{number_format($tunjangan_pajak)}}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Tunjangan Bersih</td>
                        <td style="width: 10%">:</td>
                        <td style="width: 59%">Rp{{number_format($tunjangan_bersih)}}</td>
                    </tr>
                </table>
            </td>
            <td style="width:10%"></td>
            <td style="padding: 0px;vertical-align:middle">
                <table class="borderless" style="font-size: 8pt;">
                    <tr>
                        <td colspan="3" align="left"><strong>Summary Jam Kerja</strong></td>
                    </tr>

                    <tr>
                        <td style="width: 40%">Kewajiban Jam Kerja</td>
                        <td style="width: 1%">:</td>
                        <td style="width: 59%">{{ number_format($jam_kerja['kewajiban'],1,',','.') }} ({{ number_format(($jam_kerja['kewajiban']/7.5),1,',','.') }} Hari) </td>
                    </tr>
                    <tr>
                        <td style="width: 40%">Kekurangan Jam Kerja</td>
                        <td style="width: 1%">:</td>
                        <td style="width: 59%">{{ number_format($jam_kerja['kekurangan'],1,',','.') }} ({{ number_format(($jam_kerja['kekurangan']/7.5),1,',','.') }} Hari)</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="margin-top: 1rem">
        <thead>
            <tr>
                <th>No.</th>
                <th>Hari, Tanggal  </th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
                <th>Potongan</th>
                <th>Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            @if (count($rekap) ==0 )
                <tr>
                    <td colspan="7" class="center">Tidak ada data.</td>
                </tr>
            @endif
            @foreach ($rekap as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->locale('id_ID')->dayName }}, {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')}}</td>
                    <td>{{ $item->jam_masuk}}</td>
                    <td>{{ $item->jam_keluar}}</td>
                    <td>{{ $item->sistem_kerja->nama_sistem_kerja}} {{ !empty($item->presensi_cuti) ? " - ".@$item->presensi_cuti->cuti->nama_cuti : ""}}</td>
                    <td>
                        <table class="borderless">
                            @foreach ($item->potongan_tukin as $p)
                                <tr>
                                    <td>{{ round($p->jumlah_potongan,2) }} | {{ $p->keterangan }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="borderless">
                            @foreach ($item->jurnal as $j)
                                <tr>
                                    <td>{{\Carbon\Carbon::parse($j->createdAt)->format('H:i')}} - {{ $j->judul }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
