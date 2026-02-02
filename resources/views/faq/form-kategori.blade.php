@extends('layouts.app')
@section('title','FAQ - Presensi KEMNAKER')
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
                    <h4 class="card-title">Kategori FAQ</h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" method="POST" action="{{@$value != null ?  route('kategori.update', [$value->id_kategori]) : url('setting/kategori/form')}}">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Kategori</label>
                                    <input type="text" id="name" class="form-control" name="kategori" placeholder="Kategori" value="{{@$value->kategori}}" required>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light">Simpan</button>
                                <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
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
    $(document).ready(function () {
        let table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("api/get-config/datatable") }}',
                type: 'GET',
            },
            drawCallback: function( settings ) {
                feather.replace()
            }
        });

        $('#kategori').select2({
            placeholder: '- Pilih Kategori -',
            {{--ajax: {--}}
            {{--    url: '{{ url('api/get-satker/select2') }}',--}}
            {{--    dataType: 'json',--}}
            {{--    processResults: function (data) {--}}
            {{--        let s = '{{@$user->satkerid}}'--}}
            {{--        let satkerData = [--}}
            {{--            {satker:'010201',parent:'0102'},--}}
            {{--            {satker:'010301',parent:'0103'},--}}
            {{--            {satker:'010401',parent:'0104'},--}}
            {{--            {satker:'010501',parent:'0105'},--}}
            {{--            {satker:'010601',parent:'0106'},--}}
            {{--            {satker:'010701',parent:'0107'}--}}
            {{--        ]--}}
            {{--        $.each(satkerData, function (idx, ss) {--}}
            {{--            if(s == ss.satker){--}}
            {{--                let newData = []--}}
            {{--                $.each(data.data, function (i, v) {--}}
            {{--                    if(v.id == ss.parent){--}}
            {{--                        newData.push(v)--}}
            {{--                    }--}}
            {{--                });--}}
            {{--                data.data = newData--}}
            {{--            }--}}
            {{--        });--}}
            {{--        return {--}}
            {{--            results: data.data--}}
            {{--        };--}}
            {{--    }--}}
            {{--}--}}
        });


    });
</script>
@include('layouts.alerts')
@endpush
