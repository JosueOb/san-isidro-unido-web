@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Roles
@endsection
@section('page-header')
    Registrar rol
@endsection
@section('item-role')
    active
@endsection
@section('item-role-collapse')
    show
@endsection
@section('item-role-create')
    active
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card card-primary">
            {{-- <div class="card-header">
                <h4>Formulario</h4>
            </div> --}}
            <div class="card-body">
                <form action="{{route('roles.store')}}" method="POST">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label for="name">Nombre</label>
                            <input id="name" type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="slug">Slug</label>
                            <input id="slug" type="text" class="form-control" name="slug">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <input id="description" type="email" class="form-control" name="description">
                    </div>
                    <hr>
                    <div class="form-group">
                        <h3>Permiso especial</h3>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name='special' value="all-access" id="access">
                            <label class="form-check-label" for="access">
                                Acceso total
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name='special' value="no-access" id="no-access">
                            <label class="form-check-label" for="no-access">
                                Ningún acceso
                            </label>
                        </div>
                    </div>
                    <hr>
                    <h3>Asignar permisos</h3>
                    <h4 class="text-center">Módulo roles</h4>
                    <div class="form-group">
                        <ul class="list-unstyled">
                           @foreach ($permissions as $permission)
                               <li>
                                   <div class="custom-control custom-checkbox">
                                       <input type="checkbox" name="permissions[]" class="custom-control-input" id="{{$permission->name}}" value="{{$permission->id}}">
                                       <label class="custom-control-label" for="{{$permission->name}}">
                                        {{$permission->name}}
                                        <em>({{$permission->description ?: 'Sin descripción'}})</em>
                                        </label>
                                   </div>
                               </li>
                           @endforeach 
                        </ul>
                    </div>
    
                    <div class="form-group col-4 offset-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            Guardar
                            <i class="far fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
            {{-- <div class="card-footer">
                <p>Footer</p>
            </div> --}}
        </div>
    </div>
</div>
@endsection