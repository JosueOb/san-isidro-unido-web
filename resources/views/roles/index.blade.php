@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Roles
@endsection
@section('page-header')
    Roles registrados
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
        <div class="card card-primary">
            <div class="card-header">
                <h4 class="d-inline">Lista de roles</h4>
                <a href="#" class="btn btn-primary float-right">Crear rol</a>
            </div>
            <div class="card-body">
                <div class="table table-light table-responsive table-hover">
                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th width='10px'>#</th>
                                <th>Nombre</th>
                                <th>Slug</th>
                                <th>Descripción</th>
                                <th colspan="3">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{$role->id}}</td>
                                    <td>{{$role->name}}</td>
                                    <td>{{$role->slug}}</td>
                                    <td>{{$role->description}}</td>
                                    <td width='10px'>
                                        <a href="#" class="btn btn-info">Ver</a>
                                    </td>
                                    <td width='10px'>
                                        <a href="#" class="btn btn-secondary"> Editar</a>
                                        </td>
                                    <td width='10px'>
                                        <a href="#" class="btn btn-danger">Eliminar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-right">
                <p class="text-muted m-0">Total: {{$roles->count()}}</p>
                {{$roles->links()}}
            </div>
        </div>
    </div>
</div>
@endsection