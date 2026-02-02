@extends('layouts.app')
@section('title','Detail Presensi - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Detail Presensi</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ url('/pegawai') }}">Data Pegawai</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ url('/pegawai/'.$pegawai->pegawaiid) }}">Data Presensi</a>
                </li>
                <li class="breadcrumb-item active">Detail Presensi
                </li>
            </ol>
        </div>
    </div>
</div>
@endsection
@push('css')
<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/vendors.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@section('content')
@php
    $user = session('userdata');
@endphp
<!-- Dashboard Analytics Start -->
<section>
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-profile">
                <img src="{{ asset('assets') }}/app-assets/images/banner/banner-12.jpg" class="img-fluid card-img-top" alt="Profile Cover Photo">
                <div class="card-body">
                  <div class="profile-image-wrapper">
                    <div class="profile-image">
                      <div class="avatar">
                        <img src="{{ @$pegawai->foto ?? asset('assets/app-assets/images/profile/default-user.jpg') }}" alt="Profile Picture">
                      </div>
                    </div>
                  </div>
                  <h3>{{ $pegawai->nama }}</h3>
                  <h6 class="text-muted">{{ @$pegawai->namajabatan }}</h6>
                  <hr class="mb-2">
                  <div class="text-left">
                    <div class="mt-2">
                        <h5 class="mb-75">Unit Kerja:</h5>
                        <p class="card-text">{{ @$pegawai->satker }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Eselon:</h5>
                            <p class="card-text">{{ @$pegawai->eselon }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Golongan:</h5>
                            <p class="card-text">{{ @$pegawai->gol }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">NIK:</h5>
                            <p class="card-text">{{ @$pegawai->nik }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">NIP:</h5>
                            <p class="card-text">{{ @$pegawai->nip }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Tempat, Tanggal Lahir:</h5>
                            <p class="card-text">{{ @$pegawai->tempatlahir }}, {{@$pegawai->tgllahir}}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Alamat:</h5>
                            <p class="card-text">{{ @$pegawai->alamat }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Grade:</h5>
                            <p class="card-text">{!! @$pegawai->grade ? @$pegawai->grade : '<span class="badge badge-warning">Grade belum diisi.</span>' !!}</p>
                        </div>
                  </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($user->role == 'tu' || $user->role == 'admin')
                            <a href="{{ url('presensi/edit/'.$presensi->id_presensi) }}" class="btn btn-sm btn-primary float-right ml-1"><i data-feather='edit'></i> Edit</a>
                        @endif
                        <h4 class="card-title">Detail Presensi <i class="badge badge-info float-right">{{ @$presensi->sistem_kerja->nama_sistem_kerja }} {{ @$presensi->sistem_kerja->is_cuti == 1 ? ' : '.@$presensi->presensi_cuti->cuti->nama_cuti : '' }}</i></h4>
                        <h6 class="card-subtitle text-muted">{{ $hari.', '.\Carbon\Carbon::parse($presensi->tanggal)->format('d M Y')}} </h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Hari:</h5>
                                    <p class="card-text">{{ @$hari }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Tanggal:</h5>
                                    <p class="card-text">{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Jam Masuk:</h5>
                                    <p class="card-text">{{ @$presensi->jam_masuk }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Jam Keluar:</h5>
                                    <p class="card-text">{{ @$presensi->jam_keluar }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Lokasi Masuk:</h5>
                                    <p class="card-text">{{ @$presensi->lokasi_masuk }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Lokasi Keluar:</h5>
                                    <p class="card-text">{{ @$presensi->lokasi_keluar }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Foto Masuk:</h5>
                                    <img src="{{ env('API_URL').'/'.$presensi->foto_masuk }}" alt="" style="width: 200px">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Foto Keluar:</h5>
                                    <img src="{{ env('API_URL').'/'.$presensi->foto_keluar }}" alt="" style="width: 200px">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{-- @if ($user->role == 'tu' && $presensi->sistem_kerja->nama_sistem_kerja != 'Tidak Presensi' && $presensi->sistem_kerja->nama_sistem_kerja == 'Tidak Hadir')
                                <a href="{{ route('presensi.set-tidak-presensi',[$presensi->id_presensi]) }}" type="button" class="btn btn-warning float-right" id="btn-set">Set Tidak Presensi</a>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Log Perubahan Presensi</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Sistem Kerja</th>
                                    <th>User</th>
                                    <th>Potongan Awal</th>
                                    <th>Potongan Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(@$presensi->presensi_change)
                                    @foreach($presensi->presensi_change as $key => $value)
                                        <tr>
                                            <td scope="row">{{ \Carbon\Carbon::parse($value->createdAt)->format('d-m-Y H:i:s') }}</td>
                                            <td>{{$value->presensi_change_from->nama_sistem_kerja}} -> {{ $value->presensi_change_to->nama_sistem_kerja }}</td>
                                            <td>{{ $value->user }}</td>
                                            <td>{{ number_format($value->potongan_awal) }}</td>
                                            <td>{{ number_format($value->potongan_akhir) }}</td>
                                        </tr> 
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Potongan</h4>    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$presensi->potongan_tukin as $p)
                            <tr>
                                <td>{{ $p->keterangan}}</td>
                                <td>{{ round($p->jumlah_potongan,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total Potongan</th>
                                <th>{{ round($presensi->sum_potongan,2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Jurnal</h4>
                <ul class="timeline">
                    @if (@$presensi->jurnal)
                        @foreach ($presensi->jurnal as $item)
                            <li class="timeline-item">
                            <span class="timeline-point timeline-point-indicator"></span>
                            <div class="timeline-event">
                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                <h6>{{ $item->judul }}</h6>
                                <span class="timeline-event-time">{{ \Carbon\Carbon::parse($item->createdAt)->format('H:i') }}</span>
                                </div>
                                <p>{{ $item->keterangan }}</p>
                            </div>
                            </li>
                        @endforeach
                    @endif
                  </ul>
              </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade text-left modal-primary" id="add-pegawai-modal" tabindex="-1" role="dialog" aria-labelledby="add-pegawai-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-pegawai-modal-label">Tambahkan Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_pegawai" id="id_pegawai">
                <input type="hidden" name="nama" id="nama">
                <input type="hidden" name="id_satker" id="id_satker">
                <div class="form-group">
                    <label for="pegawai">Pilih Pegawai</label>
                    <select id="pegawai" class="form-control" name="pegawai" multiple>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="save-pegawai">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<script>
    $(document).ready(function () {
        $('#btn-set').on('click', function(e){
            e.preventDefault()
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Sistem Kerja akan diubah menjadi Tidak Presensi!!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya yakin!',
                cancelButtonText: 'Batalkan!'
            }).then((result) => {
            if (result.value) {
                url = $(this).attr('href')
                $.ajax({
                    url: url,
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                }).then(function(res){
                    if(res.error){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan pada server!'
                        })
                    } else {
                        table.ajax.reload()
                        Swal.fire(
                            'Success!',
                            'Data berhasil diubah.',
                            'success'
                        )
                    }
                })
            }
            })
        })
    });
</script>

@include('layouts.alerts')
@endpush