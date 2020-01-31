@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Registrar una nueva categoría
@endsection
@section('item-category')
    active
@endsection
@section('item-category-collapse')
    show
@endsection
@section('item-category-category-create')
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
        Formulario para el registro de una nueva categoría
    </div>
</div>
@endsection