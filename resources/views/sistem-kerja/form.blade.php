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
                <div class="card-header">
                    <h4 class="card-title">{{ @$sistem_kerja ? 'Ubah' : 'Tambah' }} Sistem Kerja</h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" method="POST" action="{{ @$sistem_kerja ? route('sistem-kerja.update',[$sistem_kerja->id_sistem_kerja]) : route('sistem-kerja.store') }}">
                        @csrf
                        @if (@$sistem_kerja)
                            @method('patch')
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="nama_sistem_kerja">Nama Sistem Kerja</label>
                                    <input type="text" id="nama_sistem_kerja" class="form-control" name="nama_sistem_kerja" placeholder="Nama Sistem Kerja" value="{{ @$sistem_kerja->nama_sistem_kerja }}" {{@$sistem_kerja ? ($sistem_kerja->is_removable == 1 ? '' : 'readonly') : ''}} required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="toleransi">Toleransi Telat (Menit)</label>
                                    <input type="number" id="toleransi" class="form-control" name="toleransi" placeholder="Toleransi" value="{{ @$sistem_kerja->toleransi }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="toleransi">Toleransi Pulang (Menit)</label>
                                    <input type="number" id="toleransi_pulang" class="form-control" name="toleransi_pulang" placeholder="Toleransi" value="{{ @$sistem_kerja->toleransi_pulang }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="potongan_telat">Potongan Telat (%) cth: 0.5</label>
                                    <input type="number" step="0.01" id="potongan_telat" class="form-control" name="potongan_telat" placeholder="Potongan Telat" value="{{ @$sistem_kerja->potongan_telat }}"  {{@$user->role != 'admin' ? 'readonly' : ''}}  required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="potongan_pulang">Potongan Pulang (%) cth: 0.5</label>
                                    <input type="number" step="0.01" id="potongan_pulang" class="form-control" name="potongan_pulang" placeholder="Potongan Tukin" value="{{ @$sistem_kerja->potongan_pulang }}"  {{@$user->role != 'admin' ? 'readonly' : ''}}  required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="potongan_tukin">Potongan Tukin (%) cth: 0.5</label>
                                    <input type="number" step="0.01" id="potongan_tukin" class="form-control" name="potongan_tukin" placeholder="Potongan Tukin" value="{{ @$sistem_kerja->potongan_tukin }}"  {{@$user->role != 'admin' ? 'readonly' : ''}}  required>
                                    <small id="potongan_tukin" class="text-muted">Digunakan untuk potongan per sistem kerja seperti Tidak Hadir</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                  <label for="">Area</label>
                                  <select class="form-control" name="is_in_area" id="is_in_area">
                                    <option value="2" {{ @$sistem_kerja->is_in_area == 2 ? 'selected' : '' }}>Luar & Dalam Radius</option>
                                    <option value="0" {{ @$sistem_kerja->is_in_area == 0 ? 'selected' : '' }}>Luar Radius</option>
                                    <option value="1" {{ @$sistem_kerja->is_in_area == 1 ? 'selected' : '' }}>Dalam Radius</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-12 mb-1" id="is_lembur_el">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" style="font-size: 11pt" name="is_lembur" id="is_lembur" value="1" {{ @$sistem_kerja->is_lembur == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_lembur" style="font-size: 11pt">Sistem Kerja Lembur</label>
                                  </div>
                            </div>
                            <div class="col-12 mb-1" id="is_everyday_el">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" style="font-size: 11pt" name="is_everyday" id="is_everyday" value="1" {{ @$sistem_kerja->is_everyday == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_everyday" style="font-size: 11pt">Sistem Kerja Lembur Hari Kerja <span class="text-danger">*ketika dicentang sistem kerja akan muncul saat hari libur dan hari kerja.</span></label>
                                  </div>
                            </div>
                            <div class="col-12 mb-1">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" style="font-size: 11pt" name="is_cuti" id="is_cuti" value="1" {{ @$sistem_kerja->is_cuti == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_cuti" style="font-size: 11pt">Sistem Kerja Cuti</label>
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
<script>
    $(document).ready(function () {
        let is_lembur = '{{@$sistem_kerja->is_lembur}}'
        if(is_lembur == 0){
            $("#is_everyday_el").hide()
        }
        $("#is_lembur").change(function() {
            if(this.checked) {
                $("#is_everyday_el").show()
            }else{
                $("#is_everyday").attr('checked',true)
                $("#is_everyday_el").hide()
            }
        });
    });
</script>
@include('layouts.alerts')
@endpush