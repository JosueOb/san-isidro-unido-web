@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Registrar un nuevo lugar
@endsection
@section('item-public-service')
    active
@endsection
@section('item-public-service-collapse')
    show
@endsection
@section('item-public-service-create')
    active
@endsection
@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>

<div class="row">
    <div class="col">
       <p>Registrar un nuevo servicio público</p>
    </div>
</div>

@endsection