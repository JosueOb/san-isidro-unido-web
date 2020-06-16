@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Vecinos
@endsection
@section('page-header')
    Morador
@endsection
@section('item-neighbor')
    active
@endsection
@section('item-neighbor-collapse')
    show
@endsection
@section('item-neighbor-list')
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
        <div class="card card-primary">
            <div class="card-header">
                    <h4 class="d-inline">Detalle de usuario</h4>
                    @can('neighbors.edit')
                        @if ($neighbor->getWebSystemRoles()->isEmpty() && $neighbor->getRelationshipStateRolesUsers('morador'))
                            <a href="{{route('neighbors.edit',$neighbor->id)}}" class="btn btn-secondary float-right"><i class="far fa-edit"></i> Editar</a>
                        @endif
                    @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <p><strong>Nombre:</strong> {{$neighbor->first_name}}</p>
                        <p><strong>Apellidos:</strong> {{$neighbor->last_name}}</p>
                        <p><strong>Corre electrónico:</strong> {{$neighbor->email}}</p>
                        <p><strong>Estado:</strong> {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'Activo': 'Inactivo'}}</p>
                        <p><strong>Número telefónico:</strong> {{$neighbor->number_phone ?: 'No registrado'}}</p>
                        <p><strong>Corre verificado:</strong> {{$neighbor->email_verified_at ?: 'No verificado'}}</p>


                    </div>
                    <div class="col text-center">
                        <img class="rounded-circle dark w-25" src="{{$neighbor->getAvatar()}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection