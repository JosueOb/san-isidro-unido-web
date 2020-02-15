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
                <p><strong>Descripción:</strong> {{$role->description ?: 'Sin descripción'}}</p>
                <p><strong>Permiso especial:</strong> {{$role->special ?: 'Ninguno'}}</p>
                <h4>Permisos asignados</h4>

                @if ($permissionGroup->count() > 0)
                <div class="list-group list-group-flush accordion">
                    @foreach ($permissionGroup as $key => $permissions)
                    @php
                        //Se pasa el primer caracter a mayúscula
                        $nameGroup = ucfirst(strtolower($key));
                        //Se reemplazan las tíldes por su respectiva vocal
                        $id_element = str_replace(
                            array('Á','É','Í','Ó','Ú','á','é','í','ó','ú'),
                            array('A','E','I','O','U','a','e','i','o','u'),
                            $key
                        );
                        //Se convierte a la cadena a minúsculas
                        $id_element = strtolower($id_element);
                        //Se reemplazan los espacios por un guion
                        $id_element = str_replace(' ', '-', $id_element);
                    @endphp
                        <a class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#{{$id_element}}" aria-expanded="true" aria-controls="collapse">
                            {{$nameGroup}}
                            <span class="badge badge-dark badge-pill ml-1">{{$permissions->count()}}</span>
                            <i class="fas fa-caret-down float-right"></i>
                        </a>
                        <div id="{{$id_element}}" class="collapse">
                            <ul class="list-group list-group-flush list-unstyled">
                                @foreach ($permissions as $permission)
                                <li class="list-group-item">
                                    {{$permission->name}}
                                    <em>({{$permission->description ?: 'Sin descripción'}})</em>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                @else
                <p>Ningún permiso asignado</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection