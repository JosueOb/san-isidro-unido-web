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


    <!--Main layout-->
    <main>
        <div class="container-fluid">
            <section id="features">
                <div class="features-content">
                    <!-- Heading -->
                    <h2 class="section-title">Descripción</h2>
                    <div class="section-description">
                        <p>En la aplicación móvil de SIU te permite realizar lo siguiente:</p>
                    </div>

                    <!--feature-items-->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="fa fa-camera-retro"></i>
                                <h4 class="item-title">Reportar Problemas / Emegencias</h4>
                                <p class="item-description">Puedes dar a conocer los problemas sociales que se presentan
                                    en el barrio a la directiva,
                                    como también notificar a la policía comunitaria alguna emergencia</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="far fa-newspaper"></i>
                                <h4 class="item-title">Conocer Noticias y Eventos</h4>
                                <p class="item-description">Puedes conocer las actividades realizadas por parte de la
                                    directiva, como también los
                                    eventos
                                    que se presenten en el barrio</p>
                            </div>
                        </div>

                        <div class="col-md-4 mb-1">
                            <div class="feature-item">
                                <i class="fas fa-portrait"></i>
                                <h4 class="item-title">Directorio de Servicios Públicos</h4>
                                <p class="item-description">Puedes visualizar un directorio con servicios públicos
                                    presentes en el barrio, indicando
                                    su
                                    horario de anteción, ubicación e información de contacto</p>

                            </div>
                        </div>
                    </div>
                </div>
            </section>


            
        </div>
    </main>
    <!--/Main layout-->

</body>
</html>