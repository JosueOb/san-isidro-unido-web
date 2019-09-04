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
                    @can('roles.edit')
                        @if (!$role->mobile_app && $role->slug != 'admin')
                            <a href="{{route('roles.edit',$role->id)}}" class="btn btn-primary float-right">Editar</a>
                        @endif
                    @endcan
            </div>
            <div class="card-body">
                <p><strong>Slug:</strong> {{$role->slug}}</p>
                <p><strong>Descripción:</strong> {{$role->description ?: 'Sin descripción'}}</p>
                <p><strong>Permiso especial:</strong> {{$role->special ?: 'Ninguno'}}</p>
                <h4>Permisos asignados</h4>
                @if (count($permissions)>0)
                    @foreach ($permissions as $permission)
                    <ul class="list-unstyled">
                        <li>
                            {{$permission->name}} <em>({{$permission->description ?: 'Sin descripción'}})</em>
                        </li>
                    </ul>
                    @endforeach
                @else
                    <p>Ningún permiso asignado</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection