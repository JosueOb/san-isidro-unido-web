@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Policía
@endsection
@section('page-header')
    Listado de policías
@endsection
@section('item-police')
    active
@endsection
@section('item-police-collapse')
    show
@endsection
@section('item-police-list')
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
                <form action="{{route('search.policemen')}}" method="GET">
 
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
                        <h4 class="d-inline">Policías registrados</h4>

                        @can('policemen.create')
                        <a href="{{route('policemen.create')}}" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Agregar</a>
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
                        @can('policemen.index')
                        @php
                            $searchOption = request()->query('searchOption');
                            $searchValue = request()->query('searchValue');
                        @endphp
                        <a href="{{route('policemen.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('search.policemen', [
                            'filterOption'=>1, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Activos</a>
                        <a href="{{route('search.policemen', [
                            'filterOption'=>2, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Inactivos</a>
                        @endcan
                    </div>
                </div>
                <div class="row">
                    <div class="col table-responsive mt-3">
                        @if (count($policemen)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                    @canany(['policemen.show', 'policemen.edit','policemen.destroy'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policemen as $police)
                                    <tr>
                                        <td>{{ $police->last_name }}</td>
                                        <td>{{ $police->first_name }}</td>
                                        <td>{{ $police->email }}</td>
                                        <td>
                                            <span class="badge badge-pill {{$police->getRelationshipStateRolesUsers('policia') ? 'badge-success': 'badge-danger'}}">
                                                {{$police->getRelationshipStateRolesUsers('policia') ? 'Activo': 'Inactivo'}}
                                            </span>
                                        </td>

                                        @can('policemen.show')
                                        <td width='10px'>
                                            <a href="{{route('policemen.show', $police->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan

                                        @can('policemen.edit')
                                        {{-- Si el usuario tiene al menos un rol del sistema web no se presenta la opción de editar --}}
                                            @if ($police->getRelationshipStateRolesUsers('policia'))
                                                <td width='10px'>
                                                    <a href="{{route('policemen.edit', $police->id)}}" class="btn btn-secondary"> Editar</a>
                                                </td>
                                                
                                            @endif
                                        @endcan

                                        @can('policemen.destroy')
                                        <td width='10px'>
                                            @if ($police->getRelationshipStateRolesUsers('policia'))
                                                <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deletePolice{{$police->id}}">Desactivar</a>
                                            @else
                                                <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activePolice{{$police->id}}">Activar</a>
                                            @endif
                                        

                                            <!--Modal-->
                                            <div class="modal fade" id="deletePolice{{$police->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarPolicia" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5 class="text-center font-weight-bolder">¿Está seguro de desactivar al policía {{ $police->getFullName() }} ?</h5>
                                                        <small class="text-muted"><strong>Recuerda: </strong> una vez desactivado al policía {{ $police->getFullName() }}, no podrá ingresar a la aplicación móvil</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{route('policemen.destroy', $police->id)}}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="activePolice{{$police->id}}" tabindex="-1" role="dialog" aria-labelledby="activarPolice" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar activación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5 class="text-center font-weight-bolder">¿Está seguro de activar al policía {{ $police->getFullName() }} ?</h5>
                                                        <small class="text-muted"><strong>Recuerda: </strong> una vez activado al policía {{ $police->getFullName() }}, podrá ingresar nuevamente a la aplicación móvil</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                                        <form action="{{route('policemen.destroy', $police->id)}}" method="POST">
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
                <p class="text-muted m-0 float-right">Total: {{$policemen->total()}}</p>
                <nav>
                    {{$policemen->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection