@extends('layouts.dashboard')
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
                <p>Se presenta la cantidad de problemas sociales reportados por los moradores de barrio en base a la categoría a la que pertenecen</p>
                <graphic-social-problems></graphic-social-problems>
                <small class="text-muted">Solo se concideran los problemas sociales verificados por parte de los moderadores</small>
            </div>
        </div>
    </div>
</div>
@endsection