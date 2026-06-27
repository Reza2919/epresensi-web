@extends('layouts.app')
@section('title','Golongan Pajak - Presensi KEMNAKER')
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
                    <h4 class="card-title">{{ @$golongan_pajak ? 'Ubah' : 'Tambah' }} Golongan Pajak</h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" method="POST" action="{{ @$golongan_pajak ? route('golongan-pajak.update',[$golongan_pajak->id_golongan_pajak]) : route('golongan-pajak.store') }}">
                        @csrf
                        @if (@$golongan_pajak)
                            @method('patch')
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                  <label for="">Golongan</label>
                                 <select class="form-control" name="golongan" id="golongan" required>
    <option value="">Pilih Golongan</option>

    @for($i=1;$i<=20;$i++)
        <option
            value="Golongan {{ $i }}"
            {{ @$golongan_pajak->golongan=="Golongan ".$i ? 'selected' : '' }}>
            Golongan {{ $i }}
        </option>
    @endfor
</select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="pajak_persen">Jumlah Pajak (%)</label>
                                    <input type="string" id="pajak_persen" class="form-control" name="pajak_persen" placeholder="Pajak persen" value="{{ @$golongan_pajak->pajak_persen }}" required>
                                </div>
                            </div>
                            <div class="col-12">
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
<script src="{{ asset('assets') }}/app-assets/vendors/js/autoNumeric/autoNumeric.min.js"></script>
<script>
   <script>
$(function(){

});
</script>
@include('layouts.alerts')
@endpush