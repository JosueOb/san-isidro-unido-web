@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Solicitud
@endsection
@section('page-header')
    Rechazo de afiliación
@endsection

@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>

<div class="row">
    <div class="col">
        <form action="{{route('membership.reject', $notification->id)}}" method="POST">
            @csrf
            <div class="card card-primary">
                <div class="card-body">
                    <h5 class="text-center">¿Está seguro seguro de rechazar la solicitud de afiliación?</h5>
                    <div class="form-group">
                        <label for="description">Si estás seguro, describe a continuación la razón del rechazo, caso contrario puedes seleccionar el botón cancelar</label>
                        <textarea id="description" class="form-control" name="description" rows="5" maxlength="255"  required>{{ old('description') }}</textarea>

                        @error('description')
                        <span class="invalid-feedback d-inline-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <small class="text-muted"><strong>Recuerda: </strong>
                            <ul>
                                <li>Las solicitudes rechazadas no permiten al solicitante utilizar las funcionalidades de reportar problema social y emergencia en la aplicación móvil</li>
                                <li>Puedes regresar a visualizar la solicitud, seleccionando el botón cancelar</li>
                            </ul>
                        </small>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-danger float-right">
                                <i class="fas fa-check-circle"></i> Rechazar
                            </button>
                        </div>
                        <div class="col">
                            <a href="{{url()->previous()}}" class="btn btn-secondary float-left"><i class="fas fa-times-circle"></i> Cancelar</a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection