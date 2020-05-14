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
                
                <form method="POST" id="public-service-create">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">

                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" maxlength="45" required autofocus>
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>
                            
                            {{-- <div class="form-group">
                                <label for="description">Descripción <span class="text-muted">(opcional)</span></label>
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description')}}" maxlength="255">
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div> --}}

                            <div class="form-group">
                                <label for="subcategory">Categoría </label>

                                @if (count($subcategories) > 0)

                                <select class="form-control @error('subcategory') is-invalid @enderror" id="subcategory" name="subcategory" required>
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{$subcategory->id}}" {{old('subcategory')==$subcategory->id ? 'selected':''}}>{{$subcategory->name}}</option>
                                    @endforeach
                                </select>
                                <small id="subcategoryHelp" class="form-text text-muted">
                                    Indica la categoría a la que pertenece el servicio público
                                </small>

                                @else

                                <select class="form-control" id="subcategory" name="subcategory" required disabled>
                                </select>
                                <p class="text-danger">No existen registos de categorías, por favor comuniquese con el administrador</p>

                                @endif
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 col-lg-6">
                                    <label for="open-time">Hora de apertura</label>
                                    <input id="open-time" type="time" class="form-control" name="open-time" value="{{ old('open-time') ?: "08:00"}}" placeholder="Apertura" required>
                                    <span class="invalid-feedback" role="alert">
                                    </span>
                                </div>
                                <div class="form-group col-md-12 col-lg-6 mt-md-0 mt-sm-3 mt-3">
                                    <label for="close-time">Hora de cierre</label>
                                    <input id="close-time" type="text" class="form-control" name="close-time" value="{{ old('close-time') ?: "16:00"}}" placeholder="Cierre" required>
                                    <span class="invalid-feedback" role="alert">
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone_numbers">Teléfono(s)</label>
                                
                                <div class="input-group">
                                    <div id='phone_group' class="input-group-prepend">
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
                                <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="ubication-description">Referencia <span class="text-muted">(opcional)</span></label>
                                <input id="ubication-description" type="text" class="form-control @error('ubication-description') is-invalid @enderror" name="ubication-description" value="{{ old('ubication-description')}}" maxlength="255">
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