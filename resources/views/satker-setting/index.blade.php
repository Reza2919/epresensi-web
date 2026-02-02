@extends('layouts.app')
@section('title','Detail Satker - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Satker Setting</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Satker Setting
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
    $zona = ['wib'=> 'Waktu Indonesia Barat','wita'=>'Waktu Indonesia Tengah','wit'=>'Waktu Indonesia Timur'];
@endphp
<!-- Dashboard Analytics Start -->
<section>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-75">Satker:</h5>
                    <p class="card-text">
                        {{ @$satker_setting->nama_satker ? $satker_setting->nama_satker : $user->satker }}
                    </p>
                    {{-- <div class="mt-2">
                        <h5 class="mb-75">Lat:</h5>
                        <p class="card-text" id="lat_text">{{ @$satker_setting->lat }}</p>
                    </div>
                    <div class="mt-2">
                        <h5 class="mb-75">Long:</h5>
                        <p class="card-text" id="long_text">{{ @$satker_setting->long }}</p>
                    </div> --}}
                    <div class="mt-2">
                        <h5 class="mb-75">Zona Waktu:</h5>
                        <p class="card-text" id="zona_waktu_text">{{ @$zona[$satker_setting->zona_waktu] }}</p>
                    </div>
                    <div class="mt-2 float-right">
                        <button class="btn btn-primary btn-sm btn-edit" type="button">Ubah</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="class-title text-white">Data Satker User</h4>
                </div>
                <div class="card-body">
                    <div class="card-datatable table-responsive p-2">
                        <table class="user-list-table table table-hover" id="pegawai-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th width="40%">Nama Pegawai</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="class-title text-white">Data Koordinat</h4>
                    <button type="button" class="btn btn-info btn-sm float-right btn-koordinat"><i data-feather="plus"></i> Tambah Koordinat</button>
                </div>
                <div class="card-body">
                    <div class="card-datatable table-responsive p-2">
                        <table class="detail-list-table table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th width="40%">Keterangan</th>
                                    <th>Lat</th>
                                    <th>Long</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="body-koordinat"></tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>
<div class="modal fade text-left modal-primary" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">Set Lokasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_satker_setting" id="id_satker_setting" value="{{ @$satker_setting->id_satker_setting }}">
                <input type="hidden" name="nama_satker" id="nama_satker" value="{{ @$user->satker }}">
                <input type="hidden" name="id_satker" id="id_satker" value="{{ @$user->satkerid }}">
                {{-- <div class="form-group">
                    <label for="lat">Latitude</label>
                    <input id="lat" class="form-control" type="text" name="lat" value="{{ @$satker_setting->lat }}">
                </div>
                <div class="form-group">
                    <label for="long">Longitude</label>
                    <input id="long" class="form-control" type="text" name="long" value="{{ @$satker_setting->long }}">
                </div> --}}
                <div class="form-group">
                    <label for="zona_waktu">Zona Waktu</label>
                    <select name="zona_waktu" id="zona_waktu" class="form-control">
                        @foreach ($zona as $k => $item)
                            <option value="{{ $k }}" {{ @$satker_setting->zona_waktu == $item ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="save">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left modal-primary" id="satker-koordinat-modal" role="dialog" aria-labelledby="satker-koordinat-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="satker-koordinat-modal-label">Data Koordinat - <span id="ksatker"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="lat"></label>
                          <input type="text" name="lat" id="lat" class="form-control" placeholder="Latitude">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="long"></label>
                          <input type="text" name="long" id="long" class="form-control" placeholder="Longitude">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                          <label for="keterangan"></label>
                          <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                <button class="btn btn-success" id="btn-add-koordinat">Tambah</button>
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

    let SATKERID = '{{ $user->satkerid }}'
    $(document).ready(function () {
        loadKoordinat(SATKERID)
        $(document).on('click','.btn-edit',function(){
            $('#edit-modal').modal('show')
        })
        $('#save').on('click',function(){
            let id_satker_setting = $('#id_satker_setting').val();
            // let lat = $('#lat').val();
            // let long = $('#long').val();
            let nama_satker = $('#nama_satker').val();
            let id_satker = $('#id_satker').val();
            let zona_waktu = $('#zona_waktu').val();
            let zona_waktu_text = $('#zona_waktu option:selected').text();
            $.ajax({
                type: "post",
                url: "{{ url('satker-setting') }}",
                data: {
                    id_satker_setting : id_satker_setting,
                    // lat : lat,
                    // long : long,
                    id_satker : id_satker,
                    nama_satker : nama_satker,
                    zona_waktu : zona_waktu,
                    _token : '{{ csrf_token() }}',
                },
                dataType: "json",
                success: function (response) {
                    if(response.success == true){
                        toastr['success'](
                            response.message,
                            'Success',
                            {
                                closeButton: true,
                                tapToDismiss: false,
                            }
                        );
                        $('#lat_text').html(response.data.lat);
                        $('#long_text').html(response.data.long);
                        $('#zona_waktu_text').html(zona_waktu_text);
                    }else{
                        toastr['error'](
                            response.message,
                            'Error',
                            {
                                closeButton: true,
                                tapToDismiss: false,
                            }
                        );
                    }
                }
            });
        });
        let table = $('#pegawai-table').DataTable({
            searching: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("api/get-satker-user/datatable") }}',
                type: 'GET',
            },
            drawCallback: function( settings ) {
                feather.replace()
            }
        }) 

        $(document).on('click', '.btn-delete', function(e){
            e.preventDefault()
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang akan dihapus!",
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
                    method: 'DELETE',
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
                            'Deleted!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                        
                    }
                })
            }
            })
        })

        $(document).on('click','.btn-koordinat',function(){
            let satker = '{{ $user->satker }}'
            $('#ksatker').html(satker);
            $('#satker-koordinat-modal').modal('show');
            loadKoordinat(SATKERID)
        })

        $('#btn-add-koordinat').on('click',function(){
            let id_satker = SATKERID;
            let keterangan = $('#keterangan').val();
            let lat = $('#lat').val();
            let long = $('#long').val();
            $.ajax({
                type: "post",
                url: "{{ route('satker-koordinat.store') }}",
                data: {
                    id_satker : id_satker,
                    keterangan : keterangan,
                    lat : lat,
                    long : long,
                    _token : '{{ csrf_token() }}',
                },
                dataType: "json",
                success: function (response) {
                    if(response.success == true){
                        toastr['success'](
                            response.message,
                            'Success',
                            {
                                closeButton: true,
                                tapToDismiss: false,
                            }
                        );
                        loadKoordinat(SATKERID)
                        table.ajax.reload()
                        
                    }else{
                        toastr['error'](
                            response.message,
                            'Error',
                            {
                                closeButton: true,
                                tapToDismiss: false,
                            }
                        );
                    }
                }
            });
        });

        $(document).on('click', '.btn-delete-koordinat', function(e){
            e.preventDefault()
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang akan dihapus!",
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
                    method: 'DELETE',
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
                        loadKoordinat(SATKERID)
                        Swal.fire(
                            'Deleted!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                        
                    }
                })
            }
            })
        })
    });
    function loadKoordinat(id){
        $.ajax({
            type: "get",
            url: "{{ url('api/get-koordinat-by-satkerid') }}/"+id,
            dataType: "json",
            success: function (response) {
                let rows = '';
                if(response != []){
                    let no = 1;
                    $.each(response.rows, function (i, v) { 
                        rows += "<tr><td>"+no+"</td><td>"+v.keterangan+"</td><td>"+v.lat+"</td><td>"+v.long+"</td><td><a href='{{ url('satker-koordinat') }}/"+v.id_satker_koordinat+"' class='btn btn-danger btn-sm btn-delete-koordinat' title='Hapus'><i data-feather='trash'></a></td>/tr>";
                        no++;
                    });
                    $('#body-koordinat').html(rows);

                    feather.replace()
                }
            },
        });
    }
</script>

@include('layouts.alerts')
@endpush