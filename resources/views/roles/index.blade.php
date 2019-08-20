@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Roles
@endsection
@section('page-header')
    Listados de roles
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
    <div class=" col-md-12 col-xl-7">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class="d-inline">Roles registrados</h4>
                <a href="{{route('roles.create')}}" class="btn btn-primary float-right">Nuevo</a>
            </div>
            <div class="card-body">
                @if (count($roles)>0)
                <table class="table table-light table-responsive table-hover">
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
                                <td>{{$loop->iteration}}</td>
                                <td>{{$role->name}}</td>
                                <td>{{$role->slug}}</td>
                                <td>{{$role->description}}</td>
                                <td width='10px'>
                                    <a href="{{route('roles.show',$role->id)}}" class="btn btn-info">Ver</a>
                                </td>
                                <td width='10px'>
                                    <a href="{{route('roles.edit',$role->id)}}" class="btn btn-secondary"> Editar</a>
                                    </td>
                                <td width='10px'>
                                    <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteRole{{$role->id}}">Eliminar</a>
                                    <!--Modal-->
                                    <div class="modal fade" id="deleteRole{{$role->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarRol" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Está seguro de eliminar el rol de {{ strtolower($role->name) }}?
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                            </form>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="text-center">Ningún rol registrado</p>
                @endif
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$roles->total()}}</p>
                <nav>
                    {{$roles->links()}}
                </nav>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-5">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class="p-1">Roles del sistema</h4>
            </div>
            <div class="card-body">
                    <table class="table table-light table-responsive table-hover">
                        <thead>
                            <tr>
                                <th width='10px'>#</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Opción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rolesUser as $roleUser)
                            <tr>
                                <td>{{$roleUser->id}}</td>
                                <td>{{$roleUser->name}}</td>
                                <td>{{$roleUser->description}}</td>
                                <td>
                                    <a href="{{route('roles.show',$roleUser->id)}}" class="btn btn-info float-right">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
            <div class="card-footer text-right">
                <p class="text-muted m-0">Total: {{$rolesUser->count()}}</p>
            </div>
        </div>
    </div>
</div>
@endsection