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
    <link rel="icon" type="image/png" href="https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/logos/logo.png">

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="{{ mix('css/styles.css') }}">
      
</head>
<body class="landing">
        <!--Main Navigation-->
    <header id="home">

        <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
            <div class="container-fluid">
                <a id="brand" class="navbar-brand js-scroll-trigger" href="#home">
                    <img src="img/default_images/logos/sanIsidroIconOnlyTransparent.svg" alt="">
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
                            <a class="nav-link js-scroll-trigger" href="#home">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="#features">Descripción</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="#directive">Directiva</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="#news">Noticias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="#events">Eventos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!--Mask-->
        <div id="header" class="view">
            <div class="mask">
                <div class="container-fluid header-content">
                    <div class="row header-row">
                        <div class="col-10 col-md-7">
                            <!-- Heading -->
                            <div class="title">
                                <h2 class="text">SIU</h2>
                                <img src="img/default_images/logos/sanIsidroIconOnlyTransparent.svg" alt="siu-logo"
                                    class="logo">
                            </div>
                            <!-- Divider -->
                            <hr class="hr">
                            <!-- Description -->
                            <h4 class="description">SIU es un medio de comunición directo con la directiva barrial
                                de San Iisdro de Puengasí</h4>
                            <!-- Download -->
                            <div class="download">
                                <a href="https://play.google.com/store/apps/details?id=com.stalinmaza.sanisidrounido"
                                    target="_blanck">
                                    <img src="img/default_images/landing/google-es.png" alt="download">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!--/.Mask-->
    </header>
    <!--Main Navigation-->


</body>
</html>