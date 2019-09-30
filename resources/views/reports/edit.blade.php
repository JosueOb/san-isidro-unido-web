@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Informes
@endsection
@section('page-header')
    Editar informe
@endsection
@section('item-repot')
    active
@endsection
@section('item-report-collapse')
    show
@endsection
@section('item-report-list')
    active
@endsection
@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>

    <form class="row" id="report-update" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="col-12 col-sm-7 col-md-7 col-lg-8">
            <div class="card card-primary">
                {{-- <div class="card-header">
                    <h4>Informe</h4>
                </div> --}}
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Título</label>
                        {{-- <input id="title" type="text" class="form-control  @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" maxlength="45" required autofocus> --}}
                        <input id="title" type="text" class="form-control" name="title" value="{{ old('title') ?: $report->title}}" maxlength="45" required autofocus>
                        <span class="invalid-feedback" role="alert">

                        </span>

                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea id="description" class="form-control" name="description" rows="12" maxlength="255" required>{{ old('description') ?: $report->description}}</textarea>
                        <span class="invalid-feedback" role="alert">
                            
                        </span>
                        {{-- @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror --}}
                    </div>
                    <div class="form-group col-6 offset-3">
                        <button type="submit" class="btn btn-primary btn-block">
                            Publicar
                            <i class="far fa-save"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-5 col-md-5 col-lg-4">
            <div class="card card-primary">
                {{-- <div class="card-header">
                    <h4>Opcional</h4>
                </div> --}}
                <div class="card-body">
                    <div class="form-group">
                        <label for="images">Imágenes <span class="text-muted">(opcional)</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('images') is-invalid @enderror" id="inputImages" name="images[]" accept="image/jpeg,image/png"  multiple>
                            <label class="custom-file-label" id='imagesLabel' for="images" data-browse="Agregar"></label>
                            <span class="invalid-feedback" role="alert">
                            
                            </span>
                        </div>
                        <small id="imagesHelp" class="form-text text-muted">
                                Puedes subir hasta 5 imágenes de 1MB cada una
                        </small>

                        <div class="gallery" id="gallery-update">
                            {{-- Se presentan las imágenes seleccionadas por el usuario --}}
                            @if ($images)
                                @foreach ($images as $image)
                                <div class="gallery-item">
                                    <div class="image-cancel" data-no="0"><i class="fas fa-trash-alt"></i></div>
                                    <img src={{$image->getImageLink()}} alt='image_{{$image->id}}' data-image="{{$image->url}}">
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </form>
@endsection