@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Informes
@endsection
@section('page-header')
    Listado de informes
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
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-body">
                <form action="{{route('search.reports')}}" method="GET">
 
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select class="custom-select @error('searchOption') is-invalid @enderror" name="searchOption" required>
                                <option value="">Buscar</option>
                                <option value="1"
                                @if (old('searchOption')== 1 || request('searchOption')== 1)
                                    {{'selected'}}
                                @endif
                                >Título</option>
                                <option value="2" 
                                @if (old('searchOption')== 2 || request('searchOption')== 2)
                                    {{'selected'}}
                                @endif
                                >Autor</option>
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
                        <h4 class="d-inline">Informes registrados</h4>

                        @can('reports.create')
                        <a href="{{route('reports.create')}}" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Agregar</a>
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
                        @can('reports.index')
                        @php
                            $searchOption = request()->query('searchOption');
                            $searchValue = request()->query('searchValue');
                        @endphp
                        <a href="{{route('reports.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('search.reports', [
                            'filterOption'=>1, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Activos</a>
                        <a href="{{route('search.reports', [
                            'filterOption'=>2, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Inactivos</a>
                        @endcan
                    </div>
                </div>
                <div class="row">
                    <div class="col mt-3 mb-2">
                        @if (count($reports) > 0)
                        <div class="row">

                        
                        @foreach ($reports as $report)
                            
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                                <div class="card  card-post">
                                    <div class="card-post__image">
                                        <img src="{{$report->getFirstImage()}}" alt="">

                                        <span class="card-post__category badge badge-pill {{$report->state ? 'badge-success': 'badge-danger'}}">
                                            {{$report->state ? 'Activo': 'Inactivo'}}
                                        </span>
                                        {{-- <a href="#" class="card-post__category badge badge-pill badge-info">Activo</a> --}}
                                        <small class=" card-post__name">Escrito por {{$report->user->getFullName()}}</small>
                                        <small class="card-post__date">{{$report->created_at}}</small>
                                        <div class="card-post__author">
                                            <img class="card-post__author-avatar"  src="{{$report->user->getAvatar()}}" alt="">
                                            {{-- <a href="#" class="card-post__author-avatar" style="background-image: url('images/avatars/0.jpg');"></a> --}}
                                        </div>
                                        
                                    </div>
                                    <div class="card-body">
                                        
                                        <h5 class="card-title">{{$report->title}}</h5>
                                        {{-- <p class="card-text text-muted">{{$report->description}}
                                            <a class="text-muted" href="#">ver mas</a>
                                        </p> --}}
                                    </div>

                                    <div class="card-footer border-top d-flex">
                                        <div class="ml-auto mr-auto">
                                           
                                            @can('reports.show')
                                            <a href="{{route('reports.show',$report->id)}}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                            @endcan

                                            @can('reports.edit')
                                                <a href="{{route('reports.edit',$report->id)}}" class="btn btn-secondary" id='edit'><i class="fas fa-pen"></i></a>
                                            @endcan

                                            @can('reports.destroy')
                                                @if ($report->state )
                                                    <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteReport{{$report->id}}"><i class="fas fa-trash-alt"></i></a>
                                                @else
                                                    <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activeReport{{$report->id}}"><i class="fas fa-check-circle"></i></a>
                                                @endif
                                                <!--Modal-->
                                                <div class="modal fade" id="deleteReport{{$report->id}}" tabindex="-1" role="dialog" aria-labelledby="elimarInforme" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Confirmar desactivación</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5 class="text-center font-weight-bolder">¿Está seguro de desactivar el reporte: {{ $report->title }} ?</h5>
                                                            <small class="text-muted"><strong>Recuerda: </strong> una vez desactivado el reporte: {{ $report->title }}, no podrá ser visualizado en la aplicación móvil</small>
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-danger">Desactivar</button>
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
                                                                <h5 class="text-center font-weight-bolder">¿Está seguro de activar el reporte: {{ $report->title }} ?</h5>
                                                            <small class="text-muted"><strong>Recuerda: </strong> una vez activado el reporte: {{ $report->title }}, podrá ser visualizado nuevamente en la aplicación móvil</small>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit" class="btn btn-success">Activar</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                        </div>

                        @else
                        <p class="text-center">Nigún registro</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$reports->total()}}</p>
                <nav>
                    {{$reports->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection