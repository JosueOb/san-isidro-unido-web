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
                            {{-- @if (!$userWhoApprovedProblem && !$userWhoRechazedProblem) --}}
                            <div class="col">
                                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#rejectSocialProblemModal">
                                    <i class="fas fa-check-circle"></i> Aprobar
                                </button>
                            </div>
                            <div class="col">
                                <a href="#" class="btn btn-danger float-right float-md-left"><i class="fas fa-times-circle"></i> Rechazar</a>
                            </div>
                            {{-- @endif --}}
                        </div>
                        {{-- @endcan --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <p><strong>Nombre:</strong> {{$guest->first_name}}</p>
                        <p><strong>Apellidos:</strong> {{$guest->last_name}}</p>
                        <p><strong>Corre electrónico:</strong> {{$guest->email}}</p>
                        {{-- <p><strong>Estado:</strong> {{$neighbor->getRelationshipStateRolesUsers('morador') ? 'Activo': 'Inactivo'}}</p> --}}
                        <p><strong>Número telefónico:</strong> {{$guest->number_phone ?: 'No registrado'}}</p>
                        {{-- <p><strong>Corre verificado:</strong> {{$guest->email_verified_at ?: 'No verificado'}}</p> --}}


                    </div>
                    <div class="col text-center">
                        <div style="">
                            <img class="" style="width: 8rem; height: 8rem; object-fit: cover; border-radius: 50%;" src="{{$guest->getAvatar()}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        
                        <p><strong>Cédula:</strong> {{$guest->membership['identity_card']}}</p>
                        <p><strong>Factura de sevicio básico</strong> </p>
                        <small class="text-muted "> <strong>Recuerda:</strong> que puede seleccionar la imágen para verla de tamaño completo</small>
                        <a href="{{$guest->membership['basic_service_image']}}" target="_blank" style="display:block ;text-align: center" class="mt-3">
                            <img src="{{$guest->membership['basic_service_image']}}" alt="" style="width: 15rem; height: 15rem; object-fit: cover; border-radius: 10px">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="rejectSocialProblemModal" tabindex="-1" role="dialog" aria-labelledby="rejectSocialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rejectSocialModalLabel">Confirmación</h5>
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
          {{-- <button type="button" class="btn btn-success">Aprobar</button> --}}
          <a href="#" class="btn btn-success float-right"><i class="fas fa-check-circle"></i> Aprobar</a> 
        </div>
      </div>
    </div>
</div>
@endsection