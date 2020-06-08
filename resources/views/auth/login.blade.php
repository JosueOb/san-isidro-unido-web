@extends('layouts.authentication')

@section('content')
<div class="container mt-5">
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
      <div class="login-brand d-md-block">
        <img src="https://siu-dev97-sd.s3-sa-east-1.amazonaws.com/recursos_publicos/logos/SanIsidroIcono.svg" alt="logo" class="rounded-circle">
      </div>

      <div class="card card-primary">
        <div class="card-header">
          <h4 class="auth-title">Login</h4>
        </div>

        <div class="card-body">
          @if (session('info'))
          <div class="alert alert-danger text-center" role="alert">
            {{session('info')}}
          </div>
          @endif
          <form  method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
              <label for="email">Correo electrónico</label>
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
              @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>

            <div class="form-group">
              <div class="d-block">
                  <label for="password" class="control-label">Contraseña</label>
                
                @if (Route::has('password.request'))
                <div class="float-right ">
                  <a class="text-small" href="{{ route('password.request') }}">
                    Olvidastes tu contraseña?
                  </a>
                </div>
                @endif
              </div>
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="remember" class="custom-control-input" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="remember-me">Recuérdame</label>
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-sm btn-block" tabindex="4">
                Ingresar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection