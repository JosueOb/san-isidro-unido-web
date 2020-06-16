@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Moderadores
@endsection
@section('page-header')
    Moderador
@endsection
@section('item-moderator')
    active
@endsection
@section('item-moderator-collapse')
    show
@endsection
@section('item-moderator-list')
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
                <h4 class="d-inline">Detalle de moderador</h4>
                @can('moderators.edit')
                    @if ($moderator->getRelationshipStateRolesUsers('moderador'))
                        <a href="{{route('moderators.edit',$moderator->id)}}" class="btn btn-secondary float-right"><i class="far fa-edit"></i> Editar</a>
                    @endif
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <p><strong>Nombre:</strong> {{$moderator->first_name}}</p>
                        <p><strong>Apellidos:</strong> {{$moderator->last_name}}</p>
                        <p><strong>Corre electrónico:</strong> {{$moderator->email}}</p>
                        <p><strong>Estado:</strong> {{$moderator->getRelationshipStateRolesUsers('moderador') ? 'Activo': 'Inactivo'}}</p>
                        <p><strong>Número telefónico:</strong> {{$moderator->number_phone ?: 'No registrado'}}</p>
                        <p><strong>Corre verificado:</strong> {{$moderator->email_verified_at ?: 'No verificado'}}</p>
                    </div>
                    <div class="col text-center">
                        <img class="rounded-circle dark w-25" src="{{$moderator->getAvatar()}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection