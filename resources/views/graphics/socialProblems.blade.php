@extends('layouts.dashboard')
@section('scripts')
{{-- Load the AJAX API --}}
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Work', 11],
            ['Eat', 2],
            ['Commute', 2],
            ['Watch TV', 2],
            ['Sleep', 7]
        ]);

        var options = {
            title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
</script>
@endsection
@section('page-subtitle')
    Módulo Problema Social
@endsection
@section('page-header')
    Gráfico estadístico
@endsection
@section('item-problem')
    active
@endsection
@section('item-problem-collapse')
    show
@endsection
@section('item-problem-graphic')
    active
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="card card-primary" id='social-problem-show'>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Problemas sociales</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="piechart" style="width: 900px; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection