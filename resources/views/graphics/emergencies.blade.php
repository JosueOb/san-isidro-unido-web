@extends('layouts.dashboard')
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
            <div class="card-body"> <p>Se presenta la cantidad de emergencias reportadas por los moradores de barrio por día</p>
                <graphic-emergencies></graphic-emergencies>
                <small class="text-muted">Solo se concideran las emergencias abordadas por la policía comunitaria del barrio</small>
            </div>
        </div>
    </div>
</div>
@endsection