@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Directiva
@endsection
@section('page-header')
    Registrar nuevo directivo
@endsection
@section('item-directive')
    active
@endsection
@section('item-directive-collapse')
    show
@endsection
@section('item-directive-create')
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
                <form action="{{route('members.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label for="first_name">Nombre</label>
                            <input id="first_name" type="text" class="form-control  @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}"  required autofocus>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="last_name">Apellidos</label>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name')}}" required>
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
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="last_name">Cargo</label>

                            @if (count($positions)>0)
                                <select class="form-control @error('position') is-invalid @enderror" id="position" name="position" required>
                                    <option value="">Seleccione un cargo</option>
                                    @foreach ($positions as $position)
                                        <option value="{{$position->id}}" {{old('position')==$position->id ? 'selected':''}}>{{$position->name}}</option>
                                    @endforeach
                                </select>
                                @error('position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror 
                            @else
                                <p class="text-danger">No existen registros de cargos
                                    <a href="{{route('positions.create')}}" class="btn btn-primary float-right">Crear</a>
                                </p>
                                
                            @endif

                        </div>
                    </div>
            
                    <div class="form-group col-4 offset-4">
                        <button type="submit" class="btn btn-primary btn-block">
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