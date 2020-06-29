@extends('layouts.dashboard')
@section('scripts')
{{-- Load the AJAX API --}}
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", { packages: ["calendar"] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn({ type: 'date', id: 'Date' });
        dataTable.addColumn({ type: 'number', id: 'Won/Loss' });
        dataTable.addRows([
            [new Date(2012, 3, 13), 37032],
            [new Date(2012, 3, 14), 38024],
            [new Date(2012, 3, 15), 38024],
            [new Date(2012, 3, 16), 38108],
            [new Date(2012, 3, 17), 38229],
            // Many rows omitted for brevity.
            [new Date(2013, 9, 4), 38177],
            [new Date(2013, 9, 5), 38705],
            [new Date(2013, 9, 12), 38210],
            [new Date(2013, 9, 13), 38029],
            [new Date(2013, 9, 19), 38823],
            [new Date(2013, 9, 23), 38345],
            [new Date(2013, 9, 24), 38436],
            [new Date(2013, 9, 30), 38447]
        ]);

        var chart = new google.visualization.Calendar(document.getElementById('calendar_basic'));

        var options = {
            title: "Red Sox Attendance",
            height: 350,
        };

        chart.draw(dataTable, options);
    }
</script>
@endsection
@section('page-subtitle')
    Módulo Emergencias
@endsection
@section('page-header')
    Gráfico estadístico
@endsection
@section('item-emergency')
    active
@endsection
@section('item-emergency-collapse')
    show
@endsection
@section('item-emergency-graphic')
    active
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="card card-primary" id='social-problem-show'>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Emergencias</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="calendar_basic" style="width: 1000px; height: 350px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection