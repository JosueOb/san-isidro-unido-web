@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Lista de categorías
@endsection
@section('item-category')
    active
@endsection
@section('item-category-collapse')
    show
@endsection
@section('item-category-list')
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
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Categorías</h4>
                        {{-- @can('positions.create')
                        <a href="{{route('positions.create')}}" class="btn btn-primary float-right">Nuevo</a>
                        @endcan --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($categories)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width='10px'>Id</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Grupo</th>
                                    {{-- @canany(['positions.show', 'positions.edit','positions.destroy']) --}}
                                    <th>Opciones</th>
                                    {{-- @endcanany --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{$category->id}}</td>
                                        <td>{{$category->name}}</td>
                                        <td>{{$category->description ?? 'Sin descripción'}}</td>
                                        <td>{{$category->group}}</td>
                                        
                                        {{-- @can('positions.edit') --}}
                                        <td width='10px'>
                                            <a href="{{route('categories.edit', $category->id)}}" class="btn btn-secondary"> Editar</a>
                                        </td>
                                        {{-- @endcan --}}
                                        
                                        {{-- @can('positions.destroy') --}}
                                        <td width='10px'>
                                            <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deletePosition{{$category->id}}">Eliminar</a>
                                            <!--Modal-->
                                            <div class="modal fade" id="deletePosition{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarPosicion" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de eliminar el cargo de {{ strtolower($category->name) }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                                    <form action="{{route('categories.destroy', $category->id)}}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- @endcan --}}

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="text-center">Nigún cargo registrado</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$categories->total()}}</p>
                <nav>
                    {{$categories->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection