<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Tunjangan Kinerja Pegawai</title>
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
            font-size: 10pt;
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
            padding: 4px 2;
        }
    </style>
</head>
<body>
    <div class="center">
        <img src="{{ public_path('assets/app-assets/images/logo/logo-text.png') }}" alt=""></span>
        <h4 style="margin-top: 4px;">{{ $pegawai->satker }}</h4>
    </div>
    <table class="borderless" style="font-size: 10pt;">
        <tr>
            <td>Potongan Tunjangan Kinerja Bulan</td>
            <td style="width: 10%">:</td>
            <td>{{\Carbon\Carbon::create()->day(1)->month($bulan)->locale('id_ID')->monthName}} {{$tahun}}</td>
        </tr>
        <tr>
            <td>Nama Pegawai</td>
            <td style="width: 10%">:</td>
            <td>{{$pegawai->nama}}</td>
        </tr>
    </table>
    <table style="margin-top: 1rem">
        <thead>
            <tr>
                <th>No.</th>
                <th>Hari, Tanggal  </th>
                <th>Potongan</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @if (count($presensi) ==0 )
                <tr>
                    <td colspan="7" class="center">Tidak ada data.</td>
                </tr>
            @endif
            @php
                $tukin = $rekap_tukin->summary->tukin;
                $total_potongan = 0;
            @endphp
            @foreach ($presensi as $item)
                @php
                    $potongan = $item->sum_potongan/100 * $rekap_tukin->summary->tukin;
                    $tukin = $tukin +  ($potongan);
                    $total_potongan += $potongan;
                @endphp
                <tbody>
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->locale('id_ID')->dayName }}, {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>
                            <table class="borderless">
                                @foreach ($item->potongan_tukin as $p)
                                    <tr>
                                        <td>{{ round($p->jumlah_potongan,2) }} | {{ $p->keterangan }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                        <td align="right">
                            Rp{{ number_format($potongan,2,',','.') }}
                        </td>
                    </tr>
                </tbody>
            @endforeach
        </tbody>
    </table>
    <table class="borderless">
        <tr>
            <td></td>
            <td></td>
            <td>Tunjangan Kinerja</td>
            <td width="10%">:</td>
            <td align="right">Rp{{ number_format($rekap_tukin->summary->tukin,2,',','.') }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Potongan Tunjangan Kinerja</td>
            <td width="10%">:</td>
            <td align="right">Rp{{ number_format($total_potongan,2,',','.') }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Total Tunjangan Kinerja</td>
            <td width="10%">:</td>
            <td align="right">Rp{{ number_format($tukin,2,',','.') }}</td>
        </tr>
    </table>
</body>
</html>