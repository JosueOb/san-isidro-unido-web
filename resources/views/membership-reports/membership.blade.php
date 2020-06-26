@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo notificaciones
@endsection
@section('page-header')
    Solicitud de afiliación
@endsection

@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>
{{-- Se muestra la información de quíen aprobó o rechazó la solicitud si no está en un estado de pendiente --}}
@if ($membership->status_attendance !== 'pendiente')
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class='text-uppercase font-weight-bolder text-center'>{{ $membership->status_attendance }}</h4>
            </div>
            <div class="card-body">
                @if ($membership->status_attendance === 'aprobado')
                <div class="row">
                    <div class="col">
                        <p><strong>Apellidos:</strong> {{ $membership->responsible['approved']['who']['last_name'] }} </p>
                    </div>
                    <div class="col">
                        <p><strong>Nombre:</strong> {{ $membership->responsible['approved']['who']['first_name'] }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Fecha:</strong> {{ $membership->responsible['approved']['date'] }} </p>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col">
                        <p><strong>Apellidos:</strong> {{ $membership->responsible['rechazed']['who']['last_name'] }}</p>
                    </div>
                    <div class="col">
                        <p><strong>Nombre:</strong> {{ $membership->responsible['rechazed']['who']['first_name'] }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><strong>Fecha:</strong> {{ $membership->responsible['rechazed']['date'] }} </p>
                        <p><strong>Observación:</strong> {{ $membership->responsible['rechazed']['reason'] }} </p>
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
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4  class="d-inline">Detalle de solicitud</h4>
                    </div>
                    <div class="col">
                        {{-- @can('socialProblemReports.approveOReject') --}}
                        <div class="row">
                            {{-- Se muestra las acciones de aprobar o rechazar si la solicitusd está en un estado de pendiente --}}
                            @if ($membership->status_attendance === 'pendiente')
                            <div class="col">
                                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#approveMembershipModal">
                                    <i class="fas fa-check-circle"></i> Aprobar
                                </button>
                            </div>
                            <div class="col">
                                {{-- <a href="#" class="btn btn-danger float-right float-md-left"><i class="fas fa-times-circle"></i> Rechazar</a> --}}
                                <a href="{{route('membership.showReject', $notification->id)}}" class="btn btn-danger float-right float-md-left"><i class="fas fa-times-circle"></i> Rechazar</a>
                            </div>
                            @endif
                        </div>
                        {{-- @endcan --}}
                    </div>
                </div>
            </div>
            <div class="card-body">

                {{-- <div class="row">
                    <div class="col">
                        @if ($userWhoApprovedMembership)
                        <h4 class="text-center text-uppercase font-weight-bolder text-success">{{$guest->membership['status_attendance']}}</h4>
                        <p><strong class="text-capitalize">Moderador: </strong>{{$userWhoApprovedMembership->getFullName()}}</p>
                        <p><strong>Fecha: </strong>{{$guest->membership['approved']['date']}}</p>
                        @endif
                        <br>
                    </div>
                </div> --}}


                <h5 class="text-uppercase font-weight-bolder text-center">Solicitante</h5>
                <div class="row">
                    <div class="col">
                        <p><strong>Nombre:</strong> {{ $guest->first_name }}</p>
                        <p><strong>Apellidos:</strong> {{ $guest->last_name }}</p>
                        <p><strong>Corre electrónico:</strong> {{ $guest->email }}</p>
                        {{-- <p><strong>Estado:</strong> {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'Activo': 'Inactivo'}}</p> --}}
                        <p><strong>Número telefónico:</strong> {{$guest->number_phone }}</p>
                        {{-- <p><strong>Corre verificado:</strong> {{$guest->email_verified_at ?: 'No verificado'}}</p> --}}


                    </div>
                    <div class="col text-center">
                        <div style="">
                            <img class="" style="width: 8rem; height: 8rem; object-fit: cover; border-radius: 50%;" src="{{ $guest->avatar }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="text-uppercase font-weight-bolder text-center">Solicitud</h5>
                        <p><strong>Cédula:</strong> {{ $membership->identity_card }}</p>
                        <p><strong>Factura de sevicio básico</strong> </p>
                        <small class="text-muted ">
                            <strong>Recuerda:</strong> puedes seleccionar la imagen para verla de tamaño completo
                        </small>
                        <a href="{{ $membership->basic_service_image }}" target="_blank" style="display:block ;text-align: center" class="mt-3">
                            <img src="{{ $membership->basic_service_image }}" alt="" style="width: 15rem; height: 15rem; object-fit: cover; border-radius: 10px">
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="approveMembershipModal" tabindex="-1" role="dialog" aria-labelledby="approveMembershipModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="approveMembershipModalLabel">Confirmación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <h5 class="text-center font-weight-bolder">¿Está seguro de aprobar al usuario?</h5>
            <small class="text-muted"><strong>Recuerda: </strong>una vez aprobado, el usuario puede ingresar a la aplicación móvil con el rol de morador</small>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <a href="{{route('membership.approve', $notification->id)}}" class="btn btn-success float-right"><i class="fas fa-check-circle"></i> Aprobar</a> 
        </div>
      </div>
    </div>
</div>
@endsection