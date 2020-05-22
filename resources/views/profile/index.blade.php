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
                                <img src="{{Auth::user()->getAvatar()}}" alt="avatar" class="user_avatar mb-3">
                        </div>
                        <div class="col-12 col-md-6 col-lg-12 col-xl-7 pl-0">
                        
                            <form action="{{route('profile.avatar')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" id="image" name="avatar" required>
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
                    <form action="{{route('profile.password')}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="form-group col-12 col-md-6 col-lg-12 col-xl-6">
                                <label for="password" class="d-block">Contraseña</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" maxlength="100" required autocomplete="new-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <small id="passwordHelp" class="form-text text-muted">
                                    La contraseña debe tener como mínimo 8 caracteres, al menos un dígito, una minúscula y una mayúscula
                                </small>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-12 col-xl-6">
                                <label for="passwordConfirmation" class="d-block">Confirme contraseña</label>
                                <input id="passwordConfirmation" type="password" class="form-control @error('passwordConfirmation') is-invalid @enderror" name="passwordConfirmation"  maxlength="100" required autocomplete="new-password">
                                @error('passwordConfirmation')
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

        <div class="col-12 col-sm-12 col-md-12 col-lg-7">
            <div class="card card-primary profile">
                <div class="card-header">
                    <h4>Datos Personales</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('profile.data')}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label for="first_name">Nombre</label>
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') ?: Auth::user()->first_name }}" maxlength="25" required>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="last_name">Apellidos</label>
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') ?: Auth::user()->last_name}}" maxlength="25" required>
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="number_phone">Celular <span class="text-muted">(opcional)</span></label>
                            <input type="tel" class="form-control @error('number_phone') is-invalid @enderror" id='number_phone' name="number_phone" value="{{old('number_phone') ?: Auth::user()->number_phone}}" maxlength="10" pattern="(09)[0-9]{8}" title="Recuerda que se admiten solo 10 dígitos y anteponer el código 09 al ingresar tu número telefónico">
                            <small id="number_phoneHelp" class="form-text text-muted">
                                Recuerda anteponer el código 09 al ingresar tu número telefónico
                            </small>
                            @error('number_phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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