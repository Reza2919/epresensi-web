@extends('layouts.app')
@section('title','Laporan Presensi - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Generate Presensi</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Generate Presensi
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
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 class="class-title text-white">Generate Presensi</h4>
                        </div>
                        <div class="card-body pt-2">
                            <form action="{{ url('generate-presensi') }}" method="post" id="form">
                                @csrf
                                <input type="hidden" name="nama_satker" id="nama_satker">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <label for="tahun">Tanggal</label>
                                        <div class="form-group">
                                            <div class="form-group">
                                                <input type="date"
                                                  class="form-control" name="tanggal" id="tanggal" aria-describedby="helpId" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-info text-white float-right" id="btn-submit">
                                            Generate
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
                                        <h4 class="class-title text-white">History Generate Presensi</h4>
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
                                        <th>Tanggal</th>
                                        <th>Jumlah Pegawai</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                        <th>Created At</th>
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
            searching: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("list-generate-presensi") }}',
                type: 'GET',
            },
            "initComplete":function( settings, json){
                feather.replace()
            }
        });

        $('#type').on('change', function() {
            dataTable.ajax.reload();
        });

        $('#refresh-ajax').on('click', function () {
            dataTable.ajax.reload();
            feather.replace()
        });

    });

</script>
@include('layouts.alerts')
@endpush
