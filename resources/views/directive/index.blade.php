@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Directiva
@endsection
@section('page-header')
    Listado de miembros
@endsection
@section('item-directive')
    active
@endsection
@section('item-directive-collapse')
    show
@endsection
@section('item-directive-list')
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
                <form action="{{route('search.members')}}" method="GET">
 
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
                        <h4 class="d-inline">Miembros de la directiva registrados</h4>

                        @can('members.create')
                        <a href="{{route('members.create')}}" class="btn btn-primary float-right">Agregar</a>
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
                        @can('members.index')
                        @php
                            $searchOption = request()->query('searchOption');
                            $searchValue = request()->query('searchValue');
                        @endphp
                        <a href="{{route('members.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('search.members', [
                            'filterOption'=>1, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Activos</a>
                        <a href="{{route('search.members',[
                            'filterOption'=>2, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Inactivos</a>
                        @endcan
                    </div>
                </div>
                <div class="row">
                    <div class="col table-responsive mt-3">
                        @if (count($members)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Apellidos</th>
                                    <th>Nombre</th>
                                    <th>Cargo</th>
                                    <th>Estado</th>
                                    @canany(['members.show', 'members.edit','members.destroy'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr>
                                        <td>{{$member->last_name}}</td>
                                        <td>{{$member->first_name}}</td>
                                        <td>{{$member->position ? $member->position->name : 'Sin cargo' }}</td>
                                        <td>
                                            <span class="badge badge-pill {{$member->getRelationshipStateRolesUsers('directivo') ? 'badge-success': 'badge-danger'}}">
                                                {{$member->getRelationshipStateRolesUsers('directivo') ? 'Activo': 'Inactivo'}}
                                            </span>
                                        </td>
                                        
                                        @can('members.show')
                                        <td width='10px'>
                                            <a href="{{route('members.show',$member->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan

                                        @can('members.edit')
                                        
                                            @if (Auth::user()->id != $member->id)
                                                @if ($member->getASpecificRole('directivo')->pivot->state)
                                                <td width='10px'>
                                                    <a href="{{route('members.edit',$member->id)}}" class="btn btn-secondary"> Editar</a>
                                                </td>
                                                @endif
                                            
                                            @endif
                                        @endcan

                                        @can('members.destroy')
                                        <td width='10px'>
                                            @if (Auth::user()->id != $member->id)
                                                @if ($member->getRelationshipStateRolesUsers('directivo'))
                                                    <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteMember{{$member->id}}">Eliminar</a>
                                                    
                                                @else
                                                    <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activeMember{{$member->id}}">Activar</a>
                                                @endif
                                            @endif
                                           

                                            <!--Modal-->
                                            <div class="modal fade" id="deleteMember{{$member->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarMiembro" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de eliminar al usuario {{ $member->first_name }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('members.destroy', $member->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="activeMember{{$member->id}}" tabindex="-1" role="dialog" aria-labelledby="activarMiembro" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar activación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de activar al usuario {{ $member->first_name }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                                        <form action="{{ route('members.destroy', $member->id) }}" method="POST">
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
                <p class="text-muted m-0 float-right">Total: {{$members->total()}}</p>
                <nav>
                    {{$members->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection