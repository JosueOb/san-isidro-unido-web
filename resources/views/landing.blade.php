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

            <section id="directive">
                <div class="directive-content">
                    <h2 class="section-title">Directiva</h2>
                    <div class="section-description">
                        <p>Información de contacto de la directiva barrial</p>
                    </div>

                    <div class="row">
                        @foreach ($directiveMembers as $directive)
                        <div class="col-12 col-md-6">
                            <div class="directive-member">
                                <div class="row">
                                    <div class="col-5">
                                        <img class="member-photo"
                                            src="{{$directive->getAvatar()}}"
                                            alt="user-photo">
                                    </div>
                                    <div class="col-7">
                                        <div class="menber-info">
                                            <h4 class="member-name">{{$directive->getFullName()}}</h4>
                                            <p class="member-position">{{$directive->position->name}}</p>
                                            <span class="member-phone">{{$directive->email}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>


            <section id="news">
                <div class="news-content">
                    <h2 class="section-title">Noticias</h2>
                    <div class="section-description">
                        <p>Últimas actividades realizadas por parte de la directiva</p>
                    </div>

                    <div class="row">
                        <div class="col">
                            <!-- <div id="news-carousel" class="carousel slide" data-ride="carousel"> -->
                            <div id="news-carousel" class="carousel slide">
                                <ol class="carousel-indicators">
                                    <li data-target="#news-carousel" data-slide-to="0" class="active"></li>
                                    <li data-target="#news-carousel" data-slide-to="1"></li>
                                    <li data-target="#news-carousel" data-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="https://images.pexels.com/photos/445109/pexels-photo-445109.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                            alt="">
                                        <div class="carousel-caption">
                                            <h5>Fumigación de las calles principales del barrio</h5>

                                            <p>Escrito por: Josué Cando</p>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-dark" data-toggle="modal"
                                                data-target="#exampleModalScrollable">
                                                Ver más información
                                            </button>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100"
                                            src="https://images.pexels.com/photos/1839564/pexels-photo-1839564.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                            alt="">
                                        <div class="carousel-caption">
                                            <h5>Title</h5>
                                            <p>Text</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100"
                                            src="https://images.pexels.com/photos/3922221/pexels-photo-3922221.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                            alt="">
                                        <div class="carousel-caption">
                                            <h5>Title</h5>
                                            <p>Text</p>
                                        </div>
                                    </div>
                                </div>

                                <a class="carousel-control-prev" href="#news-carousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#news-carousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalScrollableTitle">
                                                Hola mundoHola mundoHola mundoHola mundoHola mundoHola mundoHola
                                                mundoHola mundoHola mundoHola mundoHola mundoHola mundo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <p class="text-right">Escrito por: Josué Cando</p>
                                            <p class="text-justify">
                                                Contrary to popular belief, Lorem Ipsum is not simply random text. It
                                                has
                                                roots in a piece of classical Latin literature from 45 BC, making it
                                                over
                                                2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney
                                                College in Virginia, looked up one of the more obscure Latin words,
                                                consectetur, from a Lorem Ipsum passage, and going through the cites of
                                                the
                                                word in classical literature, discovered the undoubtable source. Lorem
                                                Ipsum
                                                comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et
                                                Malorum"
                                                (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book
                                                is a
                                                treatise on the theory of ethics, very popular during the Renaissance.
                                                The
                                                first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a
                                                line
                                                in section 1.10.32.

                                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced
                                                below
                                                for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus
                                                Bonorum
                                                et Malorum" by Cicero are also reproduced in their exact original form,
                                                accompanied by English versions from the 1914 translation by H. Rackham.
                                                Contrary to popular belief, Lorem Ipsum is not simply random text. It
                                                has
                                                roots in a piece of classical Latin literature from 45 BC, making it
                                                over
                                                2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney
                                                College in Virginia, looked up one of the more obscure Latin words,
                                                consectetur, from a Lorem Ipsum passage, and going through the cites of
                                                the
                                                word in classical literature, discovered the undoubtable source. Lorem
                                                Ipsum
                                                comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et
                                                Malorum"
                                                (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book
                                                is a
                                                treatise on the theory of ethics, very popular during the Renaissance.
                                                The
                                                first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a
                                                line
                                                in section 1.10.32.

                                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced
                                                below
                                                for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus
                                                Bonorum
                                                et Malorum" by Cicero are also reproduced in their exact original form,
                                                accompanied by English versions from the 1914 translation by H. Rackham.
                                            </p>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="events">
                <div class="events-content">
                    <h2 class="section-title">Eventos</h2>
                    <div class="section-description">
                        <p>Eventos que se presentan en el barrio</p>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div id="scrolling">
                                <ul>
                                    <li>
                                        <div class="event-content">
                                            <div class="event-image">
                                                <img src="https://images.pexels.com/photos/445109/pexels-photo-445109.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                                    alt="event">
                                            </div>
                                            <h6 class="event-title">Campania de visión y odontología Pedro Belazco</h6>
                                            <p class="event-description">Descripción de evento, descripción de evento,
                                                descripción de
                                                evento,descripción de evento,descripción de evento,descripción de
                                                evento, más texo</p>
                                            <div class="event-date">
                                                <span>2020-20-15 al 2020-20-16</span>
                                                <span>20:00 - 8:00</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="event-content">
                                            <div class="event-image">
                                                <img src="https://images.pexels.com/photos/906052/pexels-photo-906052.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                                    alt="event">
                                            </div>
                                            <h6 class="event-title">Campania de visión y odontología Pedro Belazco</h6>
                                            <p class="event-description">Descripción de evento, descripción de evento,
                                                descripción de
                                                evento,descripción de evento,descripción de evento,descripción de
                                                evento, más texo</p>
                                            <div class="event-date">
                                                <span>2020-20-15 al 2020-20-16</span>
                                                <span>20:00 - 8:00</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="event-content">
                                            <div class="event-image">
                                                <img src="https://images.pexels.com/photos/341970/pexels-photo-341970.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                                    alt="event">
                                            </div>
                                            <h6 class="event-title">Campania de visión y odontología Pedro Belazco</h6>
                                            <p class="event-description">Descripción de evento, descripción de evento,
                                                descripción de
                                                evento,descripción de evento,descripción de evento,descripción de
                                                evento, más texo</p>
                                            <div class="event-date">
                                                <span>2020-20-15 al 2020-20-16</span>
                                                <span>20:00 - 8:00</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="event-content">
                                            <div class="event-image">
                                                <img src="https://images.pexels.com/photos/445109/pexels-photo-445109.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                                    alt="event">
                                            </div>
                                            <h6 class="event-title">Campania de visión y odontología Pedro Belazco</h6>
                                            <p class="event-description">Descripción de evento, descripción de evento,
                                                descripción de
                                                evento,descripción de evento,descripción de evento,descripción de
                                                evento, más texo</p>
                                            <div class="event-date">
                                                <span>2020-20-15 al 2020-20-16</span>
                                                <span>20:00 - 8:00</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="event-content">
                                            <div class="event-image">
                                                <img src="https://images.pexels.com/photos/445109/pexels-photo-445109.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                                    alt="event">
                                            </div>
                                            <h6 class="event-title">Campania de visión y odontología Pedro Belazco</h6>
                                            <p class="event-description">Descripción de evento, descripción de evento,
                                                descripción de
                                                evento,descripción de evento,descripción de evento,descripción de
                                                evento, más texo</p>
                                            <div class="event-date">
                                                <span>2020-20-15 al 2020-20-16</span>
                                                <span>20:00 - 8:00</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="event-content">
                                            <div class="event-image">
                                                <img src="https://images.pexels.com/photos/445109/pexels-photo-445109.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                                    alt="event">
                                            </div>
                                            <h6 class="event-title">Campania de visión y odontología Pedro Belazco</h6>
                                            <p class="event-description">Descripción de evento, descripción de evento,
                                                descripción de
                                                evento,descripción de evento,descripción de evento,descripción de
                                                evento, más texo</p>
                                            <div class="event-date">
                                                <span>2020-20-15 al 2020-20-16</span>
                                                <span>20:00 - 8:00</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="event-content">
                                            <div class="event-image">
                                                <img src="https://images.pexels.com/photos/445109/pexels-photo-445109.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                                                    alt="event">
                                            </div>
                                            <h6 class="event-title">Campania de visión y odontología Pedro Belazco</h6>
                                            <p class="event-description">Descripción de evento, descripción de evento,
                                                descripción de
                                                evento,descripción de evento,descripción de evento,descripción de
                                                evento, más texo</p>
                                            <div class="event-date">
                                                <span>2020-20-15 al 2020-20-16</span>
                                                <span>20:00 - 8:00</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <!--/Main layout-->

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
                                <a class="footer-link" href="#home">Inicio</a>
                                <a class="footer-link" href="#features">Descripción</a>
                                <a class="footer-link" href="#directive">Directiva</a>
                                <a class="footer-link" href="#news">Noticias</a>
                                <a class="footer-link" href="#events">Eventos</a>
                                <a class="footer-link" href="#">Login</a>
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