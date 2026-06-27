@extends('layouts.app')
@section('title','Libur - Presensi KEMNAKER')
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
                    <h4 class="card-title">Data Libur</h4>
                    <div class="float-right">
                      <a href="#" class="btn btn-success btn-sm btn-sync" type="button"> <i data-feather="radio"></i> Sinkronisasi Hari Libur</a>
                        <a href="{{ url('libur/create') }}" class="btn btn-primary btn-sm" type="button"><i data-feather="plus"></i> Tambah Libur</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-datatable table-responsive p-2">
                        <table class="user-list-table table libur-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Libur</th>
                                    <th>Tanggal</th>
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

<div class="modal fade text-left modal-primary" id="sync-modal" tabindex="-1" role="dialog" aria-labelledby="sync-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sync-modal-label">Sinkronisasi Hari Libur Dengan Google Calendar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">Informasi</h4>
                    <div class="alert-body">Hari libur yang dipilih akan ditambahkan atau diperbaharui pada sistem.</div>
                </div>
                <div class="table-responsive">
                    <table class="table holiday-table">
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>Nama Libur</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="store-libur" data-url="{{ url('/api/libur/store/bulk') }}">Simpan</button>
            </div>
        </div>
    </div>
</div>
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
        let table = $('.libur-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("api/get-libur/datatable") }}',
                type: 'GET',
            },
            drawCallback: function( settings ) {
                feather.replace()
            }
        })

        $(document).on('click', '.btn-sync', () => {
   $('#sync-modal').modal("show")
    let holidayTable = $('.holiday-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        searching: false,
        paging: false,
        ajax: {
            url: '{{ url("api/get-libur/google-calendar") }}',
            type: 'GET',
        },
        drawCallback: function( settings ) {
            feather.replace()
        }
    })
})
        $(document).on('click', '#store-libur', (e) => {
            let url = $(this).data('url')
            e.preventDefault()
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan melakukan sinkronisasi data libur. Data yang sama akan terganti dengan data yang baru.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya yakin!',
                cancelButtonText: 'Batalkan!'
            }).then((result) => {
                if (result.isConfirmed == true) {
                    let checkedData = []

                    $('input[type="checkbox"]:checked').each(function() {
                        let nama_libur = $(this).data('nama_libur')
                        let tanggal = $(this).data('tanggal')

                        let liburdata = {
                            nama_libur: nama_libur,
                            tanggal: tanggal
                        }

                        checkedData.push(liburdata);
                    });

                    $.ajax({
                        url: "{{ route('libur.storebulk') }}",
                        method: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            libur: checkedData
                        }
                    }).then(function(res){
                        if(res.error){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan pada server!'
                            })
                        } else {
                            Swal.fire(
                                'Success!',
                                'Sinkronisasi berhasil! Data libur telah ditambahkan dan diperbaharui',
                                'success'
                            ).then(result => {
                                if (result) {
                                    location.reload()
                                }
                            })
                        }
                    })
                }
            })
        })
      
});
</script>
@include('layouts.alerts')
@endpush
