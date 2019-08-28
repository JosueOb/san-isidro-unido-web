@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Directiva
@endsection
@section('page-header')
    Miembro de la directiva
@endsection
@section('item-role')
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
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                    <h4>Detalle de usuario</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <p><strong>Id:</strong> {{$member->id}}</p>
                        <p><strong>Nombre:</strong> {{$member->first_name}}</p>
                        <p><strong>Apellidos:</strong> {{$member->last_name}}</p>
                        <p><strong>Corre electrónico:</strong> {{$member->email}}</p>
                        <p><strong>Cargo:</strong> {{$member->position ? $member->position->name : 'Sin cargo'}}</p>
                        <p><strong>Estado:</strong> {{$member->state ? 'Activo' : 'Inactivo'}}</p>
                        <p><strong>Número telefónico:</strong> {{$member->number_phone ?: 'No registrado'}}</p>
                        <p><strong>Corre verificado:</strong> {{$member->email_verified_at ?: 'No verificado'}}</p>


                    </div>
                    <div class="col text-center">
                        <img class="rounded-circle dark w-25" src="{{$member->avatar}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection