@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Directiva
@endsection
@section('page-header')
    Listado de cargos
@endsection
@section('item-directive')
    active
@endsection
@section('item-directive-collapse')
    show
@endsection
@section('item-positions-list')
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
                <h4 class="d-inline">Cargos</h4>
                <a href="{{route('positions.create')}}" class="btn btn-primary float-right">Nuevo</a>
            </div>
            <div class="card-body">
                @if (count($positions)>0)
                <table class="table table-light table-hover">
                    <thead>
                        <tr>
                            <th width='10px'>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th colspan="2">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($positions as $position)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$position->name}}</td>
                                <td>{{$position->description ?? 'Sin descripción'}}</td>
    
                                <td width='10px'>
                                    <a href="{{route('positions.edit',$position->id)}}" class="btn btn-secondary"> Editar</a>
                                    </td>
                                <td width='10px'>
                                    <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deletePosition{{$position->id}}">Eliminar</a>
                                    <!--Modal-->
                                    <div class="modal fade" id="deletePosition{{$position->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarPosicion" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Está seguro de eliminar el cargo de {{ strtolower($position->name) }}?
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                            <form action="{{ route('positions.destroy', $position->id) }}" method="POST">
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
                    <p class="text-center">Nigún miembros de la directiva registrado</p>
                @endif
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$positions->total()}}</p>
                <nav>
                    {{$positions->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection