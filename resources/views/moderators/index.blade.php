@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Moderadores
@endsection
@section('page-header')
    Lista de moderadores
@endsection
@section('item-moderator')
    active
@endsection
@section('item-moderator-collapse')
    show
@endsection
@section('item-moderator-list')
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
                        <h4 class="d-inline">Moderadores asignados</h4>

                        @can('moderators.create')
                        <a href="{{route('moderators.create')}}" class="btn btn-primary float-right">Asignar</a>
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
                        @if (count($moderators)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                    @canany(['moderators.show','moderators.destroy'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($moderators as $moderator)
                                    <tr>
                                        <td>{{ $moderator->id }}</td>
                                        <td>{{ $moderator->first_name }}</td>
                                        <td>{{ $moderator->last_name }}</td>
                                        <td>{{ $moderator->email }}</td>
                                        <td>
                                            <span class="badge badge-pill {{ $moderator->getRelationshipStateRolesUsers('moderador') ? 'badge-success': 'badge-danger'}}">
                                                {{ $moderator->getRelationshipStateRolesUsers('moderador') ? 'Activo': 'Inactivo'}}
                                            </span>
                                        </td>

                                        @can('moderators.show')
                                        <td width='10px'>
                                            <a href="{{route('moderators.show', $moderator->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan

                                        @can('moderators.destroy')
                                        <td width='10px'>
                                            @if ($moderator->getRelationshipStateRolesUsers('moderador'))
                                                <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteModerator{{$moderator->id}}">Desactivar</a>
                                            @else
                                                <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activeModerator{{$moderator->id}}">Activar</a>
                                            @endif
                                           

                                            <!--Modal-->
                                            <div class="modal fade" id="deleteModerator{{$moderator->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarModerador" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de eliminar al moderador {{ $moderator->first_name }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{route('moderators.destroy', $moderator->id)}}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Desactivar</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="activeModerator{{$moderator->id}}" tabindex="-1" role="dialog" aria-labelledby="activarModerador" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar activación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de activar al moderador {{ $moderator->first_name }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                                        <form action="{{route('moderators.destroy', $moderator->id)}}" method="POST">
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
                <p class="text-muted m-0 float-right">Total: {{$moderators->total()}}</p>
                <nav>
                    {{$moderators->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection