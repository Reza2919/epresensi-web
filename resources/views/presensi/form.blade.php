@extends('layouts.app')
@section('title','Ubah Presensi - Presensi KEMNAKER')
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
            <div class="card card-profile">
                <img src="{{ asset('assets') }}/app-assets/images/banner/banner-12.jpg" class="img-fluid card-img-top" alt="Profile Cover Photo">
                <div class="card-body">
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
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ @$presensi ? 'Ubah' : 'Tambah' }} Presensi</h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" method="POST" action="{{route('presensi.save',[$presensi->id_presensi])}}">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="id_sistem_kerja">Sistem Kerja</label>
                                    <select id="id_sistem_kerja" class="form-control" name="id_sistem_kerja">
                                        <option value="{{ $presensi->id_sistem_kerja }}" selected="selected">{{ $presensi->sistem_kerja->nama_sistem_kerja }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary mr-1 mt-2 waves-effect waves-float waves-light btn-save float-left">Simpan</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Hari:</h5>
                                    <p class="card-text">{{ @$hari }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Tanggal:</h5>
                                    <p class="card-text">{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Jam Masuk:</h5>
                                    <p class="card-text">{{ @$presensi->jam_masuk }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Jam Keluar:</h5>
                                    <p class="card-text">{{ @$presensi->jam_keluar }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Lokasi Masuk:</h5>
                                    <p class="card-text">{{ @$presensi->lokasi_masuk }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Lokasi Keluar:</h5>
                                    <p class="card-text">{{ @$presensi->lokasi_keluar }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <h5 class="mb-75">Foto Masuk:</h5>
                                    <img src="{{ env('API_URL').'/'.$presensi->foto_masuk }}" alt="" style="width: 200px">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="mt-2">
                                    <h5 class="mb-75">Foto Keluar:</h5>
                                    <img src="{{ env('API_URL').'/'.$presensi->foto_keluar }}" alt="" style="width: 200px">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                        <h4 class="card-title">Potongan <button type="button" class="btn btn-primary btn-sm float-right" id="btn-add-potongan"><i data-feather="plus"></i>Tambah Potongan</button></h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$presensi->potongan_tukin as $p)
                            <tr>
                                <td>{{ $p->keterangan}}</td>
                                <td>{{ round($p->jumlah_potongan,2) }}</td>
                                <td>
                                    @if ($user->role == 'admin')
                                        <button type="button" class="btn btn-info btn-sm btn-edit" data-id="{{ $p->id_potongan_tukin }}" data-jumlah_potongan="{{ $p->jumlah_potongan }}" data-keterangan="{{ $p->keterangan }}"><i data-feather="edit"></i></button>
                                        <a href="{{ url('potongan-tukin/'.$p->id_potongan_tukin) }}" type="button" class="btn btn-warning btn-sm btn-delete"><i data-feather="trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total Potongan</th>
                                <th colspan="2">{{ round($presensi->sum_potongan,2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade text-left modal-primary" id="potongan-modal" role="dialog" aria-labelledby="potongan-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="potongan-modal-label">Data Potongan {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d-m-Y') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="id_potongan_tukin" value="" id="id_potongan_tukin">
                        <div class="form-group">
                          <label for="jumlah_potongan">Jumlah Potongan (%)</label>
                          <input type="number" step="0.01" name="jumlah_potongan" id="jumlah_potongan" class="form-control" placeholder="Contoh: -0.5">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                          <label for="keterangan">Keterangan</label>
                          <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                <button class="btn btn-success" id="btn-save-potongan">Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left modal-primary" id="cuti-modal" role="dialog" aria-labelledby="cuti-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cuti-modal-label">Pilih Cuti</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form form-vertical" method="POST" action="{{route('presensi.save',[$presensi->id_presensi])}}">
            @csrf
            @method('patch')
            <input type="hidden" name="id_sistem_kerja" value="" id="m-id_sistem_kerja">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="id_cuti">Cuti</label>
                            <select id="id_cuti" class="form-control" name="id_cuti">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success btn-save">Simpan</button>
            </div>
            </form>
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
        let role = "{{$user->role}}"
        $('#id_sistem_kerja').select2({
            placeholder: '- Pilih Sistem Kerja -',
            ajax: {
                url: '{{ url('api/get-sistem-kerja/select2') }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        valueWithText : 1,
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    let rows = []
                    $.each(data.data, function (i, v) { 
                        if(role == 'tu'){
                            if(v.text != 'WFH' && v.text != 'WFO'){
                                rows.push(v)
                            }
                        }else{
                            rows.push(v)
                        }
                    });
                    console.log(rows)
                    return {
                        results: rows
                    };
                }
            }
        });
        $('#id_cuti').select2({
            placeholder: '- Pilih Cuti -',
            ajax: {
                url: '{{ url('api/get-cuti/select2') }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
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
        
        $('#id_sistem_kerja').on('change',function(){
            let id_sistem_kerja = $(this).val();
            $('#m-id_sistem_kerja').val(id_sistem_kerja)
            $.ajax({
                type: "get",
                url: "{{ url('api/get-sistem-kerja-by-id') }}/"+id_sistem_kerja,
                dataType: "json",
                success: function (response) {
                    if(response.is_cuti == 1){
                        $('#cuti-modal').modal('show')
                    }
                }
            });
        });
        
        $('#btn-add-potongan').on('click',function(){
            $('#id_potongan_tukin').val('')
            $('#potongan-modal').modal('show')
        })
        $(document).on('click','.btn-edit',function(){
            let jml = $(this).data('jumlah_potongan').toFixed(2)
            let keterangan = $(this).data('keterangan')
            let id = $(this).data('id')
            $('#id_potongan_tukin').val(id)
            $('#keterangan').val(keterangan)
            $('#jumlah_potongan').val(jml)
            $('#potongan-modal').modal('show')
        })
        $('#btn-save-potongan').on('click',function(){
            let id_potongan_tukin = $('#id_potongan_tukin').val();
            let keterangan = $('#keterangan').val();
            let jumlah_potongan = $('#jumlah_potongan').val();
            let id_presensi = '{{ $presensi->id_presensi }}';
            let tanggal = '{{ $presensi->tanggal }}';
            let id_pegawai = '{{ $presensi->id_pegawai }}';
            $.ajax({
                type: "post",
                url: '{{ url("potongan-tukin") }}',
                data: {
                    id_presensi : id_presensi,
                    id_potongan_tukin : id_potongan_tukin,
                    id_pegawai : id_pegawai,
                    tanggal : tanggal,
                    jumlah_potongan : jumlah_potongan,
                    keterangan : keterangan,
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
                        $('#id_potongan_tukin').val('')
                        $('#keterangan').val('')
                        $('#jumlah_potongan').val('')
                        $('#potongan-modal').modal('hide')

                        setTimeout(() => {
                            window.location.reload()
                        }, 1000);
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
                            text: 'Terjadi kesalahan pada server!'
                        })
                    } else {
                        Swal.fire(
                            'Deleted!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                        setTimeout(() => {
                            window.location.reload()
                        }, 1000);
                    }
                })
            }
            })
        })
    });
</script>
@include('layouts.alerts')
@endpush