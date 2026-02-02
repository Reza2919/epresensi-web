@extends('layouts.app')
@section('title','Dashboard - Presensi KEMNAKER')
@section('content')
@php
    $user = session('userdata')
@endphp
@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
crossorigin=""/>
<link rel="stylesheet" href="{{asset("assets/leaflet-clustering")}}/MarkerCluster.css" />
<link rel="stylesheet" href="{{asset("assets/leaflet-clustering")}}/MarkerCluster.Default.css" />
<style>
    #map{
        height: 600px;
    }
</style>
@endpush
<!-- Dashboard Analytics Start -->
<section id="dashboard-analytics">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Filter</h4>

                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li>
                                <a data-action="collapse"><i data-feather="chevron-up"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label for="satkerid">Satker</label>
                                    <select id="satkerid" class="form-control" name="satkerid" {{ !@$user->satkerid || in_array(@$user->satkerid,['010103','010201','0102','010301']) ? '' : 'disabled' }}>
                                        <option value="">Pilih Satker</option>
                                        @if (@$user->role != 'admin')
                                            <option value="{{ $user->satkerid }}" selected="selected">{{ $user->satker }}</option>
                                        @else
                                            <option value="01" selected="selected">Kementerian Ketenagakerjaan</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="pieChart" style="height: 400px"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-12">
            <div class="card card-statistics">
                <div class="card-body statistics-body">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <h4>Rekap Presensi</h4>
                            <p>Rekap Presensi Pegawai <span class="nama_satker"></span></p>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                              <label for="">Dari Tanggal</label>
                              <input type="date"
                                class="form-control" name="chart_date_start" id="chart_date_start" aria-describedby="helpId" placeholder="" value="{{ \Carbon\Carbon::now()->subDays(7)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                              <label for="">Sampai Tanggal</label>
                              <input type="date"
                                class="form-control" name="chart_date_end" id="chart_date_end" aria-describedby="helpId" placeholder="" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="statistic">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <h4>Tren Presensi</h4>
                            <p class="mb-1">Tren Presensi Pegawai <span class="nama_satker"></span></p>
                        </div>
                    </div>
                    <div>
                        <canvas id="trendChart" style="height: 400px"></canvas>
                    </div>                      
                </div> 
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <h4>Peta Persebaran Presensi</h4>
                            <p class="mb-1">Persebaran Presensi Pegawai <span class="nama_satker"></span></p>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                              <input type="date"
                                class="form-control" name="tanggal" id="tanggal" aria-describedby="helpId" placeholder="" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div id="map" style="z-index:10"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning p-2 text-center mt-2" role="alert">
                                <strong>Klik lingkaran pada peta untuk fokus pada wilayah tertentu.</strong>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        
    </div>
</section>
<!-- Dashboard Analytics end -->
@endsection
@push('js')
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
crossorigin=""></script>
<script src="{{asset("assets/leaflet-clustering")}}/leaflet.markercluster-src.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
<script>
    
    var map = L.map('map').setView([0.7893, 113.9213], 5);
    let params = getParams();
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {foo: 'bar', noWrap: true}).addTo(map);
    let today = "2022-05-13"
    var markers = L.markerClusterGroup();

    let myChart;
    let myPieChart;
    $(document).ready(function () {


        let allowAll = ['010103','010201','0102','010201','010301','010401','010501','010601','010701']
        let role = '{{@$user->role}}'
        let satkerid = '{{@$user->satkerid}}'
        
        if(allowAll.includes(satkerid) || role=='admin' ){
            $('#satkerid').select2({
                placeholder: '- Pilih Satker -',
                ajax: {
                    url: '{{ url('api/get-satker/select2') }}',
                    dataType: 'json',
                    processResults: function (data) {
                        let s = '{{@$user->satkerid}}'
                        let satkerData = [
                            {satker:'010201',parent:'0102'},
                            {satker:'010301',parent:'0103'},
                            {satker:'010401',parent:'0104'},
                            {satker:'010501',parent:'0105'},
                            {satker:'010601',parent:'0106'},
                            {satker:'010701',parent:'0107'}
                        ]
                        $.each(satkerData, function (idx, ss) { 
                            if(s == ss.satker){
                                let newData = []
                                $.each(data.data, function (i, v) { 
                                    if(v.id == ss.parent || v.id.substring(0,4) == ss.parent){
                                        newData.push(v)
                                    }
                                });
                                data.data = newData
                            }
                        });
                        return {
                            results: data.data
                        };
                    }
                }
            });
        }
        $('#satkerid').on('change',function(){
            $('.nama_satker').html($(this).select2('data')[0].text)
            loadData();
            loadChart();
            loadStatistik();
            loadPieChart();
        });
        $('#tanggal').on('change',function(){
            loadData();
        });
        $('#chart_date_start').on('change',function(){
            loadChart();
            loadStatistik();
            loadPieChart();
        });
        $('#chart_date_end').on('change',function(){
            loadChart();
            loadStatistik();
            loadPieChart();
        });


        $('.nama_satker').html($('#satkerid').select2('data')[0].text)
        loadData();
        loadChart();
        loadStatistik();
        loadPieChart();
    });

    function loadData(){
        let params = getParams()
        markers.clearLayers()
		$.ajax({
            type: "get",
            url: "{{url('presensi-latlong')}}?"+params,
            dataType: "json",
            success: function (response) {
                $.each(response.rows, function (indexInArray, v) { 
                    if(v.lat_in != '-'){
                        var latlng = L.latLng(v.lat_in, v.long_in);
                        var marker = L.marker(latlng);
                        marker.bindPopup("<h6>"+v.nama_satker+"</h6>"+v.nama_pegawai+" - <strong>"+v.sistem_kerja.nama_sistem_kerja+"</strong><br>Jam Masuk : "+v.jam_masuk+"<br>Jam Keluar : "+(v.jam_keluar ?? '-')+"<br><br>"+v.lokasi_masuk)
			            markers.addLayer(marker);
                    }
                });
            }
        });

		map.addLayer(markers);
        
    }
    function serialize(obj) {
        var str = [];
        for (var p in obj)
            if (obj.hasOwnProperty(p)) {
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        }
        return str.join("&");
    }
    function getParams(){

        let params = serialize({
            idsatker: $('#satkerid').val(),
            tanggal: $('#tanggal').val()
        })
        return params;
    }
    function loadChart(){
        if($('#satkerid').val() != ""){
            $.ajax({
                type: "get",
                url: "{{ url('get-trend-presensi') }}?date_start="+$('#chart_date_start').val()+"&date_end="+$('#chart_date_end').val()+"&satkerid="+$('#satkerid').val(),
                dataType: "json",
                success: function (response) {
                    const labels = response.data.label

                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Hadir',
                                backgroundColor: 'rgb(40, 218, 198)',
                                borderColor: 'rgb(40, 218, 198)',
                                data: response.data.hadir,
                                tension: 0.5
                            },
                            {
                                label: 'Tidak Hadir',
                                backgroundColor: 'rgb(102, 110, 232)',
                                borderColor: 'rgb(102, 110, 232)',
                                data: response.data.tidak_hadir,
                                tension: 0.5
                            }
                        ]
                    };

                    const config = {
                        type: 'line',
                        data: data,
                        options: {
                            elements: {
                                line: {
                                    borderJoinStyle: 'round'
                                }
                            },
                            maintainAspectRatio: false,
                        }
                    };
                    if(myChart){
                        myChart.destroy()
                    }
                    myChart = new Chart(
                        document.getElementById('trendChart'),
                        config
                    );
                }
            });
        }
        
        
    }
    function loadPieChart(){
        let params = getParamsStatistic()
        if($('#satkerid').val() != ""){
            $.ajax({
                type: "get",
                url: "{{ url('get-statistic') }}?"+params,
                dataType: "json",
                success: function (response) {
                    let labels = [];
                    let d = [];
                    $.each(response, function (idx, v) { 
                         labels.push(v.nama_sistem_kerja)
                         d.push(v.count)
                    });
                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Total Presensi per Sistem Kerja',
                                backgroundColor: [
                                    '#1abc9c',
                                    '#e74c3c',
                                    '#f1c40f',
                                    '#f39c12',
                                    '#9B59B6',
                                    '#3499DB',
                                    '#e67e22',
                                    '#ff7979',
                                    '#30336b',
                                    '#badc58',
                                ],
                                data: d
                            }
                        ]
                    };

                    const config = {
                        type: 'pie',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins:{
                                labels: {
                                    render: 'percentage',
                                    fontColor: ['green', 'white', 'red'],
                                    precision: 2
                                }
                            }
                        },
                        
                    };
                    if(myPieChart){
                        myPieChart.destroy()
                    }
                    myPieChart = new Chart(
                        document.getElementById('pieChart'),
                        config
                    );
                }
            });
        }
        
        
    }

    function loadStatistik(){
        let params = getParamsStatistic()
        $.ajax({
            type: "get",
            url: "{{ url('get-statistic') }}?"+params,
            dataType: "json",
            success: function (response) {
                let data = '';
                $.each(response, function (i, v) { 
                    let icon = 'user-x';
                    let bg = 'warning';
                     if(v.nama_sistem_kerja == 'WFH' || v.nama_sistem_kerja == 'WFO' || v.nama_sistem_kerja == 'Upacara Bendera' || v.nama_sistem_kerja == 'Dinas Keluar'|| v.nama_sistem_kerja == 'Tugas Belajar'){
                         icon = 'user-check'
                         bg = 'success'
                     }
                     data += '<div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0 mt-2">'+
                            '<div class="d-flex flex-row">'+
                                '<div class="avatar bg-light-'+bg+' me-2">'+
                                '<div class="avatar-content">'+
                                    '<i data-feather="'+icon+'"></i>'+
                                '</div>'+
                                '</div>'+
                                '<div class="my-auto ml-2">'+
                                '<h4 class="fw-bolder mb-0">'+v.nama_sistem_kerja+'</h4>'+
                                '<p class="card-text font-small-5 mb-0">'+v.count+' orang</p>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                });
                $('#statistic').html(data)
                feather.replace()
            }
        });
    }
    function getParamsStatistic(){

        let params = serialize({
            idsatker: $('#satkerid').val(),
            date_start: $('#chart_date_start').val(),
            date_end: $('#chart_date_end').val()
        })
        return params;
    }
</script>
@endpush