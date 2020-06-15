@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Subcategoría
@endsection
@section('page-header')
    Listado de subcategorías
@endsection
@section('item-subcategory')
    active
@endsection
@section('item-subcategory-collapse')
    show
@endsection
@section('item-subcategory-list')
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
                <form action="{{route('search.subcategories')}}" method="GET">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            {{-- <select class="custom-select @error('searchOption') is-invalid @enderror" name="searchOption" required> --}}
                            <select class="custom-select @error('searchOption') is-invalid @enderror" name="searchOption" >
                                <option value="">Buscar</option>
                                <option value="1"
                                @if (old('searchOption')== 1 || request('searchOption')== 1)
                                    {{'selected'}}
                                @endif
                                >Nombre</option>
                            </select>
                            
                        </div>
                        {{-- <input type="text" class="form-control @error('searchValue') is-invalid @enderror"  name="searchValue" value="{{old('searchValue') ?: request('searchValue')}}" maxlength="50" required> --}}
                        <input type="text" class="form-control @error('searchValue') is-invalid @enderror"  name="searchValue" value="{{old('searchValue') ?: request('searchValue')}}" maxlength="50">
                        
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
                        <h4 class="d-inline">Subcategorías</h4>
                        @can('subcategories.create')
                        <a href="{{route('subcategories.create')}}" class="btn btn-primary float-right"> <i class="fas fa-plus-circle"></i> Agregar</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    @error('filterOption')
                        <span class="invalid-feedback d-inline text-center mb-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="col text-center">
                        @can('subcategories.index')
                        @php
                            $searchOption = request()->query('searchOption');
                            $searchValue = request()->query('searchValue');
                        @endphp
                        <a href="{{route('subcategories.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('search.subcategories', [
                            'filterOption'=>1, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Servicios Públicos</a>
                        <a href="{{route('search.subcategories', [
                            'filterOption'=>2,
                            'searchOption' => $searchOption,
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Eventos</a>
                        <a href="{{route('search.subcategories', ['filterOption'=>3, 'searchOption' => $searchOption, 'searchValue' => $searchValue])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Problemas</a>
                        @endcan
                    </div>
                </div>
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($subcategories)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th>Icono</th>
                                    @canany(['subcategories.edit','subcategories.destroy'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategories as $subcategory)
                                    <tr>
                                        <td>{{$subcategory->name}}</td>
                                        <td>{{$subcategory->description ?: 'Sin descripción'}}</td>
                                        <td>{{$subcategory->category->name }}</td>
                                        <td class="w-25"><img class="w-25 rounded " src={{$subcategory->getLink()}}></td>
                                        
                                        @can('subcategories.edit')
                                        <td width='10px'>
                                            <a href="{{route('subcategories.edit', $subcategory->id)}}" class="btn btn-secondary"> Editar</a>
                                        </td>
                                        @endcan

                                        @can('subcategories.destroy')
                                        <td width='10px'>
                                            <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteSubcategory{{$subcategory->id}}">Eliminar</a>
                                            <!--Modal-->
                                            <div class="modal fade" id="deleteSubcategory{{$subcategory->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarSubcategoria" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de eliminar la subcategoría {{ strtolower($subcategory->name) }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{route('subcategories.destroy', $subcategory->id)}}" method="POST">
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
                            <p class="text-center">Niguna subcategoría registrada</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$subcategories->total()}}</p>
                <nav>
                    {{$subcategories->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection