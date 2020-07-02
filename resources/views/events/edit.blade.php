@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Eventos
@endsection
@section('page-header')
    Actualizar evento
@endsection
@section('item-event')
    active
@endsection
@section('item-event-collapse')
    show
@endsection
@section('item-event-list')
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
                <form id="event-update" action="{{route('events.update', $event->id)}}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="title">Título</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') ?: $event->title}}" maxlength="100" required autofocus>
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="description">Descripción <span class="text-muted">(opcional)</span></label>
                                <input id="description" type="text" class="form-control" name="description" value="{{ old('description') ?: $event->description}}" maxlength="255" required>
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="subcategory">Categoría </label>

                                @if (count($subcategories) > 0)

                                <select class="form-control" id="subcategory" name="id" required>
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($subcategories as $subcategory)
                                    <option value="{{$subcategory->id}}" {{$event->subcategory->id == $subcategory->id ? 'selected':''}}>{{$subcategory->name}}</option>
                                    @endforeach
                                </select>
                                <small id="subcategoryHelp" class="form-text text-muted">
                                    Indica la categoría a la que pertenece el evento
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
                                    <label for="start-time">Hora de inicio</label>
                                    <input id="start-time" type="time" class="form-control" name="start-time" value="{{ old('start-time') ?: $event_range_date['start_time']}}" placeholder="Comienza" required>
                                    <span class="invalid-feedback" role="alert">
                                    </span>
                                </div>
                                <div class="form-group col-md-12 col-lg-6 mt-md-0 mt-sm-3 mt-3">
                                    <label for="end-time">Hora de cierre <span class="text-muted">(opcional)</span></label>
                                    <input id="end-time" type="text" class="form-control" name="end-time" value="{{ old('end-time') ?: $event_range_date['end_time']}}" placeholder="Termina">
                                    <span class="invalid-feedback" role="alert">
                                    </span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 col-lg-6">
                                    <label for="start-date">Fecha de inicio</label>
                                    <input id="start-date-update" type="date" class="form-control" name="start-date" value="{{ old('start-date') ?: $event_range_date['start_date']}}" placeholder="Comienza" required>
                                    <span class="invalid-feedback" role="alert">
                                    </span>
                                </div>
                                <div class="form-group col-md-12 col-lg-6 mt-md-0 mt-sm-3 mt-3">
                                    <label for="end-date">Fecha de cierre <span class="text-muted">(opcional)</span></label>
                                    <input id="end-date-update" type="date" class="form-control" name="end-date" value="{{ old('end-date') ?: $event_range_date['end_date'] }}" placeholder="Termina">
                                    <span class="invalid-feedback" role="alert">
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="responsible">Responsable del evento</span></label>
                                <input id="responsible" type="text" class="form-control" name="responsible" value="{{ old('responsible') ?: $event_responsible}}" maxlength="55" required>
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="phone_numbers">Teléfono(s)</label>
                                
                                <div class="input-group">
                                   
                                    <div class="input-group-prepend" id='phone_group'>
                                        @if (count($event->phones) > 0)
                                            @foreach ($event->phones as $phone)
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
                                <label for="ubication-description">Referencia <span class="text-muted">(opcional)</span></label>
                                <input id="ubication-description" type="text" class="form-control" name="ubication-description" value="{{ old('ubication-description') ?: $ubication['description']}}" maxlength="255">
                                <small id="categoryHelp" class="form-text text-muted">
                                    Puedes agregar detalles sobre la ubicación
                                </small>
                                <span class="invalid-feedback" role="alert">
                                </span>
                            </div>

                        </div>
                        <div class="col-12 col-md-6">

                            <div class="form-group">
                                <label for="ubication">Ubicación</label>
                                <span class="invalid-feedback" role="alert">
                                </span>
                                <div id="map" class="map">
                                    <div id="info" class="info text-muted">
                                        Latitud:  <span id='latitude'>{{$ubication['latitude']}}</span><br>
                                        Longitud: <span id='longitude'>{{$ubication['longitude']}}</span><br>
                                        Dirección: <span id='address'>{{$ubication['address']}}</span><br>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="images">Imágenes <span class="text-muted">(opcional)</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="images" name="new_images[]" accept="image/png, .jpeg, .jpg"  multiple>
                                    <label class="custom-file-label" id='imagesLabel' for="images" data-browse="Agregar"></label>
                                    <span class="invalid-feedback" role="alert">
                                    </span>
                                </div>
                                <small id="imagesHelp" class="form-text text-muted">
                                        Puedes adjuntar hasta 3 imágenes máximo de 1MB
                                </small>
                                <div class="gallery-images" id="gallery-images">
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
                            
                        </div>
                    </div>
                    <div class="form-group col-4 offset-4">
                        <button type="submit" class="btn btn-primary btn-block" id="send-data">
                            Actualizar
                            <i class="far fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection