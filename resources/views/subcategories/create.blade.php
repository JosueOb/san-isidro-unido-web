@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Subcategoría
@endsection
@section('page-header')
    Registrar subcategoría
@endsection
@section('item-subcategory')
    active
@endsection
@section('item-subcategory-collapse')
    show
@endsection
@section('item-subcategory-create')
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
                <form action="{{route('subcategories.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
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
                                <label for="description">Descripción <span class="text-muted">(opcional)</span></label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" maxlength="255">{{ old('description') }}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="category">Categoría</label>
    
                                @if (count($categories)>0)
                                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Seleccione una categoría</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}" {{old('category')==$category->id ? 'selected':''}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                @else
                                    <p class="text-danger">No existen registros de categorías</p>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="icon">Icono <span class="text-muted">(opcional)</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/png, .jpeg, .jpg">
                                    <label class="custom-file-label" id='iconLabel' for="icon" data-browse="Cambiar"></label>
                                    
                                    @if ($errors->get('icon'))
                                    {{-- Se presenta el error por parte de laravel --}}
                                        @error('icon')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @else
                                        <span class="invalid-feedback" role="alert">
                                        </span>
                                    @endif
                                    
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