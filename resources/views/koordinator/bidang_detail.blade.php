@extends('layouts.app')
@section('title','Detail Bidang - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Detail Bidang</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ url('/koordinator') }}">Data Koordinator</a>
                </li>
                <li class="breadcrumb-item active">Detail Bidang
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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="mt-2">
                        <h5 class="mb-75">Bidang:</h5>
                        <p class="card-text">{{ @$bidang->nama_bidang }}</p>
                    </div>
                    <div class="mt-2">
                        <h5 class="mb-75">Koordinator:</h5>
                        <p class="card-text">{{ @$bidang->koordinator[0]->nama_pegawai }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="class-title text-white">Data Pegawai</h4>
                    <div class="float-right">
                        <button class="btn btn-success btn-sm float-right btn-add" type="button"><i data-feather="plus"></i> Tambah Pegawai</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-datatable table-responsive p-2">
                        <table class="user-list-table table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th width="40%">Nama Pegawai</th>
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
<div class="modal fade text-left modal-primary" id="add-pegawai-modal" tabindex="-1" role="dialog" aria-labelledby="add-pegawai-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-pegawai-modal-label">Tambahkan Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_pegawai" id="id_pegawai">
                <input type="hidden" name="nama" id="nama">
                <input type="hidden" name="id_satker" id="id_satker">
                <div class="form-group">
                    <label for="pegawai">Pilih Pegawai</label>
                    <select id="pegawai" class="form-control" name="pegawai" multiple>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="save-pegawai">Simpan</button>
            </div>
        </div>
    </div>
</div>
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
            searching: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("api/get-pegawai-bidang/datatable") }}',
                type: 'GET',
                data: {
                    id_satker_bidang : '{{ $id_satker_bidang }}',
                },
            },
            drawCallback: function( settings ) {
                feather.replace()
            }
        }) 
        $('#satkerid').on('change',function(){
            table.ajax.reload()
        });
        $(document).on('click','.btn-add',function(){
            $('#add-pegawai-modal').modal('show')
        })
        $('#pegawai').select2({
            placeholder: '- Pilih Pegawai -',
            ajax: {
                url: '{{ url('api/get-pegawai/select2') }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        satkerid : '{{ $satkerid }}',
                        valueWithText : 1,
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        });
        $('#save-pegawai').on('click',function(){
            let pegawai = $('#pegawai').val();
            let id_satker_bidang = '{{ $id_satker_bidang }}';
            $.ajax({
                type: "post",
                url: "{{ url('koordinator-pegawai') }}",
                data: {
                    id_satker_bidang : id_satker_bidang,
                    pegawai : pegawai,
                    _token : '{{ csrf_token() }}',
                },
                dataType: "json",
                success: function (response) {
                    if(response.success == true){
                        toastr['success'](
                            response.message,
                            'Success',
                            {
                                closeButton: true,
                                tapToDismiss: false,
                            }
                        );
                        table.ajax.reload()
                    }else{
                        toastr['error'](
                            response.message,
                            'Error',
                            {
                                closeButton: true,
                                tapToDismiss: false,
                            }
                        );
                    }
                }
            });
        });
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
                            text: res.message
                        })
                    } else {
                        table.ajax.reload()
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
    });
</script>

@include('layouts.alerts')
@endpush