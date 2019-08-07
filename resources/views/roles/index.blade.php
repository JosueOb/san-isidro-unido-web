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
        @if (session('info'))
        <div class="alert alert-info" role="alert">
                {{ session('info') }}
            </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('danger'))
        <div class="alert alert-danger" role="alert">
                {{ session('danger') }}
            </div>
        @endif
        <div class="card card-primary">
            <div class="card-header">
                <h4 class="d-inline">Lista de roles</h4>
                <a href="{{route('roles.create')}}" class="btn btn-primary float-right">Crear rol</a>
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