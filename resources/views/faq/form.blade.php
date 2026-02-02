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
                    <h4 class="card-title">FAQ</h4>
                </div>
                <div class="card-body">
                    <form class="form form-vertical" method="POST" action="{{@$value != null ? route('faq.update', [@$value->id_faq]) :route('faq.submit')}}" id="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select id="kategori" class="form-control" name="kategori">
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Pertanyaan</label>
                                    <input type="text" id="name" class="form-control" name="pertanyaan" placeholder="Pertanyaan" value="{{@$value->question}}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="value">Jawaban</label>
                                <div id="toolbar"></div>
                                <div id="value" style="height: 500px">
                                    {!! @$value->answer !!}
                                </div>
                            </div>
                            <input type="hidden" name="jawaban" id="jawaban">
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light submit">Simpan</button>
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
<script src="{{ asset('assets') }}/app-assets/vendors/js/editors/quill/katex.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/editors/quill/highlight.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/editors/quill/quill.min.js"></script>
<script>
    $(document).ready(function () {

        $('.submit').on('click', function () {
            $('#value').find('.ql-tooltip').remove()
            const editor_content = quill.getContents();
            $('#jawaban').val(quill.root.innerHTML);
        })

        $('#kategori').select2({
            placeholder: '- Pilih Kategori -',
            ajax: {
                url: '{{ url('api/get-kategori-faq/select2') }}',
                dataType: 'json',
                success: (data) => {
                    console.log(data);
                },
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        });

        @if(@$value != null)
            const newOption = $("<option selected='selected'></option>").val('{{@$value->id_kategori}}').text('{{@$value->faq_kategori->kategori}}');
            $("#kategori").append(newOption).trigger('change.select2')
        @endif

        const toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],

            [{'header': 1}, {'header': 2}],               // custom button values
            [{'list': 'ordered'}, {'list': 'bullet'}],
            [{'script': 'sub'}, {'script': 'super'}],      // superscript/subscript
            [{'indent': '-1'}, {'indent': '+1'}],          // outdent/indent
            [{'direction': 'rtl'}],                         // text direction

            [{'size': ['small', false, 'large', 'huge']}],  // custom dropdown
            [{'header': [1, 2, 3, 4, 5, 6, false]}],

            [{'color': []}, {'background': []}],          // dropdown with defaults from theme
            [{'font': []}],
            [{'align': []}],

            ['clean']                                         // remove formatting button
        ];
        const quill = new Quill('#value', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            }
        });
    });
</script>
@include('layouts.alerts')
@endpush
