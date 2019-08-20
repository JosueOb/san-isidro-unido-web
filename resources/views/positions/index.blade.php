@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Directiva
@endsection
@section('page-header')
    Listado de cargos
@endsection
@section('item-directive')
    active
@endsection
@section('item-directive-collapse')
    show
@endsection
@section('item-positions-list')
    active
@endsection
@section('content')
<div class="col">
    <div class="card card-primary">
        <div class="card-header">
            <h4 class="d-inline">Cargos</h4>
            <a href="{{route('positions.create')}}" class="btn btn-primary float-right">Nuevo</a>
        </div>
        <div class="card-body">
            @if (count($positions)>0)
            
            @else
                <p class="text-center">Nigún miembros de la directiva registrado</p>
            @endif
        </div>
        <div class="card-footer">
            <p class="text-muted m-0 float-right">Total: {{$positions->total()}}</p>
            <nav>
                {{$positions->links()}}
            </nav>
        </div>
    </div>
</div>
@endsection