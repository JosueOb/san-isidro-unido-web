<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/landing.js') }}" defer></script>

    <!-- Icon-->
    <link rel="icon" type="image/svg+xml" href="https://siu-dev97-sd.s3-sa-east-1.amazonaws.com/recursos_publicos/logos/sanIsidroIconOnlyTransparent.svg" sizes='48x48'>

    <!-- Styles -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>
<body class="login">
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container-fluid">
            <a class="navbar-brand js-scroll-trigger" href="{{route('landing')}}">
                <img src="https://siu-dev97-sd.s3-sa-east-1.amazonaws.com/recursos_publicos/logos/sanIsidroIconOnlyTransparent.svg" alt="">
                <span>San Isidro Unido</span>
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ml-auto">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{route('landing')}}">Regresar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section>
        @yield('content')
    </section>

    <footer class="footer">
        <div class="social-media">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="social-media-title">Nuestras redes sociales</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="social-media-icons">
                            <!--Facebook-->
                            <a class="social-icon" href="#" target="_blank">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <!--Instagram-->
                            <a class="social-icon" href="#" target="_blank">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                            <!--Youtube-->
                            <a class="social-icon" href="#" target="_blank">
                                <i class="fab fa-youtube"></i> Youtube
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-content">
            <div class="container">
                <div class="row">

                    <div class="col-md-4">
                        <div class="footer-item">
                            <h6 class="item-title"><a href="#">San Isidro Unido</a></h6>
                            <hr class="item-slide">
                            <div class="item-content description">
                                <p>Comunícate directamente con la directiva barrial con SIU</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="footer-item">
                            <h6 class="item-title">Contacto</h6>
                            <hr class="item-slide">
                            <div class="item-content contacts">
                                <p><i class="fa fa-envelope mr-3"></i> siul@gmail.com</p>
                                <p><i class="fa fa-phone mr-3"></i> 022505682</p>
                                <p><i class="fa fa-print mr-3"></i> 022505682</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="footer-item">
                            <h6 class="item-title">Menú Principal</h6>
                            <hr class="item-slide">
                            <div class="item-content options">
                                <a class="footer-link" href="{{route('landing')}}">Inicio</a>
                                <a class="footer-link" href="{{route('landing')}}#features">Descripción</a>
                                <a class="footer-link" href="{{route('landing')}}#directive">Directiva</a>
                                <a class="footer-link" href="{{route('landing')}}#news">Noticias</a>
                                <a class="footer-link" href="{{route('landing')}}#events">Eventos</a>
                                <a class="footer-link" href="{{route('login')}}">Login</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="footer-copyright">
            Copyright © 2020
            <a href="#">San Isidro Unido</a>
        </div>
    </footer>
</body>
</html>