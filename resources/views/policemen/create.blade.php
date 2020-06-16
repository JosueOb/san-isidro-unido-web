@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Policía
@endsection
@section('page-header')
    Registrar policía
@endsection
@section('item-police')
    active
@endsection
@section('item-police-collapse')
    show
@endsection
@section('item-police-create')
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
                <form action="{{route("policemen.store")}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label for="first_name">Nombre</label>
                            <input id="first_name" type="text" class="form-control  @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" maxlength="25" required autofocus>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="last_name">Apellidos</label>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name')}}" maxlength="25" required>
                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="number_phone">Celular</label>
                            <input id="number_phone" type="text" class="form-control @error('number_phone') is-invalid @enderror" name="number_phone" value="{{old('number_phone')}}" maxlength="10" pattern="(09)[0-9]{8}" title="Recuerda que se admiten solo 10 dígitos y anteponer el código 09 al ingresar tu número telefónico" required>
                            <small id="number_phoneHelp" class="form-text text-muted">
                                Recuerda anteponer el 09 al ingresar tu número telefónico
                            </small>
                            @error('number_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
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