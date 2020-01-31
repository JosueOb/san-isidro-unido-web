@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Lista de categorías
@endsection
@section('item-category')
    active
@endsection
@section('item-category-collapse')
    show
@endsection
@section('item-category-category-list')
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
        Listar categorías de servicios públicos
    </div>
</div>
@endsection