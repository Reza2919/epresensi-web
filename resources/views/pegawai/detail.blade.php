@extends('layouts.app')
@section('title','Detail Presensi - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Detail Pegawai</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ url('/pegawai') }}">Data Pegawai</a>
                </li>
                <li class="breadcrumb-item active">Detail Pegawai
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
    $user = session('userdata')
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
                  <h6 class="text-muted">{{ $pegawai->jabatan }}</h6>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 class="class-title text-white">Data Presensi</h4>
                            <form action="{{ url('rekap-presensi/'.$pegawai->id) }}" method="post" target="_blank">
                                <div class="row">
                                    <div class="col-md-5 col-xs-12">
                                        <div class="form-group">
                                            <select id="p_bulan" class="form-control" name="bulan">
                                                <option value="nama">Pilih Bulan</option>
                                                <option {{ \Carbon\Carbon::now()->month == 1 ? 'selected' : '' }}  value="1">Januari</option>
                                                <option {{ \Carbon\Carbon::now()->month == 2 ? 'selected' : '' }}  value="2">Februari</option>
                                                <option {{ \Carbon\Carbon::now()->month == 3 ? 'selected' : '' }}  value="3">Maret</option>
                                                <option {{ \Carbon\Carbon::now()->month == 4 ? 'selected' : '' }}  value="4">April</option>
                                                <option {{ \Carbon\Carbon::now()->month == 5 ? 'selected' : '' }}  value="5">Mei</option>
                                                <option {{ \Carbon\Carbon::now()->month == 6 ? 'selected' : '' }}  value="6">Juni</option>
                                                <option {{ \Carbon\Carbon::now()->month == 7 ? 'selected' : '' }}  value="7">Juli</option>
                                                <option {{ \Carbon\Carbon::now()->month == 8 ? 'selected' : '' }}  value="8">Agustus</option>
                                                <option {{ \Carbon\Carbon::now()->month == 9 ? 'selected' : '' }}  value="9">September</option>
                                                <option {{ \Carbon\Carbon::now()->month == 10 ? 'selected' : '' }}  value="10">Oktober</option>
                                                <option {{ \Carbon\Carbon::now()->month == 11 ? 'selected' : '' }}  value="11">November</option>
                                                <option {{ \Carbon\Carbon::now()->month == 12 ? 'selected' : '' }}  value="12">Desember</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <select id="p_tahun" class="form-control" name="tahun">
                                                <option value="nama">Pilih Tahun</option>
                                                @for ($i = (int) \Carbon\Carbon::now()->year - 3; $i <= (int) \Carbon\Carbon::now()->year; $i++)
                                                    <option value="{{$i}}" {{(int) \Carbon\Carbon::now()->year == $i ? 'selected' : ''}}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-info text-white">
                                            <i data-feather="printer"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="card-datatable table-responsive p-2">
                                <table class="presensi-list-table table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Keluar</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card earnings-card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title mb-1">Tunjangan Kinerja</h5>
                      <div class="font-small-5 t_bulan">{{ \Carbon\Carbon::now()->format('M Y') }}</div>
                      <h3 class="mb-1" id="t_tukin">0</h3>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card earnings-card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title mb-1">Potongan Tukin</h5>
                      <div class="font-small-5 t_bulan">{{ \Carbon\Carbon::now()->format('M Y') }}</div>
                      <h3 class="mb-1" id="t_potongan">0</h3>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card earnings-card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title mb-1">Total Tukin</h5>
                      <div class="font-small-5 t_bulan">{{ \Carbon\Carbon::now()->format('M Y') }}</div>
                      <h3 class="mb-1" id="t_tunjangan">0</h3>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="class-title text-white">Data Potongan Tunjangan Kinerja</h4>
                    <form action="{{ url('rekap-presensi/'.$pegawai->id) }}" method="post" target="_blank">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <select id="bulan" class="form-control" name="bulan">
                                        <option value="nama">Pilih Bulan</option>
                                        <option {{ \Carbon\Carbon::now()->month == 1 ? 'selected' : '' }}  value="1">Januari</option>
                                        <option {{ \Carbon\Carbon::now()->month == 2 ? 'selected' : '' }}  value="2">Februari</option>
                                        <option {{ \Carbon\Carbon::now()->month == 3 ? 'selected' : '' }}  value="3">Maret</option>
                                        <option {{ \Carbon\Carbon::now()->month == 4 ? 'selected' : '' }}  value="4">April</option>
                                        <option {{ \Carbon\Carbon::now()->month == 5 ? 'selected' : '' }}  value="5">Mei</option>
                                        <option {{ \Carbon\Carbon::now()->month == 6 ? 'selected' : '' }}  value="6">Juni</option>
                                        <option {{ \Carbon\Carbon::now()->month == 7 ? 'selected' : '' }}  value="7">Juli</option>
                                        <option {{ \Carbon\Carbon::now()->month == 8 ? 'selected' : '' }}  value="8">Agustus</option>
                                        <option {{ \Carbon\Carbon::now()->month == 9 ? 'selected' : '' }}  value="9">September</option>
                                        <option {{ \Carbon\Carbon::now()->month == 10 ? 'selected' : '' }}  value="10">Oktober</option>
                                        <option {{ \Carbon\Carbon::now()->month == 11 ? 'selected' : '' }}  value="11">November</option>
                                        <option {{ \Carbon\Carbon::now()->month == 12 ? 'selected' : '' }}  value="12">Desember</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <select id="tahun" class="form-control" name="tahun">
                                        <option value="nama">Pilih Tahun</option>
                                        @for ($i = (int) \Carbon\Carbon::now()->year - 3; $i <= (int) \Carbon\Carbon::now()->year; $i++)
                                            <option value="{{$i}}" {{(int) \Carbon\Carbon::now()->year == $i ? 'selected' : ''}}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-md-3">
                                <button type="submit" class="btn btn-info text-white">
                                    <i data-feather="printer"></i>
                                </button>
                            </div> --}}
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="card-datatable table-responsive p-2">
                        @if($user->role == 'admin' || $user->role == 'tu')
                            <button type="button" class="btn btn-primary btn-sm float-right" id="btn-add-potongan"><i data-feather="plus"></i>Tambah Potongan</button>
                        @endif
                        <table class="potongan-list-table table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Potongan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<div class="modal fade text-left modal-primary" id="potongan-modal" role="dialog" aria-labelledby="potongan-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="potongan-modal-label">Data Potongan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="id_potongan_tukin" value="" id="id_potongan_tukin">
                        <div class="form-group">
                          <label for="jumlah_potongan">Tanggal</label>
                          <input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Tanggal">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="id_potongan_tukin" value="" id="id_potongan_tukin">
                        <div class="form-group">
                          <label for="jumlah_potongan">Jumlah Potongan (%)</label>
                          <input type="number" step="0.01" name="jumlah_potongan" id="jumlah_potongan" class="form-control" placeholder="Contoh: -0.5">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                          <label for="keterangan">Keterangan</label>
                          <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                <button class="btn btn-success" id="btn-save-potongan">Simpan</button>
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

    $('#btn-add-potongan').on('click', function () {
        $('#id_potongan_tukin').val('');
        $('#tanggal').val('');
        $('#keterangan').val('');
        $('#jumlah_potongan').val('');
        $('#potongan-modal').modal('show');
    });

    $(document).on('click','.btn-edit',function(){
        let jml = $(this).data('jumlah_potongan').toFixed(2);
        let keterangan = $(this).data('keterangan');
        let tanggal = $(this).data('tanggal');
        let id = $(this).data('id');

        $('#id_potongan_tukin').val(id);
        $('#keterangan').val(keterangan);
        $('#tanggal').val(tanggal);
        $('#jumlah_potongan').val(jml);

        $('#potongan-modal').modal('show');
    });

    $('#btn-save-potongan').on('click', function () {

        $('#potongan-modal').modal('hide');

        let id_potongan_tukin = $('#id_potongan_tukin').val();
        let keterangan = $('#keterangan').val();
        let jumlah_potongan = $('#jumlah_potongan').val();
        let tanggal = $('#tanggal').val();
        let id_pegawai = '{{ $id_pegawai }}';

        $.ajax({
            type: "post",
            url: '{{ url("potongan-tukin") }}',
            data: {
                id_potongan_tukin : id_potongan_tukin,
                id_pegawai : id_pegawai,
                tanggal : tanggal,
                jumlah_potongan : jumlah_potongan,
                keterangan : keterangan,
                _token : '{{ csrf_token() }}',
            },
            dataType: "json",
            success: function (response) {

                if(response.success){

                    toastr.success(response.message);

                    $('#id_potongan_tukin').val('');
                    $('#keterangan').val('');
                    $('#jumlah_potongan').val('');
                    $('#tanggal').val('');

                }else{

                    toastr.error(response.message);

                }
            }
        });
    });

    getSummary();

$('.presensi-list-table').DataTable({
    processing: true,
    serverSide: false,
    ajax: '{{ url("api/get-presensi-pegawai/datatable/".$id_pegawai) }}',
    columns: [
        { data: 0 },
        { data: 1 },
        { data: 2 },
        { data: 3 },
        { data: 4 },
        { data: 5 }
    ]
});

$('.potongan-list-table').DataTable({
    processing: true,
    serverSide: false,
    ajax: '{{ url("api/get-potongan-tukin/datatable/".$id_pegawai) }}',
    columns: [
        { data: 0 },
        { data: 1 },
        { data: 2 },
        { data: 3 },
        { data: 4 }
    ]
});

});
    function getSummary(){
        $.ajax({
            type: "post",
            url: "{{ url('api/get-rekap-tukin') }}",
            data:{
                'id_pegawai': '{{ @$id_pegawai }}',
                'bulan': $('#bulan').val(),
                'tahun': $('#tahun').val(),
                '_token': '{{ csrf_token() }}'
            },
            dataType: "json",
            success: function (response) {
                if(response.summary){
                    let total_potongan = (response.summary.potongan_harian+response.summary.potongan_lainnya);
                    console.log(total_potongan);
                    $('#t_tunjangan').html(numberWithCommas(response.summary.tukin - (-1*total_potongan/100 * response.summary.tukin).toFixed(2)))
                    $('#t_potongan').html(numberWithCommas(total_potongan.toFixed(2)) +'% ('+numberWithCommas((-1*total_potongan/100 * response.summary.tukin).toFixed(2))+')' )
                    $('#t_tukin').html(numberWithCommas(response.summary.tukin.toFixed(2)))
                    console.log($('#bulan:selected').text())
                    $('.t_bulan').html($('#bulan option:selected').text()+" "+$('#tahun').val())
                }
            }
        });
    }
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>

@include('layouts.alerts')
@endpush
