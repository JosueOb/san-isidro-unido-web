@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Problema Social
@endsection
@section('page-header')
    Problema Social reportado
@endsection
@section('item-problem')
    active
@endsection
@section('item-problem-collapse')
    show
@endsection
@section('item-problem-list')
    active
@endsection

@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>

{{-- Se muestra la información del moderador que aprobó o rechazó el reporte de problema social, cuando el estado del problema sea diferente a pendiente --}}
@if ($emergency_status_attendance !== 'pendiente')
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class='text-uppercase font-weight-bolder float'>{{ $emergency_status_attendance }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="text-uppercase text-center font-weight-bolder">
                            Policía
                        </h5>
                    </div>
                </div>
                @if ($emergency_status_attendance === 'atendido')

                <div class="row">
                    <div class="col">
                        <p><strong>Apellidos:</strong> {{ $emergency->additional_data['attended']['who']['last_name'] }} </p>
                    </div>
                    <div class="col">
                        <p><strong>Nombre:</strong> {{ $emergency->additional_data['attended']['who']['first_name'] }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Fecha:</strong> {{ $emergency->additional_data['attended']['date'] }} </p>
                    </div>
                </div>
                @endif

                @if ($emergency_status_attendance === 'rechazado')
                
                <div class="row">
                    <div class="col">
                        <p><strong>Apellidos:</strong> {{ $emergency->additional_data['rechazed']['who']['last_name'] }}</p>
                    </div>
                    <div class="col">
                        <p><strong>Nombre:</strong> {{ $emergency->additional_data['rechazed']['who']['first_name'] }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Fecha:</strong> {{ $emergency->additional_data['rechazed']['date'] }} </p>
                        <p><strong>Observación:</strong> {{ $emergency->additional_data['rechazed']['reason'] }} </p>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endif


<div class="row">
    <div class="col">
        <div class="card card-primary" id='social-problem-show'>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4  class="d-inline">Detalle de la emergencia reportada</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <p><strong>Título:</strong> {{$emergency->title}}</p>
                        <p><strong>Descripción:</strong> {{$emergency->description ?: 'sin descripción'}}</p>
                        <p><strong>Reportado por:</strong> {{ $neighbor->getFullName() }}</p>
                        <p><strong>Fecha:</strong> {{ $emergency->created_at }}</p>
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
                        <small class="text-muted "> <strong>Recuerda:</strong> que puede seleccionar la imágen para verla de tamaño completo</small>
                        <div class="gallery-images">
                            {{-- Se presentan las imágenes seleccionadas por el usuario --}}
                            @foreach ($images as $image)
                            <div class="gallery-item">
                                <img src={{$image->getLink()}} alt='image_report_{{$image->id}}'>
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