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
       <p>Listar eventos</p>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Servicios públicos</h4>
                        {{-- @can('publicServices.create') --}}
                        <a href="{{route('events.create')}}" class="btn btn-primary float-right">Nuevo</a>
                        {{-- @endcan --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($events)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width='10px'>Id</th>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th>Estado</th>
                                    {{-- @canany(['publicServices.edit','publicServices.destroy']) --}}
                                    <th>Opciones</th>
                                    {{-- @endcanany --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ $event->id}}</td>
                                        <td>{{ $event->title}}</td>
                                        <td>{{ $event->description ?: 'Sin descripción'}}</td>
                                        <td>{{ $event->subcategory->name }}</td>
                                        <td>{{ $event->state ? 'Activo': 'Inactivo'}}</td>

                                        {{-- @can('publicServices.show') --}}
                                        <td width='10px'>
                                            <a href="{{ route('events.show', $event->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        {{-- @endcan --}}
                                        
                                        {{-- @can('publicServices.edit') --}}
                                        <td width='10px'>
                                            <a href="{{route('events.edit', $event->id)}}" class="btn btn-secondary"> Editar</a>
                                        </td>
                                        {{-- @endcan --}}

                                        {{-- @can('publicServices.destroy') --}}
                                        <td width='10px'>
                                            @if ($event->state )
                                                <a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#deleteEvent{{$event->id}}">Eliminar</a>
                                            @else
                                                <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#activeEvent{{$event->id}}">Activar</a>
                                            @endif
                                            <!--Modal-->
                                            <div class="modal fade" id="deleteEvent{{$event->id}}" tabindex="-1" role="dialog" aria-labelledby="elimarEvento" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de eliminar el evento {{ $event->title }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
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
                                                            ¿Está seguro de activar el evento {{ $event->title }}?
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
                                        {{-- @endcan --}}

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
                    {{$events->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection