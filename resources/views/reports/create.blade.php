@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Informes
@endsection
@section('page-header')
    Registrar informe
@endsection
@section('item-repot')
    active
@endsection
@section('item-report-collapse')
    show
@endsection
@section('item-report-create')
    active
@endsection
@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>

    <form class="row" action="#" method="POST">
        @csrf
        <div class="col-12 col-sm-7 col-md-7 col-lg-8">
            <div class="card card-primary">
                {{-- <div class="card-header">
                    <h4>Informe</h4>
                </div> --}}
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Título</label>
                        <input id="title" type="text" class="form-control  @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" maxlength="45" required autofocus>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="12" maxlength="255" required>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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
                            <input type="file" class="custom-file-input @error('images') is-invalid @enderror" id="images" name="images[]" accept="image/jpeg,image/png" onchange="previewImages()" multiple>
                            <label class="custom-file-label" id='imagesLabel' for="images" data-browse="Agregar"></label>
                        </div>
                        <small id="imagesHelp" class="form-text text-muted">
                                Puedes subir hasta 5 imágenes de 5MB cada una
                        </small>
                        @error('images')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="gallery" id="gallery">
                            {{-- Se presentan las imágenes seleccionadas por el usuario --}}
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </form>

@endsection
@section('scripts')
<script>
    function previewImages(){
    //Presentación de los files que obtiene el input
    var images = document.querySelector('#images').files;
    //console.log(images);
    var fileNames = [];
    //Se eliminan las imagenes seleccionadas anteriormente
    $('div.gallery-item').remove();

    function readAndPreview(file, no){
        if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
            var reader = new FileReader();

            reader.addEventListener("load", function () {
            
            var gallery_item = '<div class="gallery-item item-'+no+'">'+
                                    '<div class="image-cancel" data-no="'+no+'">'+
                                        '<i class="fas fa-trash-alt"></i>'+
                                    '</div>'+
                                    '<img src="'+ this.result+'">'+
                                '</div>';
            $('#gallery').append(gallery_item);
            }, false);

            reader.readAsDataURL(file);
        }
    }
    if (images.length > 0) {
        console.log(fileNames);
        for(let i = 0; i <images.length; i++){
            readAndPreview(images[i],i);
            fileNames.push(images[i].name);
        }
        // $('#images').siblings(".custom-file-label").addClass("selected").html(fileNames.join('; '));
        $("#imagesLabel").addClass("selected").html(fileNames.join('; '));
        console.log($('#images').siblings());
    }else{
        // $('#images').siblings(".custom-file-label").addClass("selected").html('Seleccionar archivos');
        $("#imagesLabel").addClass("selected").html('Seleccionar archivos');
    }
}
</script>

@endsection