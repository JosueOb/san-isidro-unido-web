@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Informes
@endsection
@section('page-header')
    Informes registrados
@endsection
@section('item-report')
    active
@endsection
@section('item-report-collapse')
    show
@endsection
@section('item-report-list')
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
</div> --}}
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Informes registrados</h4>

                        @can('reports.create')
                        <a href="{{route('reports.create')}}" class="btn btn-primary float-right">Agregar</a>
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
                        @if (count($reports) > 0)
                        <table class="table table-light table-hover table-sm" id='reports'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Título</th>
                                    {{-- @canany(['members.show', 'members.edit','members.destroy']) --}}
                                    <th>Opciones</th>
                                    {{-- @endcanany --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $report->id}}</td>
                                        <td>{{$report->title}}</td>
                                        <td>
                                            <span class="badge badge-pill {{$report->state ? 'badge-success': 'badge-danger'}}">
                                                {{$report->state ? 'Activo': 'Inactivo'}}
                                            </span>
                                        </td>
                                        
                                        @can('reports.show')
                                        <td width='10px'>
                                            <a href="{{route('reports.show',$report->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan

                                        @can('reports.edit')
                                        <td width='10px'>
                                            <a href="{{route('reports.edit',$report->id)}}" class="btn btn-secondary" id='edit'>Editar</a>
                                            {{-- <a href="#" class="btn btn-secondary" id='edit'  data-report='{{$report->id}}'>Editar</a> --}}
                                        </td>
                                        @endcan

                                        @can('reports.destroy')
                                        <td width='10px'>
                                            @if ($report->state )
                                                <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteReport{{$report->id}}">Eliminar</a>
                                            @else
                                                <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activeReport{{$report->id}}">Activar</a>
                                            @endif
                                           

                                            <!--Modal-->
                                            <div class="modal fade" id="deleteReport{{$report->id}}" tabindex="-1" role="dialog" aria-labelledby="elimarInforme" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de eliminar el reporte {{ $report->title }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('reports.destroy', $report->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="activeReport{{$report->id}}" tabindex="-1" role="dialog" aria-labelledby="activarInforme" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar activación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de activar el informe {{ $report->title }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        {{-- <button type="button" class="btn btn-primary">Eliminar</button> --}}
                                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST">
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
                <p class="text-muted m-0 float-right">Total: {{$reports->total()}}</p>
                <nav>
                    {{$reports->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection