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
@section('item-moderator-assign')
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
                <form action="{{route('search.assign')}}" method="GET">
 
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

                        @can('moderators.create')
                        <a href="{{route('moderators.create')}}" class="btn btn-primary float-right">Registrar</a>
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
                        @can('moderators.assign')
                        @php
                            $searchOption = request()->query('searchOption');
                            $searchValue = request()->query('searchValue');
                        @endphp
                        <a href="{{route('moderators.assign')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('search.assign', [
                            'filterOption'=>1, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Activos</a>
                        <a href="{{route('search.assign', [
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
                                    <th></th>
                                    <th>Apellidos</th>
                                    <th>Nombre</th>
                                    <th>Correo electrónico</th>
                                    <th>Estado</th>
                                    @canany(['moderators.assign'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($neighbors as $neighbor)
                                    <tr >
                                        <td width='10px'>
                                            <img src="{{$neighbor->getAvatar()}}" class="rounded-circle" style="width: 2.25rem; height: 2.25rem; object-fit: cover;" alt="user-avatar">
                                        </td>
                                        <td>{{ $neighbor->last_name }}</td>
                                        <td>{{ $neighbor->first_name }}</td>
                                        <td>{{ $neighbor->email }}</td>
                                        <td>
                                            <span class="badge badge-pill {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'badge-success': 'badge-danger'}}">
                                                {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'Activo': 'Inactivo'}}
                                            </span>
                                        </td>

                                        @can('moderators.assign')
                                        <td width='10px'>

                                            @if ($neighbor->getRelationshipStateRolesUsers('morador'))
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#assignModal{{$neighbor->id}}">
                                                Asignar
                                            </button>
                                            
                                            <!-- Modal -->
                                            <div class="modal fade" id="assignModal{{$neighbor->id}}" tabindex="-1" role="dialog" aria-labelledby="assignlModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="assignlModalLabel">Confirmar asignación</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5 class="text-center font-weight-bolder">¿Está seguro de asignar al usuario {{ $neighbor->getFullName() }} como moderador?</h5>
                                                            <small class="text-muted"><strong>Recuerda: </strong> una vez asignado el rol de moderador al usuario {{ $neighbor->getFullName() }}, podrá ingresar al sistema web.</small>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        
                                                            <form action="{{ route('moderators.storeAssign', $neighbor->id) }}" method="POST">
                                                                @csrf
                                                                @method('put')
                                                                <button type="submit" class="btn btn-success">Asignar</button>
                                                            </form>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                    {{$neighbors->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>


@endsection