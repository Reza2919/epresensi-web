@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card">
        <div class="card-header">
            <h4>Detail Cuti</h4>
        </div>

        <div class="card-body">

            <div class="form-group mb-3">
                <label>Nama Cuti</label>
                <input type="text"
                       class="form-control"
                       value="{{ $cuti->nama_cuti }}"
                       readonly>
            </div>

            <div class="form-group mb-3">
                <label>Keterangan</label>
                <textarea class="form-control" readonly>{{ $cuti->keterangan }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label>Nilai Persen</label>
                <input type="text"
                       class="form-control"
                       value="{{ $cuti->nilai_persen }}%"
                       readonly>
            </div>

            <a href="{{ route('cuti.index') }}"
               class="btn btn-secondary">
               Kembali
            </a>

        </div>
    </div>

</div>
@endsection