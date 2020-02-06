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
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Subcategorías</h4>
                        {{-- @can('categories.create') --}}
                        <a href="{{route('subcategories.create')}}" class="btn btn-primary float-right">Nuevo</a>
                        {{-- @endcan --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($subcategories)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width='10px'>Id</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    {{-- @can(['categories.edit']) --}}
                                    <th>Opciones</th>
                                    {{-- @endcan --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategories as $subcategory)
                                    <tr>
                                        <td>{{$subcategory->id}}</td>
                                        <td>{{$subcategory->name}}</td>
                                        <td>{{$subcategory->description ?? 'Sin descripción'}}</td>
                                        <td>{{$subcategory->category->name }}</td>
                                        
                                        {{-- @can('categories.edit') --}}
                                        <td width='10px'>
                                            <a href="#" class="btn btn-secondary"> Editar</a>
                                        </td>
                                        {{-- @endcan --}}

                                        {{-- @can('positions.destroy') --}}
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
                                                    <form action="#" method="POST">
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
                            <p class="text-center">Niguna subcategoría registrada</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$subcategories->total()}}</p>
                <nav>
                    {{$subcategories->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection