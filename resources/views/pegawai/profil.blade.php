@extends('layouts.app')
@section('title','Profil - Presensi KEMNAKER')
@section("breadcrumb")
<div class="row breadcrumbs-top">
    <div class="col-12">
        <h2 class="content-header-title float-left mb-0">Profil</h2>
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Profil
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
        <div class="col-lg-6 col-sm-12">
            <div class="card card-profile">
                <img src="{{ asset('assets') }}/app-assets/images/banner/banner-12.jpg" class="img-fluid card-img-top" alt="Profile Cover Photo">
                <div class="card-body">
                @if (@$pegawai)
                    <div class="profile-image-wrapper">
                    <div class="profile-image">
                        <div class="avatar">
                        <img src="{{ @$pegawai->foto ?? asset('assets/app-assets/images/profile/default-user.jpg') }}" alt="Profile Picture">
                        </div>
                    </div>
                    </div>
                    <h3>{{ $pegawai->nama }}</h3>
                    <h6 class="text-muted">{{ @$pegawai->namajabatan }}</h6>
                    <hr class="mb-2">
                    <div class="text-left">
                    <div class="mt-2">
                        <h5 class="mb-75">Unit Kerja:</h5>
                        <p class="card-text">{{ @$pegawai->satker }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Eselon:</h5>
                            <p class="card-text">{{ @$pegawai->eselon }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Golongan:</h5>
                            <p class="card-text">{{ @$pegawai->gol }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">NIP:</h5>
                            <p class="card-text">{{ @$pegawai->nip }}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Tempat, Tanggal Lahir:</h5>
                            <p class="card-text">{{ @$pegawai->tempatlahir }}, {{@$pegawai->tgllahir}}</p>
                        </div>
                        <div class="mt-2">
                            <h5 class="mb-75">Alamat:</h5>
                            <p class="card-text">{{ @$pegawai->alamat }}</p>
                        </div>
                    </div>
                @else
                <div class="profile-image-wrapper">
                    <div class="profile-image">
                        <div class="avatar">
                        <img src="{{ asset('assets/app-assets/images/profile/default-user.jpg') }}" alt="Profile Picture">
                        </div>
                    </div>
                    </div>
                    <h3>{{ $user->nama }}</h3>
                    <h6 class="text-muted">Administrator</h6>
                    <button id="change-password" class="btn btn-primary" role="button">Ubah Password</button>
                @endif
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade text-left modal-primary" id="modal-change-password" tabindex="-1" role="dialog" aria-labelledby="modal-change-password-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-change-password-label">Ubah Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_user" id="id_user" value="{{ @$user->id_user }}">
                <div class="form-group">
                    <label for="password">Password Lama</label>
                    <input id="password" class="form-control" type="password" name="password">
                </div>
                <div class="form-group">
                    <label for="password_baru">Password Baru</label>
                    <input id="password_baru" class="form-control" type="password" name="password_baru">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="save">Simpan</button>
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
        $('#change-password').on('click',function(){
            $('#modal-change-password').modal('show');
        })
        $('#save').on('click',function(){
            $.ajax({
                type: "post",
                url: "{{ url('change-password') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    password: $('#password').val(),
                    password_baru: $('#password_baru').val(),
                    password_baru_confirmation: $('#password_confirmation').val(),
                    id_user: $('#id_user').val(),
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

                    }else{
                        if(typeof(response.message) == 'object'){
                            $.each(response.message, function (idx, m) {
                                toastr['error'](
                                    m+'<br>',
                                    'Error',
                                    {
                                        closeButton: true,
                                        tapToDismiss: false,
                                    }
                                );
                            });
                        }else{
                            toastr['error'](
                                response.message+'<br>',
                                'Error',
                                {
                                    closeButton: true,
                                    tapToDismiss: false,
                                }
                            );
                        }
                    }
                }
            });
        })
    });
</script>
@include('layouts.alerts')
@endpush
