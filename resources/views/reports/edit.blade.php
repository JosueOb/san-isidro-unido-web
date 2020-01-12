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

    <form class="row" id="report-update" enctype="multipart/form-data" action="{{route('reports.update', $report->id)}}">
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
                        <input id="title" type="text" class="form-control" name="title" value="{{ old('title') ?: $report->title}}" maxlength="255" required autofocus>
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
                        <button type="submit" class="btn btn-primary btn-block" id="send-data">
                            Actualizar
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
                            <input type="file" class="custom-file-input" id="inputImages" name="images[]" accept="image/png, .jpeg, .jpg"  multiple>
                            <label class="custom-file-label" id='imagesLabel' for="images" data-browse="Agregar"></label>
                            <span class="invalid-feedback" role="alert">
                            
                            </span>
                        </div>
                        <small id="imagesHelp" class="form-text text-muted">
                                Puedes subir hasta 5 imágenes de 1MB cada una
                        </small>

                        <div class="gallery-images" id="gallery-update">
                            {{-- Se presentan las imágenes seleccionadas por el usuario --}}
                            @if ($images)
                                @foreach ($images as $image)
                                <div class="gallery-item">
                                    <div class="image-cancel"><i class="fas fa-trash-alt"></i></div>
                                    <img src={{$image->getLink()}} alt='image_{{$image->id}}' data-image="{{$image->url}}">
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label for="document">Documento <span class="text-muted">(opcional)</span></label>
                        <div class="custom-file">

                            <input type="file" class="custom-file-input" id="inputDocument" name="document[]" accept=".pdf">
                            <label class="custom-file-label" id='imagesLabel' for="document" data-browse="Agregar"></label>
                            <span class="invalid-feedback" role="alert">
                                
                            </span>
                        </div>
                        <small id="documentHelp" class="form-text text-muted">
                            Puedes adjuntar un documento PDF máximo de 5MB
                        </small>
                        <div class="gallery-document" id="gallery-document-update">
                            {{-- Se presentan el documento seleccionado por el usuario --}}
                            @if ($resource)
                                @foreach ($resource as $document)
                                <div class="gallery-item">
                                    {{-- <div class="image-cancel"><i class="fas fa-trash-alt"></i></div>
                                    <img src={{$document->getLink()}} alt='image_{{$document->id}}' data-image="{{$image->url}}"> --}}

                                    <i class="fas fa-file-pdf image-document"></i>
                                    {{-- <p class="document-name">{{$document->getLink()}}</p> --}}
                                    <a href="{{$document->getLink()}}" class="link-document" target="_blank" data-document="{{$document->url}}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <i class="fas fa-trash-alt image-cancel" id="delete_old_document"></i>

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