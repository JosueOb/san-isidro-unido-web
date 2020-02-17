@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Categoría
@endsection
@section('page-header')
    Listado de categorías
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
                        {{-- @can('categories.create')
                        <a href="{{route('categories.create')}}" class="btn btn-primary float-right">Nuevo</a>
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
                                    @can(['categories.edit'])
                                    <th>Opción</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{$category->id}}</td>
                                        <td>{{$category->name}}</td>
                                        <td>{{$category->description ?: 'Sin descripción'}}</td>
                                        
                                        @can('categories.edit')
                                        <td width='10px'>
                                            <a href="{{route('categories.edit', $category->id)}}" class="btn btn-secondary"> Editar</a>
                                        </td>
                                        @endcan

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="text-center">Niguna categoría registrada</p>
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