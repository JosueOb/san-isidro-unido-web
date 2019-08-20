@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Directiva
@endsection
@section('page-header')
    Listado de miembros
@endsection
@section('item-directive')
    active
@endsection
@section('item-directive-collapse')
    show
@endsection
@section('item-directive-list')
    active
@endsection
@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xl-7">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class="d-inline">Miembros de la directiva registrados</h4>
                <a href="{{route('members.create')}}" class="btn btn-primary float-right">Agregar</a>
            </div>
            <div class="card-body">
                @if (count($members)>0)
                    
                @else
                    <p class="text-center">Nigún miembros de la directiva registrado</p>
                @endif
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$members->total()}}</p>
                <nav>
                    {{$members->links()}}
                </nav>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-5">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class="p-1">Cargos</h4>
            </div>
            <div class="card-body">
                @if (count($positions)>0)
                
                @else
                    <p class="text-center">Nigún miembros de la directiva registrado</p>
                @endif
            </div>
            <div class="card-footer text-right">
                <p class="text-muted m-0">Total: {{$positions->count()}}</p>
                {{-- <p class="text-muted m-0 float-right">Total: {{$members->total()}}</p> --}}
                <nav>
                    {{$positions->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection