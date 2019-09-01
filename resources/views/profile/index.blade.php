@extends('layouts.dashboard')
@section('page-subtitle')
    Información personal
@endsection
@section('page-header')
    Perfil
@endsection
@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>
<div class="profile">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-5">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Avatar</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-12 col-xl-5">
                            <img src="{{Auth::user()->getAvatar()}}" alt="avatar" class="w-75 rounded-circle">
                        </div>
                        <div class="col-12 col-md-6 col-lg-12 col-xl-7 pl-0">
                        
                            <form action="{{route('profile.avatar')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                <div class="form-group">
                                    <label for="avatar">Imagen</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" id="avatar" name="avatar" required>
                                        <label class="custom-file-label" for="avatar" data-browse="Cambiar"></label>
                                        @error('avatar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                                    Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary profile">
                <div class="card-header">
                    <h4>Cambiar contraseña</h4>
                </div>

                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="form-group col-12 col-md-6 col-lg-12 col-xl-6">
                                <label for="password" class="d-block">Contraseña</label>
                                <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password">
                                <div id="pwindicator" class="pwindicator">
                                    <div class="bar"></div>
                                    <div class="label"></div>
                                </div>
                            </div>
                            <div class="form-group col-12  col-md-6 col-lg-12 col-xl-6">
                                <label for="password2" class="d-block">Confirme contraseña</label>
                                <input id="password2" type="password" class="form-control" name="password-confirm">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm btn-block">
                            Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-7">
            <div class="card card-primary profile">
                <div class="card-header">
                    <h4>Datos</h4>
                </div>

                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label for="frist_name">Nombre</label>
                                <input id="frist_name" type="text" class="form-control" name="frist_name">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="last_name">Apellidos</label>
                                <input id="last_name" type="text" class="form-control" name="last_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input id="email" type="email" class="form-control" name="email">
                            <div class="invalid-feedback">
                                Ingrese un correo electrónico
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="form-group ">
                            <button type="submit" class="btn btn-primary btn-sm btn-block">
                            Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection