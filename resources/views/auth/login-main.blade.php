<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <section>
        <div class="container mt-5">
          <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
              <div class="login-brand d-none d-md-block">
                <img src="images/shards-dashboards-logo.svg" alt="logo" width="100" class="shadow-info rounded-circle">
              </div>
  
              <div class="card card-primary">
                <div class="card-header">
                  <h4>Login</h4>
                </div>
  
                <div class="card-body">
                  <form  action="index.html" class="needs-validation" novalidate="">
                    
                    <div class="form-group">
                      <label for="email">Correo electrónico</label>
                      <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                      <div class="invalid-feedback">
                        Ingrese su correo electrónico
                      </div>
                    </div>
  
                    <div class="form-group">
                      <div class="d-block">
                          <label for="password" class="control-label">Contrasenia</label>
                        <div class="float-right ">
                          <a href="index.html" class="text-small">
                            Olvidastes tu contrasenia?
                          </a>
                        </div>
                      </div>
                      <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                      <div class="invalid-feedback">
                        Ingrese su contrasenia
                      </div>
                    </div>
  
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                        <label class="custom-control-label" for="remember-me">Recuerdame</label>
                      </div>
                    </div>
  
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-sm btn-block" tabindex="4">
                        Ingresar
                      </button>
                    </div>
                  </form>
                  
                  <!--<div class="mt-5 text-muted text-center">
                    No tienes una cuenta? <a href="auth-register.html">Crear cuenta</a>
                  </div>-->
                </div>
              </div>
              <!--<div class="text-center">
                Copyright &copy; JosueOb 
              </div>-->
            </div>
          </div>
        </div>
      </section>
</body>
</html>
