@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Listado de lugares
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
        <div class="card card-primary">
            <div class="card-body">
                <form action="{{route('search.publicServices')}}" method="GET">
 
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select class="custom-select @error('searchOption') is-invalid @enderror" name="searchOption" required>
                                <option value="">Buscar</option>
                                <option value="1"
                                @if (old('searchOption')== 1 || request('searchOption')== 1)
                                    {{'selected'}}
                                @endif
                                >Nombre</option>
                                <option value="2" 
                                @if (old('searchOption')== 2 || request('searchOption')== 2)
                                    {{'selected'}}
                                @endif
                                >Categoría</option>
                            </select>
                            
                        </div>
                        <input type="text" class="form-control @error('searchValue') is-invalid @enderror"  name="searchValue" value="{{old('searchValue') ?: request('searchValue')}}" maxlength="50" required>
                        
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @error('searchOption')
                            <span class="invalid-feedback d-inline" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('searchValue')
                            <span class="invalid-feedback d-inline" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
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
                        <a href="{{route('publicServices.create')}}" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Agregar</a>
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
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    @canany(['publicServices.edit','publicServices.destroy'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($publicServices as $publicService)
                                    <tr>
                                        <td>{{$publicService->name}}</td>
                                        <td>{{$publicService->subcategory->name}}</td>

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
                                                        <h5 class="text-center font-weight-bolder">¿Está seguro de eliminar el servicio público {{ strtolower($publicService->name) }} ?</h5>
                                                        <small class="text-muted">
                                                            <strong>Recuerda: </strong>
                                                            el registro se elimina completamente de la base de datos
                                                        </small>
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
                    {{$publicServices->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection