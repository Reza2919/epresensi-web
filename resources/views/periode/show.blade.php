@extends('layouts.app')

@section('title','Detail Periode Tukin')

@section('content')

<section>
    <div class="row">
        <div class="col-md-8">

            <div class="card">

                <div class="card-header">
                    <h4>Detail Periode Tukin</h4>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $periode->id_periode }}</td>
                        </tr>

                        <tr>
                            <th>Periode</th>
                            <td>{{ $periode->periode }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if($periode->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-warning">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Dibuat</th>
                            <td>{{ $periode->createdAt }}</td>
                        </tr>

                        <tr>
                            <th>Diupdate</th>
                            <td>{{ $periode->updatedAt }}</td>
                        </tr>

                    </table>

                    <a href="{{ route('periode.index') }}"
                        class="btn btn-secondary">
                        Kembali
                    </a>

                </div>

            </div>

        </div>
    </div>
</section>

@endsection