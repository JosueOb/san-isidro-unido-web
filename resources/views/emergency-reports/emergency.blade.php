@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Solicitud
@endsection
@section('page-header')
    Emergencia
@endsection

@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>

{{-- Se muestra la información del policía que aprobó o rechazó el reporte de emergencia, cuando el estado del problema sea diferente a pendiente --}}
@if ($emergency_status_attendance !== 'pendiente')
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class='text-uppercase font-weight-bolder text-center'>{{ $emergency_status_attendance }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="text-uppercase text-center font-weight-bolder">Policía</h5>
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
        <div class="card card-primary" id='emergency-show'>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4  class="d-inline">Detalle de la emergencia reportada</h4>
                    </div>
                    <div class="col">
                        @can('emergencyReport.publish')
                        <div class="row">
                            @if ($emergency_status_attendance === 'atendido' && !$emergency->state)
                            <div class="col">
                                <button type="button" class="btn btn-secondary float-right" data-toggle="modal" data-target="#publishEmergencyModal">
                                    <i class="fas fa-check-circle"></i> Publicar
                                </button>
                            </div>
                            @endif
                            @if ($emergency->state)
                                <div class="col text-center" style="color:green;">
                                    <i class="fas fa-thumbs-up"></i>
                                    <h5 class="d-inline text-uppercase font-weight-bolder">Publicado</h5>
                                </div>
                            @endif
                        </div>
                        @endcan
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

<!-- Modal -->
<div class="modal fade" id="publishEmergencyModal" tabindex="-1" role="dialog" aria-labelledby="publishEmergencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="publishEmergencyModalLabel">Confirmación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <h5 class="text-center font-weight-bolder">¿Estas seguro de publicar la emergencia?</h5>
            <small class="text-muted"><strong>Recuerda: </strong>la emergencia será publicada en la aplicación móvil y no podrás revertir la acción.</small>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <a href="{{route('emergencyReport.publish', $notification->id)}}" class="btn btn-success float-right"><i class="fas fa-check-circle"></i> Publicar</a> 
        </div>
      </div>
    </div>
</div>
@endsection