@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Moderadores
@endsection
@section('page-header')
    Asignar moderador
@endsection
@section('item-moderator')
    active
@endsection
@section('item-moderator-collapse')
    show
@endsection
@section('item-moderator-create')
    active
@endsection
@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>
{{-- <div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-body">
                <form action="{{route('search.neighbors')}}" method="GET">
 
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
                                >Apellido</option>
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
</div> --}}
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Moradores registrados</h4>

                        @can('neighbors.create')
                        <a href="{{route('neighbors.create')}}" class="btn btn-primary float-right">Agregar</a>
                        @endcan

                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- <div class="row">
                    <div class="col text-center">
                        @can('neighbors.index')
                        <a href="{{route('neighbors.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('neighbors.filters', 1)}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Activos</a>
                        <a href="{{route('neighbors.filters', 2)}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Inactivos</a>
                        @endcan
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col table-responsive mt-3">
                        @if (count($neighbors)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                    @canany(['moderators.create'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($neighbors as $neighbor)
                                    <tr>
                                        <td>{{ $neighbor->id }}</td>
                                        <td>{{ $neighbor->first_name }}</td>
                                        <td>{{ $neighbor->last_name }}</td>
                                        <td>{{ $neighbor->email }}</td>
                                        <td>
                                            <span class="badge badge-pill {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'badge-success': 'badge-danger'}}">
                                                {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'Activo': 'Inactivo'}}
                                            </span>
                                        </td>

                                        @can('moderators.create')
                                        <td width='10px'>
                                            @if ($neighbor->getASpecificRole('moderador'))
                                                <p>Asignado</p>
                                            @else
                                                <form action="{{ route('moderators.store', $neighbor->id) }}" method="GET">
                                                    <button type="submit" class="btn btn-info">Asignar</button>
                                                </form>
                                            @endif
                                        </td>
                                        @endcan

                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-center">Nigún registro</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$neighbors->total()}}</p>
                <nav>
                    {{$neighbors->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection