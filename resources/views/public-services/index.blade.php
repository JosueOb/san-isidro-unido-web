@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Lugares registrados
@endsection
@section('item-public-service')
    active
@endsection
@section('item-public-service-collapse')
    show
@endsection
@section('item-public-service-list')
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
       <p>Listar servicios públicos</p>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Servicios públicos</h4>
                        @can('publicServices.create')
                        <a href="{{route('publicServices.create')}}" class="btn btn-primary float-right">Nuevo</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($publicServices)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width='10px'>Id</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Descripción</th>
                                    @canany(['publicServices.edit','publicServices.destroy'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($publicServices as $publicService)
                                    <tr>
                                        <td>{{$publicService->id}}</td>
                                        <td>{{$publicService->name}}</td>
                                        <td>{{$publicService->subcategory->name}}</td>
                                        <td>{{$publicService->description ?: 'Sin descripción'}}</td>

                                        @can('publicServices.show')
                                        <td width='10px'>
                                            <a href="{{route('publicServices.show',$publicService->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan
                                        
                                        @can('publicServices.edit')
                                        <td width='10px'>
                                            <a href="{{route('publicServices.edit', $publicService->id)}}" class="btn btn-secondary"> Editar</a>
                                        </td>
                                        @endcan

                                        @can('publicServices.destroy')
                                        <td width='10px'>
                                            <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deletePublicService{{$publicService->id}}">Eliminar</a>
                                            <!--Modal-->
                                            <div class="modal fade" id="deletePublicService{{$publicService->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarServicioPublico" aria-hidden="true">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de eliminar el servicio público {{ strtolower($publicService->name) }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{route('publicServices.destroy', $publicService->id)}}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </td>
                                        @endcan

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="text-center">Nigún servicio público registrado</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$publicServices->total()}}</p>
                <nav>
                    {{$publicServices->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection