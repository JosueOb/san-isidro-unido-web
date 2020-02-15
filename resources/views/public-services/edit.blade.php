@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Actualizar servicio público
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
        <div class="card card-primary">
            <div class="card-body">

                <form id="public-service-update" action="{{route('publicServices.update', $publicService->id)}}">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-12 col-md-6">

                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?: $publicService->name}}" maxlength="45" required autofocus>
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Descripción <span class="text-muted">(opcional)</span></label>
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') ?: $publicService->description}}" maxlength="255">
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="subcategory">Categoría </label>

                                @if (count($subcategories) > 0)

                                <select class="form-control @error('subcategory') is-invalid @enderror" id="subcategory" name="subcategory" required>
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{$subcategory->id}}" {{$publicService->subcategory->id == $subcategory->id ? 'selected':''}}>{{$subcategory->name}}</option>
                                    @endforeach
                                </select>
                                <small id="subcategoryHelp" class="form-text text-muted">
                                    Indica la categoría a la que pertenece el servicio público
                                </small>

                                @else

                                <select class="form-control" id="subcategory" name="subcategory" required disabled>
                                </select>
                                <p class="text-danger">No existen registos de categorías, porfavor comuniquese con el administrador</p>

                                @endif
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="phone_numbers">Teléfono(s)</label>
                                
                                <div class="input-group">
                                   
                                    <div class="input-group-prepend" id='phone_group'>
                                        @if ($phones)
                                            @foreach ($phones as $phone)
                                            <div  id='phone_item'>
                                                <span class="input-group-text" id='phone_bagde'>
                                                    {{$phone->phone_number}}
                                                    <i class="fas fa-minus-circle" id='delete_phone'></i>
                                                </span>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <input id="phone_numbers" type="text" class="form-control @error('phone_numbers') is-invalid @enderror" name="phone_numbers" value="{{ old('phone_numbers')}}" maxlength="10" required pattern="(09)[0-9]{8}|(02)[0-9]{7}" title="Teléfono no válido" autocomplete="off">
                                </div>
                                
                                <small id="phone_numbersHelp" class="form-text text-muted">
                                    Recuerda anteponer el 09 al ingresar un número móvil o 02 al ser un número fijo, puedes ingresar hasta 3 teléfonos
                                </small>
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="email">Correo electrónico <span class="text-muted">(opcional)</span></label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?: $publicService->email}}">
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="ubication-description">Detalle <span class="text-muted">(opcional)</span></label>
                                <input id="ubication-description" type="text" class="form-control" name="ubication-description" value="{{ old('ubication-description') ?: $ubication['description']}}" maxlength="255">
                                <small id="categoryHelp" class="form-text text-muted">
                                    Puedes agregar detalles sobre la ubicación
                                </small>
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group mt-md-5 mt-lg-5">
                                <label for="ubication">Ubicación</label>
                                <span class="invalid-feedback" role="alert">
                                </span>
                                <div id="map" class="map">
                                    <div id="info" class="info text-muted">
                                        Latitud:  <span id='lat'>{{$ubication['lat']}}</span><br>
                                        Longitud: <span id='lng'>{{$ubication['lng']}}</span><br>
                                        Dirección: <span id='address'>{{$ubication['address']}}</span><br>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-4 offset-4">
                        <button type="submit" class="btn btn-primary btn-block" id="send-data">
                            Registrar
                            <i class="far fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection