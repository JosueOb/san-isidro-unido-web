@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Servicios Públicos
@endsection
@section('page-header')
    Registrar una nueva categoría
@endsection
@section('item-category')
    active
@endsection
@section('item-category-collapse')
    show
@endsection
@section('item-category-create')
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
                <form action="{{route('categories.store')}}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" maxlength="25" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción <span class="text-muted">(opcional)</span></label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" maxlength="255">{{ old('description')}}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="icon">Icono <span class="text-muted">(opcional)</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/png, .jpeg, .jpg">
                                    <label class="custom-file-label" id='iconLabel' for="icon" data-browse="Agregar"></label>
                                    <span class="invalid-feedback" role="alert">
                                    
                                    </span>
                                </div>
                                <small id="iconHelp" class="form-text text-muted">
                                        Puedes subir una imágen en formarto png, jpeg y jpg
                                </small>

                                <div class="gallery-images" id="gallery-images">
                                    {{-- Se presentan las imágenes seleccionadas por el usuario --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-4 offset-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            Guardar
                            <i class="far fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection