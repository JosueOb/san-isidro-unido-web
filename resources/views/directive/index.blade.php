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
        @if (session('info'))
        <div class="alert alert-info" role="alert">
                {{ session('info') }}
            </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('danger'))
        <div class="alert alert-danger" role="alert">
                {{ session('danger') }}
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col">
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
</div>
@endsection