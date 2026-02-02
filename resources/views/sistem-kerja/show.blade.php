@extends('layouts.app')
@section('title','Sistem Kerja - Presensi KEMNAKER')
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
                <form class="form form-vertical" method="POST" action="{{route('sistem-kerja-detail.update',[$sistem_kerja->id_sistem_kerja])}}">
                    @csrf
                    <div class="card-header">
                        <h4 class="card-title">Ketentuan Jam Kerja - {{ $sistem_kerja->nama_sistem_kerja }}</h4>
                        <input type="submit" class="btn btn-primary btn-sm float-right" value="Simpan">
                    </div>
                    <div class="card-body">
                        @if (@$sistem_kerja)
                            @method('patch')
                        @endif
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Total Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (@$sistem_kerja->sistem_kerja_detail)
                                    @foreach ($detail as $item)
                                        <tr>
                                            <td>{{ $item->hari }}</td>
                                            <td><input class="form-control time-mask" type="text" name="jam_masuk[{{$item->hari}}]" value="{{ @$item->jam_masuk }}"></td>
                                            <td><input class="form-control time-mask" type="text" name="jam_keluar[{{$item->hari}}]" value="{{ @$item->jam_keluar }}"></td>
                                            <td><input class="form-control" step="0.01" type="number" name="total_jam_kerja[{{$item->hari}}]" value="{{ @$item->total_jam_kerja }}"></td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                                        <tr>
                                            <td>{{ $day }}</td>
                                            <td><input class="form-control time-mask" type="text" name="jam_masuk[{{$day}}]" value="00:00"></td>
                                            <td><input class="form-control time-mask" type="text" name="jam_keluar[{{$day}}]" value="00:00"></td>
                                            <td><input class="form-control" step="0.01" type="number" name="total_jam_kerja[{{$day}}]" value="7.5"></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                            
                    </div>
            </div>
            </form>
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
        $('.time-mask').toArray().forEach(function(field){
            new Cleave(field, {time: true, timePattern: ["h", "m"]});
         })
    });
</script>
@include('layouts.alerts')
@endpush