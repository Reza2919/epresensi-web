@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Edit Pegawai</h4>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ url('pegawai/'.$pegawai->id.'/update') }}">
            @csrf

            <div class="form-group">
                <label>Nama</label>
                <input type="text"
                       name="nama"
                       class="form-control"
                       value="{{ $pegawai->nama }}">
            </div>

            <div class="form-group">
                <label>NIP</label>
                <input type="text"
                       name="nip"
                       class="form-control"
                       value="{{ $pegawai->nip }}">
            </div>

            <div class="form-group">
                <label>Jabatan</label>
                <input type="text"
                       name="jabatan"
                       class="form-control"
                       value="{{ $pegawai->jabatan }}">
            </div>

            <div class="form-group">
                <label>Satker</label>
                <input type="text"
                       name="satker"
                       class="form-control"
                       value="{{ $pegawai->satker }}">
            </div>

            <div class="form-group">
                <label>Status</label>
                <input type="text"
                       name="status"
                       class="form-control"
                       value="{{ $pegawai->status }}">
            </div>

            <button type="submit" class="btn btn-primary">
                Update
            </button>

        </form>

    </div>
</div>

@endsection