@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Registrar un nuevo lugar
@endsection
@section('item-public-service')
    active
@endsection
@section('item-public-service-collapse')
    show
@endsection
@section('item-public-service-create')
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
                {{-- <form action="{{route('publicSercives.store')}}" method="POST"> --}}
                <form method="POST" id="public-service-create">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">

                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" maxlength="60" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description')}}" maxlength="255" required>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category">Categoría </label>
                                
                                <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="farmacia">Farmacia</option>
                                    <option value="ferretería">Ferretería</option>
                                    <option value="tienda">Tienda</option>
                                    {{-- <option value="one-person" {{old('category')=='one-person' ? 'selected':''}}>Para una persona</option>
                                    <option value="several-people"  {{old('category')=='several-people' ? 'selected':''}}>Para varias personas</option> --}}
                                </select>
                                <small id="categoryHelp" class="form-text text-muted">
                                    Indica la categoría a la que pertenece el servicio público
                                </small>
                                @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone_numbers">Teléfonos</label>
                                <input id="phone_numbers" type="tel" class="form-control @error('phone_numbers') is-invalid @enderror" name="phone_numbers" value="{{ old('phone_numbers')}}" maxlength="255" required>
                                @error('phone_numbers')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ubication-description">Detalle (opcional)</label>
                                <input id="ubication-description" type="text" class="form-control @error('ubication-description') is-invalid @enderror" name="ubication-description" value="{{ old('ubication-description')}}" maxlength="255">
                                <small id="categoryHelp" class="form-text text-muted">
                                    Puedes agregar detalles sobre la ubicación
                                </small>
                                @error('ubication-description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="ubication">Ubicación</label>
                                <div id="map" class="map">
                                    <span id="info" class="info"></span>
                                    <p id="ubicacion_seleccionada" class="info text-muted">No tengo ubicación seleccionada</p>
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <input id="ubication" type="text" class="form-control" name="ubication">
                                <input id="latitude" type="text" class="form-control" name="latitude">
                                <input id="longitude" type="text" class="form-control" name="longitude">
                                <input id="ubication-description" type="text" class="form-control" name="ubication-description">
                            </div> --}}
                           
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