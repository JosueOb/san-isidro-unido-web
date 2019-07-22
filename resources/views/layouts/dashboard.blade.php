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
    
    <!-- Icon-->
    <link rel="icon" type="image/png" href="{{asset('storage/img/logo.png')}}">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
    
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!--Main Sidebar-->
            <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
                <div class="main-navbar">
                    <nav class="navbar align-items-stretch navbar-light flex-md-nowrap border-bottom p-0">
                        <a class="navbar-brand w-100 mr-0" href="#">
                            <div class="d-table m-auto">
                                <img id='main-logo' src="{{ asset('storage/img/logo.png') }}" class="brand-logo d-inline-block align-top mr-1 w-25">
                                <span class="d-none d-md-inline ml-1 brand-name">San Isidro Unido</span>
                            </div>
                        </a>
                        <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </nav>
                </div>

                <div class="nav-wrapper">
                    <ul class="nav flex-column accordion" id="accordionSidebar">
                        <li class="menu-header">Rol del usuario</li>
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="collapse" data-target="#collapseRol" aria-expanded="true" aria-controls="collapseRol">
                                <i class="fas fa-id-card-alt"></i>
                                <span>Opción 1</span>
                            </a>
                            <div id="collapseRol" class="collapse show" >
                                <div class="collapse-inner">
                                    <a class="collapse-item active" href="#"><i class="fas fa-plus-circle"></i>Acción 1</a>
                                    <a class="collapse-item" href="cards.html"><i class="fas fa-list"></i>Acción 2</a>
                                    <a class="collapse-item" href="cards.html"><i class="fas fa-edit"></i>Acción 3</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="collapse" data-target="#collapseDirectiva" aria-expanded="true" aria-controls="collapseDirectiva">
                                <!--<i class="material-icons">vertical_split</i>-->
                                <i class="fas fa-users"></i>
                                <span>Opción 2</span>
                            </a>
                            <div id="collapseDirectiva" class="collapse" >
                                <div class="collapse-inner">
                                    <a class="collapse-item" href="#"><i class="fas fa-plus-circle"></i>Acción 1</a>
                                    <a class="collapse-item" href="#"><i class="fas fa-list"></i>Acción 2</a>
                                    <a class="collapse-item" href="#"><i class="fas fa-edit"></i>Acción 3</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </aside>
            <!--/Main Sidebar-->

            <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
                <div class="main-navbar sticky-top">

                    <!-- Main Navbar -->
                    <nav class="navbar align-items-stretch navbar-light p-0 justify-content-end flex-md-nowrap">
                    <!--<nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0 justify-content-end">-->
                        <ul class="navbar-nav flex-row">

                            <li class="nav-item dropdown user-options m-0">
                                <a href="#" class="nav-link dropdown-toggle px-3 text-nowrap" data-toggle="dropdown" id="dropdownMenuUser" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ asset('storage/img/logo.png') }}" alt="user avatar" class="user-avatar rounded-circle mr-2"width='50px'>
                                    <span class="d-none d-lg-inline-block">Nombre del usuario</span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby='dropdownNotifications'>
                                    <a href="profile.html" class="dropdown-item">
                                        <div class="option__icon-wrapper">
                                            <div class="option__icon">
                                                <i class="fas fa-user-circle icon"></i>
                                            </div>
                                        </div>
                                        <div class="option__content">
                                            <span class="option__name">Perfil</span>
                                        </div>
                                    </a>
                                    <a href="login.html" class="dropdown-item">
                                        <div class="option__icon-wrapper">
                                            <div class="option__icon">
                                                <i class="fas fa-sign-out-alt text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="option__content">
                                            <span class="option__name text-danger">Cerrar sesión</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                        <nav class="nav">
                            <a href="#" class="nav-link nav-link-icon toggle-sidebar d-sm-inline d-md-none d-lg-none text-center" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
                                <i class="fas fa-bars"></i>
                            </a>
                        </nav>
                    </nav>
                </div>
                <!-- /Main Navbar -->
                    
                <div class="main-content-container container-fluid px-4">

                    <!--Page header-->
                    <div class="page-header row no-gutters py-4">
                        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                            <span class="text-uppercase page-subtitle">Título</span>
                            <h3 class="page-title">Subtitulo</h3>
                        </div>
                    </div>
                    <!--/Page header-->

                    <!--Content-->
                    <div class="card card-primary">
                        
                        <div class="card-body">
                            <p>Contenido ha presentar</p>
                        </div>
                    </div>
                <!--/Content-->
                </div>

                
                <!--Footer-->
                <footer class="main-footer d-flex p-2 px-3 bg-white border-top">
                    <span class="copyright ml-auto my-auto mr-2">Copyright © 2019
                        <a href="#">San Isidro Unido</a>
                    </span>
                </footer>
                <!--/Footer-->
                
            </main>
        </div>
    </div>
</body>
</html>