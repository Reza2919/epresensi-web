@extends('layouts.app')
@section('title','Laporan Presensi - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Report Presensi</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Cetak Laporan Presensi
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 class="class-title text-white">Cetak Laporan Presensi</h4>
                        </div>
                        <div class="card-body pt-2">
                            <form action="{{ url('laporan-presensi/print') }}" method="post" id="form">
                                @csrf
                                <input type="hidden" name="nama_satker" id="nama_satker">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <label for="bulan">Bulan</label>
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
                                        <label for="tahun">Tahun</label>
                                        <div class="form-group">
                                            <select id="tahun" class="form-control" name="tahun">
                                                <option value="nama">Pilih Tahun</option>
                                                @for ($i = (int) \Carbon\Carbon::now()->year - 3; $i <= (int) \Carbon\Carbon::now()->year; $i++)
                                                    <option value="{{$i}}" {{(int) \Carbon\Carbon::now()->year == $i ? 'selected' : ''}}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="satkerid">Satker</label>
                                            <select id="satkerid" class="form-control" name="satkerid" {{ !@$user->satkerid || in_array(@$user->satkerid,['010103','010201','0102','010201','010301','010401','010501','010601','010701']) ? '' : 'readonly' }}>
                                                <option value="">Pilih Satker</option>
                                                @if (@$user->role != 'admin')
                                                    <option value="{{ $user->satkerid }}" selected="selected">{{ $user->satker }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="type">Jenis Laporan</label>
                                          <select class="form-control" name="type" id="type">
                                            <option value="presensi_per_bulan">Rekap Presensi Per Bulan</option>
                                            <option value="tukin_per_bulan">Rekap Tukin Per Bulan</option>
                                            <option value="tukin_kelas_jabatan">Tunjangan Kinerja Per Kelas Jabatan</option>
                                            <option value="tukin_rekening">Tunjangan Kinerja & Rekening</option>
                                            <option value="presensi_detail_presensi_per_satker">Rekap Detail Presensi Per Satker</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-info text-white float-right" id="btn-submit">
                                            <i data-feather="printer"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12" id="list-table-generate">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="class-title text-white">History List Laporan Presensi</h4>
                                    </div>
                                    <div class="col-md-6" style="text-align: right">
                                        <div id="refresh-ajax">
                                            <i class="feather-16" data-feather="refresh-ccw" style="color: white; cursor: pointer"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <div class="card-datatable table-responsive">
                                <table class="generate-list-table table table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Satker</th>
                                        <th>Jenis Laporan</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                        <th>Cetak Laporan Tanggal</th>
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
@endsection
@push('js')
<!-- BEGIN: Page Vendor JS-->
<script>
    $(document).ready(function () {
        feather.replace()
        let dataTable = $('.generate-list-table').DataTable({
            serverSide: true,
            processing: true,
            orderMulti: true,
            stateSave: true,
            paging: true,
            searching: false,

            ajax: {
                url: '{{ url("list-laporan-presensi") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    "_token": '{{csrf_token()}}'
                }
            },
            "initComplete":function( settings, json){
                feather.replace()
            }
        });
        let allowAll = ['010103','010201','0102','010201','010301','010401','010501','010601','010701']
        let role = '{{@$user->role}}'
        let satkerid = '{{@$user->satkerid}}'

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
                        dataTable.ajax.reload()
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

        $(document).on('click', '.btn-download', function (e) {
            // e.preventDefault()
            setTimeout(() =>{
                location.reload()
            }, 1000);
        })

        $('#btn-submit').on('click',function(){
            let satkerid = $('#satkerid').val()
            let nama_satker = $('#satkerid option:selected').text()
            let bulan = $('#bulan option:selected').text()
            let tahun = $('#tahun option:selected').text()

            let type = $('#type').val()
            const typeText = $('#type option:selected').text();
            if(type == 'presensi_detail_presensi_per_satker'){
                $('#form').attr('action',"{{ url('rekap-presensi-all') }}/"+satkerid)
            }else{
                $('#form').attr('action',"{{ url('laporan-presensi/print') }}")
            }
            $('#nama_satker').val(nama_satker)
            $(this).append(`<input type="hidden" name="nama_satker" value="${nama_satker}" /> `);
            $(this).append(`<input type="hidden" name="bulan_text" value="${bulan}" /> `);
            $(this).append(`<input type="hidden" name="tahun_text" value="${tahun}" /> `);
            $(this).append(`<input type="hidden" name="type_text" value="${typeText}" /> `);
            $('#form').submit()
        })

        $('#type').on('change', function() {
            dataTable.ajax.reload();
        });

        $('#refresh-ajax').on('click', function () {
            dataTable.ajax.reload();
            feather.replace()
        });

        if(allowAll.includes(satkerid) || role == 'admin'){
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
                            {satker:'010701',parent:'0107'},
                        ]
                        $.each(satkerData, function (i, ss) {
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
    });

</script>
@include('layouts.alerts')
@endpush
