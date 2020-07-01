@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Eventos
@endsection
@section('page-header')
    Evento registrado
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
        <div class="card card-primary" id='event-show'>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4  class="d-inline">Detalle de evento</h4>

                        @can('events.edit')
                        <a href="{{route('events.edit', $event->id)}}" class="btn btn-primary float-right"><i class="far fa-edit"></i> Editar</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <p><strong>Título:</strong> {{$event->title}}</p>
                        <p><strong>Descripción:</strong> {{$event->description ?: 'sin descripción'}}</p>
                        <p><strong>Categoría:</strong> {{ strtolower($event->subcategory->name)}}</p>
                        <p><strong>Estado:</strong> {{ $event->state ? 'activo': 'inactivo' }}</p>
                        <p><strong>Hora de inicio:</strong> {{$event_range_date['start_time']}}</p>
                        <p><strong>Hora de cierre:</strong> {{$event_range_date['end_time'] ?: 'no definida'}}</p>
                        <p><strong>Fecha de inicio:</strong> {{$event_range_date['start_date']}}</p>
                        <p><strong>Fecha de cierre:</strong> {{$event_range_date['end_date'] ?: 'no definida'}}</p>
                        <p><strong>Responsable:</strong> {{$event_responsible}}</p>
                        
                        <p><strong>Teléfonos del responsable:</strong><br>
                            @if (count($event->phones) > 0)
                                @foreach ($event->phones as $phone)
                                    {{$phone->phone_number}}<br>
                                @endforeach
                            @else
                                <p>Ningún teléfono registrado</p>
                            @endif
                        </p>
                        <p><strong>Referencia:</strong> {{$ubication['description'] ?: 'sin referencia de ubicación'}}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <p><strong>Ubicación</strong></p>
                        <div id="map" class="map">
                            <div id="info" class="info text-muted">
                                Latitud:  <span id='latitude'>{{$ubication['latitude']}}</span><br>
                                Longitud: <span id='longitude'>{{$ubication['longitude']}}</span><br>
                                Dirección: <span id='address'>{{$ubication['address']}}</span><br>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col">
                        @if (count($images) > 0)
                        <p><strong>Imágenes:</strong></p>
                        <small class="text-muted "> <strong>Recuerda:</strong> puedes seleccionar la imágen para verla de tamaño completo</small>
                        <div class="gallery-images">
                            {{-- Se presentan las imágenes seleccionadas por el usuario --}}
                            @foreach ($images as $image)
                            <div class="gallery-item">
                                <a href="{{$image->getLink()}}" target="_blank">
                                    <img src={{$image->getLink()}} alt='image_report_{{$image->id}}'>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection