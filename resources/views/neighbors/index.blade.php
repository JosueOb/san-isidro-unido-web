@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Vecinos
@endsection
@section('page-header')
    Listado de moradores
@endsection
@section('item-neigbor')
    active
@endsection
@section('item-neighbor-collapse')
    show
@endsection
@section('item-neigbhor-list')
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
                <form action="{{route('search')}}" method="GET">
 
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
                                <option value="3" 
                                @if (old('searchOption')== 3 || request('searchOption')== 3)
                                    {{'selected'}}
                                @endif
                                >Cargo</option>
                            </select>
                            
                        </div>
                        <input type="text" class="form-control @error('searchValue') is-invalid @enderror"  name="searchValue" value="{{old('searchValue') ?: request('searchValue')}}" required>
                        
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
                        <h4 class="d-inline">Miembros de la directiva registrados</h4>

                        @can('members.create')
                        <a href="{{route('neighbors.create')}}" class="btn btn-primary float-right">Agregar</a>
                        @endcan

                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- <div class="row">
                    <div class="col text-center">
                        @can('members.index')
                        <a href="{{route('members.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('members.filters', 1)}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Activos</a>
                        <a href="{{route('members.filters', 2)}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Inactivos</a>
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
                                    <th>Rol</th>
                                    <th>Cargo</th>
                                    <th>Estado</th>
                                    @canany(['neighbors.show', 'neighbors.edit','neighbors.destroy'])
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
                                        <td>
                                            @foreach ($neighbor->roles as $role)
                                                {{$role->name}}<br>
                                            @endforeach
                                        </td>
                                        <td>{{ $neighbor->position ? $neighbor->position->name : 'Sin cargo' }}</td>
                                        <td><span class="badge badge-pill {{$neighbor->state ? 'badge-success': 'badge-danger'}}">{{ $neighbor->state ? 'Activo': 'Inactivo'}}</span></td>
                                        
                                        @can('neighbors.show')
                                        <td width='10px'>
                                            <a href="{{route('neighbors.show',$neighbor->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan

                                        @can('neighbors.edit')
                                            <td width='10px'>
                                                <a href="{{route('neighbors.edit',$neighbor->id)}}" class="btn btn-secondary"> Editar</a>
                                            </td>
                                        @endcan

                                        @can('members.destroy')
                                        <td width='10px'>
                                            @if ($neighbor->state)
                                                <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteNeighbor{{$neighbor->id}}">Eliminar</a>
                                            @else
                                                <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activeNeighbor{{$neighbor->id}}">Activar</a>
                                            @endif
                                           

                                            <!--Modal-->
                                            <div class="modal fade" id="deleteNeighbor{{$neighbor->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarVecino" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de eliminar al usuario {{ $neighbor->first_name }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('neighbors.destroy', $neighbor->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="activeNeighbor{{$neighbor->id}}" tabindex="-1" role="dialog" aria-labelledby="activarMiembro" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar activación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de activar al usuario {{ $neighbor->first_name }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                                        <form action="{{ route('members.destroy', $neighbor->id) }}" method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-success">Activar</button>
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