@extends('layouts.app')
@section('title','Pegawai - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Data Pegawai</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Data Pegawai
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
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Filter</h4>
                    
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li>
                                <a data-action="collapse"><i data-feather="chevron-up"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-xs-12">
                                <div class="form-group">
                                    <label for="satkerid">Satker</label>
                                    <select id="satkerid" class="form-control" name="satkerid" {{ !@$user->satkerid || in_array(@$user->satkerid,['010103','010201','0102','010301']) ? '' : 'disabled' }}>
                                        <option value="">Pilih Satker</option>
                                        @if (@$user->role != 'admin')
                                        <option value="{{ $user->satkerid }}" selected="selected">{{ $user->satker }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                <div class="form-group">
                                    <label for="statuspegawai">Status Pegawai</label>
                                    <select id="statuspegawai" class="form-control" name="statuspegawai">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                <div class="form-group">
                                    <label for="search_by">Cari Berdasarkan</label>
                                    <select id="search_by" class="form-control" name="search_by">
                                        <option value="nama">Nama Pegawai</option>
                                        <option value="nip">NIP</option>
                                        <option value="nik">NIK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-4">
                                <div class="form-group">
                                    <label for="satkerid">Cari</label>
                                    <input type="text" class="form-control" name="search" id="search" placeholder="Cari">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary float-right btn-search" type="button">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Pegawai</h4>
                </div>
                <div class="card-body">
                    <div class="card-datatable table-responsive p-2">
                        <table class="user-list-table table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th width="40%">Nama</th>
                                    <th>NIP</th>
                                    <th>Jabatan</th>
                                    <th>Satker</th>
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
</section>
<div class="modal fade text-left modal-primary" id="assign-modal" tabindex="-1" role="dialog" aria-labelledby="assign-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assign-modal-label">Tambahkan Akses Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_pegawai" id="id_pegawai">
                <input type="hidden" name="nama" id="nama">
                <input type="hidden" name="id_satker" id="id_satker">
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" class="form-control" name="role">
                        <option>Pilih Role</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="tu">Tata Usaha</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="save-assign">Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left modal-primary" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="detail-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail-modal-label">Detail Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-start align-items-center">
                    <!-- avatar -->
                    <div class="avatar mr-1">
                        <img id="foto" src="{{ asset('assets/app-assets/images/profile/default-user.jpg') }}" alt="avatar img" height="50" width="50">
                    </div>
                    <!--/ avatar -->
                    <div class="profile-user-info">
                        <h6 class="mb-0" id="nama_pegawai"></h6>
                        <small class="text-muted" id="namajabatan"></small>
                    </div>
                </div>
                <div class="mt-2">
                    <h5 class="mb-75">Satker:</h5>
                    <p class="card-text" id="satker"></p>
                </div>
                <div class="mt-2">
                    <h5 class="mb-75">Tempat, Tanggal Lahir:</h5>
                    <p class="card-text" id="ttl"></p>
                </div>
                <div class="mt-2">
                    <h5 class="mb-75">Alamat:</h5>
                    <p class="card-text" id="alamat"></p>
                </div>
                <div class="mt-2">
                    <h5 class="mb-75">Eselon:</h5>
                    <p class="card-text" id="eselon"></p>
                </div>
                <div class="mt-2">
                    <h5 class="mb-50">Golongan:</h5>
                    <p class="card-text mb-0" id="gol"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
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

        let allowAll = ['010103','010201','0102','010201','010301','010401','010501','010601','010701']
        let role = '{{@$user->role}}'
        let satkerid = '{{@$user->satkerid}}'
        
        if(allowAll.includes(satkerid) || role=='admin' ){
            $('#satkerid').select2({
                placeholder: '- Pilih Satker -',
                ajax: {
                    url: '{{ url('api/get-satker/select2') }}',
                    dataType: 'json',
                    processResults: function (data) {
                        let s = '{{@$user->satkerid}}'
                        let satkerData = [
                            {satker:'010201',parent:'0102'},
                            {satker:'010301',parent:'0103'},
                            {satker:'010401',parent:'0104'},
                            {satker:'010501',parent:'0105'},
                            {satker:'010601',parent:'0106'},
                            {satker:'010701',parent:'0107'}
                        ]
                        $.each(satkerData, function (idx, ss) { 
                            if(s == ss.satker){
                                let newData = []
                                $.each(data.data, function (i, v) { 
                                    if(v.id == ss.parent || v.id.substring(0,4) == ss.parent){
                                        newData.push(v)
                                    }
                                });
                                data.data = newData
                            }
                        });
                        return {
                            results: data.data
                        };
                    }
                }
            });
        }

        let table = $('.table').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("api/get-pegawai/datatable") }}',
                type: 'GET',
                data: function(e){
                    e.satkerid = $("#satkerid").val()
                    e.statuspegawai = $("#statuspegawai").val()
                    e.search_by = $("#search_by").val()
                    e.search = $("#search").val()
                }
            },
            drawCallback: function( settings ) {
                feather.replace()
            }
        }) 
        // $('#satkerid').select2({
        //     placeholder: '- Pilih Satker -',
        //     ajax: {
        //         url: '{{ url('api/get-satker/select2') }}',
        //         dataType: 'json',
        //         processResults: function (data) {
        //             return {
        //                 results: data.data
        //             };
        //         }
        //     }
        // });
        $('#statuspegawai').select2({
            placeholder: '- Pilih Status Pegawai -',
            ajax: {
                url: '{{ url('api/get-status-pegawai/select2') }}',
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        });
        $('.btn-search').on('click',function(){
            table.ajax.reload()
        });
        $(document).on('click','.btn-assign',function(){
            let id_pegawai = $(this).data('id_pegawai');
            let id_satker = $(this).data('id_satker');
            let nama = $(this).data('nama');
            $('#id_pegawai').val(id_pegawai);
            $('#id_satker').val(id_satker);
            $('#nama').val(nama);
            $('#assign-modal').modal('show')
        })
        $(document).on('click','.btn-detail',function(){
            let data = $(this).data('detail');
            $('#nama_pegawai').html(data.nama);
            $('#alamat').html(data.alamat);
            $('#satker').html(data.satker);
            $('#foto').attr('src',data.foto == null ? "{{ asset('assets/app-assets/images/profile/default-user.jpg') }}" : data.foto);
            $('#namajabatan').html(data.namajabatan);
            $('#gol').html(data.gol);
            $('#eselon').html(data.eselon);
            $('#ttl').html(data.tempatlahir+', '+data.tgllahir);
            $('#detail-modal').modal('show')
        })
        $('#save-assign').on('click',function(){
            let id_pegawai = $('#id_pegawai').val();
            let id_satker = $('#id_satker').val();
            let nama = $('#nama').val();
            let role = $('#role').val();
            $.ajax({
                type: "post",
                url: "{{ url('satker-user') }}",
                data: {
                    id_pegawai : id_pegawai,
                    id_satker : id_satker,
                    nama : nama,
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
    });
</script>
@endpush