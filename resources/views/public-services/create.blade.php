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
                                <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" maxlength="45" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Descripción (opcional)</label>
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description')}}" maxlength="255">
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category">Categoría </label>
                                

                                @if (count($categories)>0)

                                <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}" {{old('category')==$category->id ? 'selected':''}}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <small id="categoryHelp" class="form-text text-muted">
                                    Indica la categoría a la que pertenece el servicio público
                                </small>
                                @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                 
                            @else
                                <p class="text-danger">No existen registos de categorías, porfavor comuniquese con el administrador</p>
                                
                            @endif

                            </div>

                            <div class="form-group">
                                <label for="phone_numbers">Teléfonos</label>
                                
                                <div class="input-group">
                                    <div class="input-group-prepend" id='phone_group'>
                                    </div>
                                    <input id="phone_numbers" type="text" class="form-control @error('phone_numbers') is-invalid @enderror" name="phone_numbers" value="{{ old('phone_numbers')}}" maxlength="10" required pattern="(09)[0-9]{8}|(02)[0-9]{7}" title="Teléfono no válido" autocomplete="off">
                                </div>
                                
                                <small id="phone_numbersHelp" class="form-text text-muted">
                                    Recuerda anteponer el 09 al ingresar un número móvil o 02 al ser un número fijo, puedes ingresar hasta 3 teléfonos
                                </small>
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