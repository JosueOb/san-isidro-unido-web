@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Eventos
@endsection
@section('page-header')
    Eventos registrados
@endsection
@section('item-event')
    active
@endsection
@section('item-event-collapse')
    show
@endsection
@section('item-event-list')
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
                <form action="{{route('search.events')}}" method="GET">
 
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select class="custom-select @error('searchOption') is-invalid @enderror" name="searchOption" required>
                                <option value="">Buscar</option>
                                <option value="1"
                                @if (old('searchOption')== 1 || request('searchOption')== 1)
                                    {{'selected'}}
                                @endif
                                >Evento</option>
                                <option value="2" 
                                @if (old('searchOption')== 2 || request('searchOption')== 2)
                                    {{'selected'}}
                                @endif
                                >Categoría</option>
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
                        <h4 class="d-inline">Eventos</h4>
                        @can('events.create')
                        <a href="{{route('events.create')}}" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Agregar</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
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
                        <a href="{{route('events.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('search.events', [
                            'filterOption'=>1, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Activos</a>
                        <a href="{{route('search.events', [
                            'filterOption'=>2, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Inactivos</a>
                        @endcan
                    </div>
                </div>
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($events)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th>Categoría</th>
                                    <th>Estado</th>
                                    <th>Inicia</th>
                                    {{-- <th>Hora</th> --}}
                                    @canany(['events.show','events.edit','events.destroy'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ $event->title}}</td>
                                        <td>{{ $event->subcategory->name }}</td>
                                        <td>{{ $event->state ? 'Activo': 'Inactivo'}}</td>
                                        <td>{{ $event->additional_data['range_date']['start_date'] }} a las {{ $event->additional_data['range_date']['start_time'] }}</td>

                                        @can('events.show')
                                        <td width='10px'>
                                            <a href="{{ route('events.show', $event->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan
                                        
                                        @can('events.edit')
                                        <td width='10px'>
                                            <a href="{{route('events.edit', $event->id)}}" class="btn btn-secondary"> Editar</a>
                                        </td>
                                        @endcan

                                        @can('events.destroy')
                                        <td width='10px'>
                                            @if ($event->state )
                                                <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteEvent{{$event->id}}">Desactivar</a>
                                            @else
                                                <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activeEvent{{$event->id}}">Activar</a>
                                            @endif
                                            <!--Modal-->
                                            <div class="modal fade" id="deleteEvent{{$event->id}}" tabindex="-1" role="dialog" aria-labelledby="elimarEvento" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar desactivación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5 class="text-center font-weight-bolder">¿Está seguro de desactivar el evento: {{ $event->title }} ?</h5>
                                                        <small class="text-muted"><strong>Recuerda: </strong> una vez desactivado el evento: {{ $event->title }}, no podrá ser visualizado en la aplicación móvil</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Desactivar</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="activeEvent{{$event->id}}" tabindex="-1" role="dialog" aria-labelledby="activarEvento" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Confirmar activación</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5 class="text-center font-weight-bolder">¿Está seguro de activar el evento: {{ $event->title }} ?</h5>
                                                        <small class="text-muted"><strong>Recuerda: </strong> una vez activado el evento: {{ $event->title }}, podrá ser visualizado nuevamente en la aplicación móvil</small>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('events.destroy', $event->id) }}" method="POST">
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
                            <p class="text-center">Nigún servicio público registrado</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$events->total()}}</p>
                <nav>
                    {{$events->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection