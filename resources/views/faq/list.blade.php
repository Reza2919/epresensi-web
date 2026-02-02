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
                        <h4 class="card-title">Frequently Asked Questions </h4>
                        <div class="float-right">
                            <a href="{{ url('setting/faq/form') }}" class="btn btn-primary btn-sm" type="button"><i data-feather="plus"></i> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-datatable table-responsive">
                            <table class="user-list-table table">
                                <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Kategori</th>
                                    <th>Pertanyaan</th>
                                    <th>Jawaban</th>
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
                    url: '{{ url("api/get-faq/datatable") }}',
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
