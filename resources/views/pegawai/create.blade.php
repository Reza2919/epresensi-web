@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Tambah Pegawai</h4>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ url('pegawai/store') }}">
            @csrf

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control">
            </div>

            <div class="form-group">
                <label>NIP</label>
                <input type="text" name="nip" class="form-control">
            </div>

            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="jabatan" class="form-control">
            </div>

            <div class="form-group">
                <label>Satker</label>
                <input type="text" name="satker" class="form-control">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Aktif">Aktif</option>
                    <option value="Non Aktif">Non Aktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan
            </button>

        </form>

    </div>
</div>

@endsection