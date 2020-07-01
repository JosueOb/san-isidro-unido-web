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
@if ($social_problem_status_attendance !== 'pendiente')
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class='text-uppercase font-weight-bolder float'>{{ $social_problem_status_attendance }}</h4>
            </div>
            <div class="card-body">
                @if ($social_problem_status_attendance === 'aprobado')
                <div class="row">
                    <div class="col">
                        <h5 class="text-uppercase text-center font-weight-bolder">
                            @php
                                $roles = collect($social_problem->additional_data['approved']['who']['roles']);
                                $role = $roles->filter(function($roles, $key) use ($social_problem){
                                    return $social_problem->additional_data['approved']['who']['pivot']['role_id'] === $roles['id'];
                                });
                                $role = array_values($role->toArray());
                            @endphp
                            {{ $role[0]['name'] }}
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Apellidos:</strong> {{ $social_problem->additional_data['approved']['who']['last_name'] }} </p>
                    </div>
                    <div class="col">
                        <p><strong>Nombre:</strong> {{ $social_problem->additional_data['approved']['who']['first_name'] }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Fecha:</strong> {{ $social_problem->additional_data['approved']['date'] }} </p>
                    </div>
                </div>
                @endif

                @if ($social_problem_status_attendance === 'rechazado')
                <div class="row">
                    <div class="col">
                        <h5 class="text-uppercase text-center font-weight-bolder">
                        @php
                            $roles = collect($social_problem->additional_data['rechazed']['who']['roles']);
                            $role = $roles->filter(function($roles, $key) use ($social_problem){
                                return $social_problem->additional_data['rechazed']['who']['pivot']['role_id'] === $roles['id'];
                            });
                            $role = array_values($role->toArray());
                        @endphp
                        {{ $role[0]['name'] }}
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Apellidos:</strong> {{ $social_problem->additional_data['rechazed']['who']['last_name'] }}</p>
                    </div>
                    <div class="col">
                        <p><strong>Nombre:</strong> {{ $social_problem->additional_data['rechazed']['who']['first_name'] }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Fecha:</strong> {{ $social_problem->additional_data['rechazed']['date'] }} </p>
                        <p><strong>Observación:</strong> {{ $social_problem->additional_data['rechazed']['reason'] }} </p>
                    </div>
                </div>
                @endif

                @if ($social_problem_status_attendance === 'atendido')
                <div class="row">
                    <div class="col">
                        <h5 class="text-uppercase text-center font-weight-bolder">
                        @php
                            $roles = collect($social_problem->additional_data['attended']['who']['roles']);
                            $role = $roles->filter(function($roles, $key) use ($social_problem){
                                return $social_problem->additional_data['attended']['who']['pivot']['role_id'] === $roles['id'];
                            });
                            $role = array_values($role->toArray());
                        @endphp
                        {{ $role[0]['name'] }}
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Apellidos:</strong> {{ $social_problem->additional_data['attended']['who']['last_name'] }} </p>
                    </div>
                    <div class="col">
                        <p><strong>Nombre:</strong> {{ $social_problem->additional_data['attended']['who']['first_name'] }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Fecha:</strong> {{ $social_problem->additional_data['attended']['date'] }} </p>
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
                        <h4  class="d-inline">Detalle del problema social reportado</h4>
                    </div>
                    <div class="col">
                        @can('socialProblems.attendOrReject')
                        <div class="row">
                            {{-- Se muestra las acciones de atender o rechazar si el problema reportado esta en estado de aprobado por los moderadores --}}
                            @if ($social_problem->state && $social_problem_status_attendance === 'aprobado')
                            <div class="col">
                                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#attendSocialProblemModal">
                                    <i class="fas fa-check-circle"></i> Atender
                                </button>
                            </div>
                            <div class="col">
                                <a href="{{route('socialProblems.showReject', $social_problem->id)}}" class="btn btn-danger float-right float-md-left"><i class="fas fa-times-circle"></i> Rechazar</a>
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
                        <p><strong>Título:</strong> {{$social_problem->title}}</p>
                        <p><strong>Descripción:</strong> {{$social_problem->description ?: 'sin descripción'}}</p>
                        <p><strong>Categoría:</strong> {{ strtolower($social_problem->subcategory->name) }}</p>
                        <p><strong>Reportado por:</strong> {{ $neighbor->getFullName() }}</p>
                        <p><strong>Fecha:</strong> {{ $social_problem->created_at }}</p>
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

<!-- Modal -->
<div class="modal fade" id="attendSocialProblemModal" tabindex="-1" role="dialog" aria-labelledby="attendSocialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="attendSocialModalLabel">Confirmación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <h5 class="text-center font-weight-bolder">¿Está seguro de cambiar el estado del problema social a atendido?</h5>
            <small class="text-muted"><strong>Recuerda: </strong>no se puede revertir este cambio de estado una vez realizado, el problema social queda registrado como resuleto y los usuarios moradores de la aplicación móvil lo pueden listar y visualizar como problema resuelto</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <a href="{{route('socialProblems.attend', $social_problem->id )}}" class="btn btn-success float-right"><i class="fas fa-check-circle"></i> Atender</a> 
        </div>
      </div>
    </div>
</div>
@endsection