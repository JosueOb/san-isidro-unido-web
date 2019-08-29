@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Roles
@endsection
@section('page-header')
    Registrar rol
@endsection
@section('item-role')
    active
@endsection
@section('item-role-collapse')
    show
@endsection
@section('item-role-create')
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
            {{-- <div class="card-header">
                <h4>Formulario</h4>
            </div> --}}
            <div class="card-body">
                <form action="{{route('roles.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label for="name">Nombre</label>
                            <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autofocus required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="slug">URL amigable</label>
                            <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug')}}" required>
                            @error('slug')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción <span class="text-muted">(opcional)</span></label>
                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" >{{ old('description')}}</textarea>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <hr>
                    <h3>Asignar permisos</h3>
                    <div class="form-group">
                        <ul class="list-unstyled">
                            @foreach ($permissions as $permission)
                                <li>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="permissions[]" class="custom-control-input @error('permissions') is-invalid @enderror" id="{{$permission->name}}" value="{{$permission->id}}" 
                                        @if (is_array(old('permissions')) && in_array($permission->id, old('permissions')))
                                            checked
                                        @endif>
                                        <label class="custom-control-label" for="{{$permission->name}}">
                                            {{$permission->name}}
                                            <em>({{$permission->description ?: 'Sin descripción'}})</em>
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @error('permissions')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
    
                    <div class="form-group col-4 offset-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            Guardar
                            <i class="far fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
            {{-- <div class="card-footer">
                <p>Footer</p>
            </div> --}}
        </div>
    </div>
</div>
@endsection