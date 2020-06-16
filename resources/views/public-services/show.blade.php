
@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Lugar registrado
@endsection
@section('item-public-service')
    active
@endsection
@section('item-public-service-collapse')
    show
@endsection
@section('item-public-service-list')
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
        <div class="card card-primary" id='public-service-show'>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4  class="d-inline">Detalle de servicio público</h4>

                        @can('publicServices.edit')
                        <a href="{{route('publicServices.edit', $publicService->id)}}" class="btn btn-primary float-right"><i class="far fa-edit"></i> Editar</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <p><strong>Nombre:</strong> {{$publicService->name}}</p>
                        <p><strong>Categoría:</strong> {{ strtolower($publicService->subcategory->name)}}</p>
                        <p><strong>Hora de apertura:</strong> {{$publicOpening['open_time']}}</p>
                        <p><strong>Hora de cierre:</strong> {{$publicOpening['close_time'] ?: 'no definida'}}</p>
                        
                        <p><strong>Teléfonos:</strong><br>
                            @if (count($publicService->phones) > 0)
                                @foreach ($publicService->phones as $phone)
                                    {{$phone->phone_number}}<br>
                                @endforeach
                            @else
                                <p>Ningún teléfono registrado</p>
                            @endif
                        </p>
                        <p><strong>Corre electrónico:</strong> {{$publicService->email ?: 'sin correo electrónico'}}</p>
                        <p><strong>Detalle:</strong> 
                        {{
                            isset($ubication['description']) ? $ubication['description'] : 'sin detalle de ubicación'
                        }}</p>
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
            </div>
        </div>
    </div>
</div>
@endsection