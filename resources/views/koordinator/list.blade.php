@extends('layouts.app')
@section('title','Koordinator - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Data Koordinator</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Data Koordinator
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
                    <h4 class="card-title">{{ $satker->satker }}</h4>
                    <div class="float-right">
                        <form action="{{ url('koordinator/'.$id_satker.'/generate') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm" type="button"><i data-feather="plus"></i> Generate Koordinator</a>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-datatable table-responsive p-2">
                        <table class="user-list-table table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th width="40%">Nama</th>
                                    <th>Bidang</th>
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
        let table = $('.table').DataTable({
            searching: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("api/get-koordinator/datatable?id_satker=".$id_satker) }}',
                type: 'GET'
            },
            drawCallback: function( settings ) {
                feather.replace()
            }
        }) 
        $('#satkerid').on('change',function(){
            table.ajax.reload()
        });
        $(document).on('click','.btn-detail',function(){
            let id_satker_bidang = $(this).data('id');
            $('#detail-modal').modal('show')
        })
        
    });
</script>

@include('layouts.alerts')
@endpush