@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Solicitud
@endsection
@section('page-header')
    Solicitud de Emergencia
@endsection

@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card card-primary" id='emergency-show'>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4  class="d-inline">Detalle de la emergencia</h4>
                    </div>
                    <div class="col">
                        @can('emergencyReport.publishEmergency')
                        <div class="row">
                            @if ($userWhoAttendedEmergency)
                                @if ($emergency->state)
                                    <div class="col text-center" style="color:green;">
                                        <i class="fas fa-thumbs-up"></i>
                                        <h5 class="d-inline text-uppercase font-weight-bolder">Publicado</h5>
                                    </div>
                                @else
                                <div class="col">
                                    <button type="button" class="btn btn-secondary float-right" data-toggle="modal" data-target="#publishEmergencyModal">
                                        <i class="fas fa-check-circle"></i> Publicar
                                    </button>
                                </div>
                                @endif
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

                        <h4 class="text-center text-uppercase font-weight-bolder text-info">{{$emergency->additional_data['status_attendance']}}</h4>


                        @if ($userWhoAttendedEmergency)
                        {{-- <h4 class="text-center text-uppercase font-weight-bolder text-success">Aprobado</h4> --}}
                        <p><strong class="text-capitalize">Aprobado por: </strong>{{$userWhoAttendedEmergency->getFullName()}}</p>
                        <p><strong>Fecha: </strong>{{$additionalData['approved']['date']}}</p>
                        @endif

                        @if ($userWhoRechazedEmergency)
                        {{-- <h4 class="text-center text-uppercase font-weight-bolder text-danger">Rechazado</h4> --}}
                        <p><strong>Rechazado por: </strong>{{$userWhoRechazedEmergency->getFullName()}}</p>
                        <p><strong>Fecha: </strong>{{$additionalData['rechazed']['date']}}</p>
                        <p class="text-lowercase"><strong class="text-capitalize">Razón: </strong>{{$additionalData['rechazed']['reason']}}</p>
                        @endif
                        
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
          {{-- <button type="button" class="btn btn-success">Publicar</button> --}}
          <a href="{{route('emergencyReport.publishEmergency', [$emergency->id, $notification->id])}}" class="btn btn-success float-right"><i class="fas fa-check-circle"></i> Publicar</a> 
        </div>
      </div>
    </div>
</div>
@endsection