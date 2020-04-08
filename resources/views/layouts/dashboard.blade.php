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

    <!--Leaflet CSS-->
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""
  />
      
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!--Main Sidebar-->
            <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
                <div class="main-navbar">
                    <nav class="navbar align-items-stretch navbar-light flex-md-nowrap border-bottom p-0">
                        <a class="navbar-brand w-100 mr-0" href="{{ route('login')}}">
                            <div class="d-table m-auto">
                                <img src="{{ asset('storage/img/logo.png') }}" class="brand-logo d-inline-block align-top mr-1">
                                <span class="d-none d-md-inline d-lg-none d-xl-inline ml-1 brand-name">San Isidro Unido</span>
                            </div>
                        </a>
                        <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </nav>
                </div>

                <div class="nav-wrapper">
                    <ul class="nav flex-column accordion" id="accordionSidebar">
                        <li class="menu-header">
                            @foreach (Auth::user()->getWebSystemRoles() as $role)
                                {{$role->name}}<br>
                            @endforeach
                        </li>
                        
                        @canany(['roles.index', 'roles.create'])
                        <li class="nav-item @yield('item-role')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapseRol" aria-expanded="true" aria-controls="collapseRol">
                                <i class="fas fa-id-card-alt"></i>
                                <span>Roles</span>
                            </a>
                            <div id="collapseRol" class="collapse @yield('item-role-collapse')" >
                                <div class="collapse-inner">
                                    @can('roles.index')
                                        <a class="collapse-item @yield('item-role-list')" href="{{route('roles.index')}}"><i class="fas fa-list"></i>Listar roles</a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                        @endcanany

                        @canany(['members.index', 'members.create','positions.index','positions.create'])
                        <li class="nav-item @yield('item-directive')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapseDirective" aria-expanded="true" aria-controls="collapseDirective">
                                <i class="fas fa-users"></i>
                                <span>Directiva</span>
                            </a>
                            <div id="collapseDirective" class="collapse @yield('item-directive-collapse')" >
                                <div class="collapse-inner">
                                    @can('members.index')
                                        <a class="collapse-item @yield('item-directive-list')" href="{{ route('members.index') }}"><i class="fas fa-list"></i>Listar miembros</a>
                                    @endcan
                                    @can('members.create')
                                        <a class="collapse-item @yield('item-directive-create')" href="{{route('members.create')}}"><i class="fas fa-user-plus"></i>Registrar miembro</a>
                                    @endcan
                                    @can('positions.index')
                                        <a class="collapse-item @yield('item-positions-list')" href="{{route('positions.index')}}"><i class="fas fa-list"></i>Listar cargos</a>
                                    @endcan
                                    @can('positions.create')
                                        <a class="collapse-item @yield('item-positions-create')" href="{{route('positions.create')}}"><i class="fas fa-plus-circle"></i>Agregar cargo</a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                        @endcanany

                        @canany(['neighbors.index', 'neighbors.create'])
                        <li class="nav-item @yield('item-neighbor')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapseNeighbor" aria-expanded="true" aria-controls="collapseNeighbor">
                                <i class="fas fa-user"></i>
                                <span>Vecinos</span>
                            </a>
                            <div id="collapseNeighbor" class="collapse @yield('item-neighbor-collapse')" >
                                <div class="collapse-inner">
                                    @can('neighbors.index')
                                        <a class="collapse-item @yield('item-neighbor-list')" href="{{route('neighbors.index')}}"><i class="fas fa-list"></i>Listar vecinos</a>
                                    @endcan
                                    @can('neighbors.index')
                                        <a class="collapse-item @yield('item-neighbor-create')" href="{{ route('neighbors.create')}}"><i class="fas fa-user-plus"></i>Registrar vecino</a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                        @endcanany
                        
                        @canany(['reports.index', 'reports.create'])
                        <li class="nav-item @yield('item-report')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapseReport" aria-expanded="true" aria-controls="collapseReport">
                                <i class="fas fa-book"></i>
                                <span>Actividades</span>
                            </a>
                            <div id="collapseReport" class="collapse @yield('item-report-collapse')" >
                                <div class="collapse-inner">
                                    @can('reports.index')
                                    <a class="collapse-item @yield('item-report-list')" href="{{ route('reports.index') }}"><i class="fas fa-list"></i>Listar actividades</a>
                                    @endcan
                                    @can('reports.create')
                                    <a class="collapse-item @yield('item-report-create')" href="{{ route('reports.create')}}"><i class="fas fa-book-open"></i>Registrar actividades</a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                        @endcanany

                        @canany(['categories.index','categories.edit'])
                        <li class="nav-item @yield('item-category')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                                <i class="fas fa-tag"></i>
                                <span>Categorías</span>
                            </a>
                            <div id="collapseCategory" class="collapse @yield('item-category-collapse')" >
                                <div class="collapse-inner">
                                    <a class="collapse-item @yield('item-category-list')" href="{{ route('categories.index')}}"><i class="fas fa-list"></i>Listar categorías</a>
                                    {{-- <a class="collapse-item @yield('item-category-create')" href="{{ route('categories.create') }}"><i class="fas fa-plus-circle"></i>Agregar categoría</a> --}}
                            </div>
                            </div>
                        </li>
                        @endcanany

                        @canany(['subcategories.index','subcategories.create'])
                        <li class="nav-item @yield('item-subcategory')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapseSubcategory" aria-expanded="true" aria-controls="collapseSubcategory">
                                <i class="fas fa-tags"></i>
                                <span>Subcategorías</span>
                            </a>
                            <div id="collapseSubcategory" class="collapse @yield('item-subcategory-collapse')" >
                                <div class="collapse-inner">
                                    @can('subcategories.index')
                                    <a class="collapse-item @yield('item-subcategory-list')" href="{{route('subcategories.index')}}"><i class="fas fa-list"></i>Listar subcategorías</a>
                                    @endcan
                                    @can('subcategories.create')
                                    <a class="collapse-item @yield('item-subcategory-create')" href="{{route('subcategories.create')}}"><i class="fas fa-plus-circle"></i>Agregar subcategoría</a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                        @endcanany

                        @canany(['publicServices.index','publicServices.create'])
                        <li class="nav-item @yield('item-public-service')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapsePublicService" aria-expanded="true" aria-controls="collapsePublicService">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Servicios públicos</span>
                            </a>
                            <div id="collapsePublicService" class="collapse @yield('item-public-service-collapse')" >
                                <div class="collapse-inner">
                                    @can('publicServices.index')
                                    <a class="collapse-item @yield('item-public-service-list')" href="{{ route('publicServices.index') }}"><i class="fas fa-list"></i>Listar servicios publicos</a>
                                    @endcan
                                    @can('publicServices.create')
                                    <a class="collapse-item @yield('item-public-service-create')" href="{{ route('publicServices.create') }}"><i class="fas fa-calendar-plus"></i></i>Registrar servicio público</a>
                                    @endcan
                            </div>
                            </div>
                        </li>
                        @endcanany
                        

                        @canany(['events.index','events.create'])
                        <li class="nav-item @yield('item-event')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapseEvent" aria-expanded="true" aria-controls="collapseEvent">
                                <i class="fas fa-calendar-week"></i>
                                <span>Eventos</span>
                            </a>
                            <div id="collapseEvent" class="collapse @yield('item-event-collapse')" >
                                <div class="collapse-inner">
                                    @can('events.index')
                                    <a class="collapse-item @yield('item-event-list')" href="{{route('events.index')}}"><i class="fas fa-list"></i>Listar eventos</a>
                                    @endcan
                                    @can('events.create')
                                    <a class="collapse-item @yield('item-event-create')" href="{{route('events.create')}}"><i class="fas fa-calendar-plus"></i></i>Registrar evento</a>
                                    @endcan
                            </div>
                            </div>
                        </li>
                        @endcanany


                        {{-- @canany(['events.index','events.create']) --}}
                        <li class="nav-item @yield('item-moderator')">
                            <a class="nav-link" data-toggle="collapse" data-target="#collapseModerator" aria-expanded="true" aria-controls="collapseModerator">
                                <i class="fas fa-calendar-week"></i>
                                <span>Moderadores</span>
                            </a>
                            <div id="collapseModerator" class="collapse @yield('item-moderator-collapse')" >
                                <div class="collapse-inner">
                                    {{-- @can('events.create') --}}
                                    <a class="collapse-item @yield('item-moderator-create')" href="{{route('moderators.create')}}"><i class="fas fa-shield-alt"></i>Asignar moderador</a>
                                    {{-- @endcan --}}
                                    {{-- @can('events.index') --}}
                                    <a class="collapse-item @yield('item-moderator-list')" href="{{route('moderators.index')}}"><i class="fas fa-list"></i>Listar moderadores</a>
                                    {{-- @endcan --}}
                            </div>
                            </div>
                        </li>
                        {{-- @endcanany --}}


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
                                    <img src="{{ Auth::user()->getAvatar()}}" alt="user avatar" class="user-avatar rounded-circle mr-2">
                                    <span class="d-none d-lg-inline-block">{{ Auth::user()->getFullName() }}</span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby='dropdownNotifications'>
                                    <a href="{{route('profile')}}" class="dropdown-item">
                                        <div class="option__icon-wrapper">
                                            <div class="option__icon">
                                                <i class="fas fa-user-circle icon"></i>
                                            </div>
                                        </div>
                                        <div class="option__content">
                                            <span class="option__name">Perfil</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                        <div class="option__icon-wrapper">
                                            <div class="option__icon">
                                                <i class="fas fa-sign-out-alt text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="option__content">
                                            <span class="option__name text-danger">Cerrar sesión</span>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
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
                            <span class="text-uppercase page-subtitle">@yield('page-subtitle')</span>
                            <h3 class="page-title">@yield('page-header')</h3>
                        </div>
                    </div>
                    <!--/Page header-->

                    <!--Content-->
                    @yield('content')
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