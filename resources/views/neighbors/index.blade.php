@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Vecinos
@endsection
@section('page-header')
    Listado de moradores
@endsection
@section('item-neighbor')
    active
@endsection
@section('item-neighbor-collapse')
    show
@endsection
@section('item-neighbor-list')
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
</div>
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Moradores registrados</h4>

                        @can('neighbors.create')
                        <a href="{{route('neighbors.create')}}" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Agregar</a>
                        @endcan

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @error('filterOption')
                        <span class="invalid-feedback d-inline text-center mb-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="col text-center">
                        @can('neighbors.index')
                        @php
                            $searchOption = request()->query('searchOption');
                            $searchValue = request()->query('searchValue');
                        @endphp
                        <a href="{{route('neighbors.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('search.neighbors', [
                            'filterOption'=>1, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Activos</a>
                        <a href="{{route('search.neighbors', [
                            'filterOption'=>2, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Inactivos</a>
                        @endcan
                    </div>
                </div>
                <div class="row">
                    <div class="col table-responsive mt-3">
                        @if (count($neighbors)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Apellidos</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                    @canany(['neighbors.show', 'neighbors.edit','neighbors.destroy'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($neighbors as $neighbor)
                                    <tr>
                                        <td>{{ $neighbor->last_name }}</td>
                                        <td>{{ $neighbor->first_name }}</td>
                                        <td>{{ $neighbor->email }}</td>
                                        <td>
                                            <span class="badge badge-pill {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'badge-success': 'badge-danger'}}">
                                                {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'Activo': 'Inactivo'}}
                                            </span>
                                        </td>

                                        @can('neighbors.show')
                                        <td width='10px'>
                                            <a href="{{route('neighbors.show',$neighbor->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan

                                        @can('neighbors.edit')
                                        {{-- Si el usuario tiene al menos un rol del sistema web no se presenta la opción de editar --}}
                                            @if ($neighbor->getWebSystemRoles()->isEmpty() && $neighbor->getRelationshipStateRolesUsers('morador'))
                                                <td width='10px'>
                                                    <a href="{{route('neighbors.edit',$neighbor->id)}}" class="btn btn-secondary"> Editar</a>
                                                </td>
                                                
                                            @endif
                                        @endcan

                                        @can('neighbors.destroy')
                                        <td width='10px'>
                                            @if (Auth::user()->id != $neighbor->id)
                                                @if ($neighbor->getRelationshipStateRolesUsers('morador'))
                                                    <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteNeighbor{{$neighbor->id}}">Desactivar</a>
                                                @else
                                                    <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activeNeighbor{{$neighbor->id}}">Activar</a>
                                                @endif
                                            @endif
                                           

                                            <!--Modal-->
                                            <div class="modal fade" id="deleteNeighbor{{$neighbor->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarVecino" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar desactivación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5 class="text-center font-weight-bolder">¿Está seguro de desactivar al usuario {{ $neighbor->getFullName() }} ?</h5>
                                                        <small class="text-muted"><strong>Recuerda: </strong> una vez desactivado el usuario {{ $neighbor->getFullName() }}, no podrá ingresar a la aplicación móvil</small>
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
                                                        <h5 class="text-center font-weight-bolder">¿Está seguro de activar al usuario {{ $neighbor->getFullName() }} ?</h5>
                                                        <small class="text-muted"><strong>Recuerda: </strong> una vez activado al usuario {{ $neighbor->getFullName() }}, podrá ingresar nuevamente a la aplicación móvil</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                                        <form action="{{ route('neighbors.destroy', $neighbor->id) }}" method="POST">
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
                    {{$neighbors->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection