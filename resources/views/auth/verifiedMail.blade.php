@extends('layouts.authentication')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand d-none d-md-block">
                <img src="{{ asset('storage/img/logo.svg')}}" alt="logo" class="rounded-circle">
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Correo verificado</h4>
                </div>

                <div class="card-body">

                    <p>
                        Gracias por verificar tu correo electrónico, ahora puede acceder a las funcionalidades de la aplicación móvil
                    </p>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
