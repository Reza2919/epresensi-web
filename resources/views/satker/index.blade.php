@extends('layouts.app')
@section('title','Satker - Presensi KEMNAKER')
@push('css')
<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/vendors.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
<style>
    .select2-container{
        width: 100% !important;
        padding: 0;
    }
</style>
@endpush
@section('content')
@php
    $user = session('userdata');
    $zona = ['wib'=> 'Waktu Indonesia Barat','wita'=>'Waktu Indonesia Tengah','wit'=>'Waktu Indonesia Timur'];
@endphp
<!-- Dashboard Analytics Start -->
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Satker</h4>
                </div>
                <div class="card-body">
                    <div class="card-datatable table-responsive p-2">
                        <table class="satker-list-table table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Satker ID</th>
                                    <th>Nama Satker</th>
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

<div class="modal fade text-left modal-primary" id="satker-modal" role="dialog" aria-labelledby="satker-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="satker-modal-label">Satker User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <select id="id_pegawai" class="form-control" name="id_pegawai">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <select id="role" class="form-control" name="role">
                                <option value="pimpinan">Pimpinan</option>
                                <option value="tu">Tata Usaha</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success btn-sm" id="btn-assign">Tambah</button>
                    </div>
                    <div class="col-md-12">
                        <h5>Data User</h5>
                        <table class="detail-list-table table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th width="40%">Nama</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="body-user"></tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <h5>Data Koordinator</h5>
                        <table class="detail-list-table table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th width="40%">Nama</th>
                                    <th>Bidang</th>
                                </tr>
                            </thead>
                            <tbody id="body-koordinator"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left modal-primary" id="setting-modal" tabindex="-1" role="dialog" aria-labelledby="setting-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setting-modal-label">Set Lokasi</h5>
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
                    <div class="col-md-10">
                        <div class="form-group">
                          <label for="keterangan"></label>
                          <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success btn-sm mt-2" id="btn-add-koordinator">Tambah</button>
                    </div>
                    <div class="col-md-12">
                        <h5>Data Koordinat</h5>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard Analytics end -->
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
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<script>
    let SATKERID = '';
    $(document).ready(function () {
        let table = $('.satker-list-table').DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            ajax: {
                url: '{{ url("api/get-satker/datatable") }}',
                type: 'GET',
            },
            drawCallback: function( settings ) {
                feather.replace()
            }
        }) 
        $('#id_pegawai').select2({
            placeholder: '- Pilih Pegawai -',
            ajax: {
                url: '{{ url('api/get-pegawai/select2') }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        satkerid : SATKERID,
                        valueWithText : 1,
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        });
        $(document).on('click','.btn-detail',function(){
            let id = $(this).data('id');
            $('#satker-modal').modal('show');
            SATKERID = id;
            loadUser(id)
            $.ajax({
                type: "get",
                url: "{{ url('api/get-koordinator-by-satkerid') }}/"+id,
                dataType: "json",
                success: function (response) {
                    let rows = '';
                    if(response != []){
                        let no = 1;
                        $.each(response.rows, function (i, v) { 
                            rows += "<tr><td>"+no+"</td><td>"+v.nama_pegawai+"</td><td>"+v.bidang.nama_bidang+"</td><td></tr>";
                            no++;
                        });
                        $('#body-koordinator').html(rows);
                    }
                },
                drawCallback: function( settings ) {
                    feather.replace()
                }
            });
        })
        $(document).on('click','.btn-koordinat',function(){
            let id = $(this).data('id');
            let satker = $(this).data('satker');
            $('#ksatker').html(satker);
            $('#satker-koordinat-modal').modal('show');
            SATKERID = id;
            loadKoordinat(id)
        })

        $('#btn-assign').on('click',function(){
            let pegawai = $('#id_pegawai').val().split('|');
            let id_satker = SATKERID;
            let nama = $('#nama').val();
            let role = $('#role').val();
            $.ajax({
                type: "post",
                url: "{{ url('satker-user') }}",
                data: {
                    id_pegawai : pegawai[0],
                    id_satker : id_satker,
                    nama : pegawai[1],
                    role : role,
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
                        loadUser(SATKERID)
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
        $('#btn-add-koordinator').on('click',function(){
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
        $(document).on('click','.btn-setting',function(){
            let satkerid = $(this).data('id')
            let satker = $(this).data('satker')
            $('#setting-modal-label').html(satker)
            $('#nama_satker').val(satker)
            $('#id_satker').val(satkerid)
            getSatkerSetting(satkerid)
            $('#setting-modal').modal('show')
        })
        $('#save').on('click',function(){
            let lat = $('#lat').val();
            let long = $('#long').val();
            let nama_satker = $('#nama_satker').val();
            let id_satker = $('#id_satker').val();
            let zona_waktu = $('#zona_waktu').val();
            let id_satker_setting = $('#id_satker_setting').val();
            $.ajax({
                type: "post",
                url: "{{ url('satker-setting') }}",
                data: {
                    lat : lat,
                    long : long,
                    id_satker : id_satker,
                    nama_satker : nama_satker,
                    id_satker_setting : id_satker_setting,
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
                        loadUser(SATKERID)
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
    function loadUser(id){
        $.ajax({
            type: "get",
            url: "{{ url('api/get-satker-user-by-satkerid') }}/"+id,
            dataType: "json",
            success: function (response) {
                let rows = '';
                if(response != []){
                    let no = 1;
                    $.each(response.rows, function (i, v) { 
                        rows += "<tr><td>"+no+"</td><td>"+v.nama+"</td><td>"+v.role.toUpperCase()+"</td><td><a href='{{ url('satker-user') }}/"+v.id_satker_user+"/delete' class='btn btn-danger btn-sm btn-delete' title='Hapus'><i data-feather='trash'></a></td>/tr>";
                        no++;
                    });
                    $('#body-user').html(rows);

                    feather.replace()
                }
            },
        });
    }
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
    function getSatkerSetting(id_satker){
        $.ajax({
            type: "get",
            url: "{{ url('api/get-satker-setting') }}/"+id_satker,
            dataType: "json",
            success: function (response) {
                let rows = '';
                $('#lat').val(response.data.lat)
                $('#long').val(response.data.long)
                $('#zona_waktu').val(response.data.zona_waktu)
                $('#id_satker_setting').val(response.data.id_satker_setting)
            }
        });
    }
</script>
@include('layouts.alerts')
@endpush