@extends('layouts.authentication')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand d-md-block">
                {{-- <img src="{{ asset('storage/img/logo.svg')}}" alt="logo" class="rounded-circle"> --}}
                <img src="https://siu-dev97-sd.s3-sa-east-1.amazonaws.com/recursos_publicos/logos/SanIsidroIcono.svg" alt="logo" class="rounded-circle">
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="auth-title">Verifica tu correo electrónico</h4>
                </div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            <p>Se ha enviado un nuevo enlace de verificación a su correo electrónico</p>
                        </div>
                    @endif

                    <p>
                        Antes de continuar, revisa tu correo electrónico para ver si hay un enlace de verificación, 
                        Si no recibes el correo <a href="{{ route('verification.resend') }}"> haga clic aquí para solicitar otro</a>
                    </p>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
