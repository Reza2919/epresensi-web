@extends('layouts.app')
@section('title','Config - Presensi KEMNAKER')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/editors/quill/katex.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/editors/quill/monokai-sublime.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/editors/quill/quill.bubble.css">
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
                    <h4 class="card-title">{{ @$config ? 'Ubah' : 'Tambah' }} Config</h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" method="POST" action="{{ @$config ? route('config.update',[$config->id_config]) : route('config.store') }}">
                        @csrf
                        @if (@$config)
                            @method('patch')
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" id="name" class="form-control" name="name" placeholder="Nama Config" value="{{ @$config->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="value">{{ @strtoupper(str_replace('-',' ',$config->name)) }}</label>
                                <div id="toolbar"></div>
                                <div id="value" style="height: 500px">
                                    {!! @$config->value !!}
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="form-group">
                                    <label for="desc">Keterangan</label>
                                    <textarea name="desc" id="desc" class="form-control" cols="30" rows="3" required>{{ @$config->desc }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" id="save" class="btn btn-primary mr-1 waves-effect waves-float waves-light">Simpan</button>
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
<script src="{{ asset('assets') }}/app-assets/vendors/js/editors/quill/katex.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/editors/quill/highlight.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/editors/quill/quill.min.js"></script>
<script>
    $(document).ready(function () {
        var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],

        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction

        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
        ];
        var quill = new Quill('#value', {
            theme: 'snow',
            modules : {
                toolbar: toolbarOptions
            }
        });
        $('#save').on('click',function(){
            var form = $('form')[0];
            var formData = new FormData(form);

            $('#value').find('.ql-tooltip').remove
            var editor_content = quill.container.innerHTML

            formData.append('value',editor_content)
            formData.append('_method','put')
            formData.append('is_redirect',false)
            console.log(formData)
            $.ajax({
                type: "POST",
                url: "{{ url('config/'.$config->id_config) }}",
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    if(response.code == 200){
                        toastr['success'](
                            response.data.name+' berhasil diubah!',
                            'Success',
                            {
                                closeButton: true,
                                tapToDismiss: false,
                            }
                        );
                    }else{
                        if(response.error_api){
                            toastr['error'](
                                response.error_api,
                                'Whoops!',
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
