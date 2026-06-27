@extends('layouts.app')
@section('title','Tunjangan Kinerja - Presensi KEMNAKER')
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
                    <h4 class="card-title">{{ @$tukin ? 'Ubah' : 'Tambah' }} Tunjangan Kinerja</h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" method="POST" action="{{ @$tukin ? route('tukin.update',[$tukin->id_tukin]) : route('tukin.store') }}">
                        @csrf
                        @if (@$tukin)
                            @method('patch')
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="col-12">
    <div class="form-group">
        <label>Periode</label>

        <select class="form-control" name="id_periode" required>

            @foreach($periodes as $periode)

                <option value="{{ $periode->id_periode }}"
                    {{ @$tukin->id_periode == $periode->id_periode ? 'selected' : '' }}>
                    {{ $periode->periode }}
                </option>

            @endforeach

        </select>
    </div>
</div>
                                  <label for="">Grade</label>
                                  <select class="form-control" name="kelas_jabatan" id="kelas_jabatan">
                                    @for ($i = 1; $i <= 17; $i++)
                                        <option value="{{ $i }}" {{ @$tukin->kelas_jabatan == $i ? 'selected' : '' }}>{{ $i }}</option> 
                                    @endfor
                                  </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="jumlah_tukin">Jumlah Tunjangan Kinerja</label>
                                    <input type="string" id="jumlah_tukin" class="form-control" name="jumlah_tukin" placeholder="Jumlah Tukin" value="{{ @$tukin->jumlah_tukin }}" required>
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
    $(document).ready(function () {
        new AutoNumeric('#jumlah_tukin', { currencySymbol : 'Rp',digitGroupSeparator: '.',decimalCharacter: ',',vMin: 0 });
        let table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("api/get-tukin/datatable") }}',
                type: 'GET',
            },
            drawCallback: function( settings ) {
                feather.replace()
            }
        }) 
    });
</script>
@include('layouts.alerts')
@endpush