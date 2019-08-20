@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Roles
@endsection
@section('page-header')
    Detalle Rol
@endsection
@section('item-role')
    active
@endsection
@section('item-role-collapse')
    show
@endsection
@section('item-role-list')
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
                    <h4 class="d-inline">{{$role->name}}</h4>
                    @if (!$hasTheSameRole)
                        @can('roles.edit')
                            <a href="{{route('roles.edit',$role->id)}}" class="btn btn-primary float-right">Editar</a>
                        @endcan
                    @endif
            </div>
            <div class="card-body">
                <p><strong>Slug:</strong> {{$role->slug}}</p>
                <p><strong>Descripción:</strong> {{$role->description}}</p>
                <p><strong>Permiso especial:</strong> {{$role->special ?: 'Ninguno'}}</p>
                @if (count($permissions)>0)
                    <h4>Permisos asignados</h4>
                    @foreach ($permissions as $permission)
                    <ul class="list-unstyled">
                        <li>
                            {{$permission->name}}
                        </li>
                    </ul>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection