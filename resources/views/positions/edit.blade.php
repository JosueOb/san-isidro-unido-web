@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Directiva
@endsection
@section('page-header')
    Editar cargo
@endsection
@section('item-directive')
    active
@endsection
@section('item-directive-collapse')
    show
@endsection
@section('item-positions-list')
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
                <form action="{{route('positions.update', $position->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?: $position->name}}" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    
                                </div>
                                <div class="form-group">
                                    <label for="allocation">Asignación </label>
                                    {{-- <input id="allocation" type="text" class="form-control @error('allocation') is-invalid @enderror" name="allocation" value="{{ old('allocation')}}" required> --}}
                                    <select class="form-control @error('allocation') is-invalid @enderror" id="allocation" name="allocation">
                                        <option value="">Seleccione una opción</option>
                                        <option value="one-person" {{$position->allocation=='one-person' ? 'selected':''}}>Para una persona</option>
                                        <option value="several-people"  {{$position->allocation=='several-people' ? 'selected':''}}>Para varias personas</option>
                                    </select>
                                    <small id="allocationHelp" class="form-text text-muted">
                                        Indica si el cargo se lo puede asignar a varias personas o solamente a una
                                    </small>
                                    @error('allocation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="description">Descripción <span class="text-muted">(opcional)</span></label>
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5">{{ old('description') ?: $position->description}}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
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