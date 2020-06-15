@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Roles
@endsection
@section('page-header')
    Listados de roles
@endsection
@section('item-role')
    active
@endsection
@section('item-role-collapse')
    show
@endsection
@section('item-role-list')
    active
@endsection
@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>
<div class="row">
    <div class=" col-md-12 col-xl-7">
        <div class="card card-primary">
            <div class="card-header">
                <h4>Sistema web</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($webSystemRoles)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Slug</th>
                                    <th>Descripción</th>
                                    @canany(['roles.show', 'roles.edit'])
                                    <th>Opciones</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($webSystemRoles as $webSystemRole)
                                    <tr>
                                        <td>{{ $webSystemRole->name }}</td>
                                        <td>{{ $webSystemRole->slug }}</td>
                                        <td>{{ $webSystemRole->description ?: 'Sin descripción'}}</td>

                                        @can('roles.show')
                                            <td width='10px'>
                                                <a href="{{route('roles.show',$webSystemRole->id)}}" class="btn btn-info">Ver</a>
                                            </td>
                                        @endcan
                                        @can('roles.edit')
                                            @if ($webSystemRole->slug != 'admin')
                                            <td width='10px'>
                                                <a href="{{route('roles.edit',$webSystemRole->id)}}" class="btn btn-secondary"> Editar</a>
                                            </td>
                                            @endif
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="text-center">Ningún rol registrado</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$webSystemRoles->total()}}</p>
                <nav>
                    {{$webSystemRoles->links()}}
                </nav>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-5">
        <div class="card card-primary">
            <div class="card-header">
                <h4>Aplicación móvil</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col table-responsive">
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    @can(['roles.show'])
                                    <th>Opción</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($appRoles as $appRole)
                                <tr>
                                    <td>{{$appRole->name}}</td>
                                    <td>{{$appRole->description ?: 'Sin descripción'}}</td>
                                    @can('roles.show')
                                        <td width='10px'>
                                            <a href="{{route('roles.show',$appRole->id)}}" class="btn btn-info float-right">Ver</a>
                                        </td>
                                    @endcan
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <p class="text-muted m-0">Total: {{$appRoles->count()}}</p>
            </div>
        </div>
    </div>
</div>
@endsection