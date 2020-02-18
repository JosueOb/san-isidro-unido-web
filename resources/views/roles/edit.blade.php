@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Roles
@endsection
@section('page-header')
    Editar rol
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
            <div class="card-body">
                <form action="{{route('roles.update', $role->id)}}" method="POST" class="needs-validation">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{old('name') ?: $role->name}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción <span class="text-muted">(opcional)</span></label>
                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" maxlength="255" autofocus>{{ old('description') ?: $role->description }}</textarea>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <hr>
                    <h3>Asignar permisos</h3>
                    <div class="form-group">
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
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" class="custom-control-input @error('permissions') is-invalid @enderror" id="{{$permission->name}}" value="{{$permission->id}}" 
                                                @foreach ($rolePermissions as $rolPermission)
                                                    @if ($rolPermission->id == $permission->id)
                                                        {{'checked'}}
                                                    @endif
                                                @endforeach>
                                                <label class="custom-control-label" for="{{$permission->name}}">
                                                    {{$permission->name}}
                                                    <em>({{$permission->description ?: 'Sin descripción'}})</em>
                                                </label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endforeach
                                @error('permissions')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                        </div>
                    </div>
    
                    <div class="form-group col-4 offset-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            Guardar
                            <i class="far fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection