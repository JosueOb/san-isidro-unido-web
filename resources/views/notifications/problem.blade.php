@extends('layouts.dashboard')
@section('page-subtitle')
    Notificaciones
@endsection
@section('page-header')
    Problemas sociales
@endsection
@section('content')
<div class="row">
    <div class="col">
        @include('layouts.alerts')
    </div>
</div>

<div class="row">
    <div class="col">
       <p>Lista de notificaciones de problemas sociales</p>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card card-primary">
            {{-- <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Servicios públicos</h4>
                        @can('publicServices.create')
                        <a href="{{route('publicServices.create')}}" class="btn btn-primary float-right">Nuevo</a>
                        @endcan
                    </div>
                </div>
            </div> --}}
            <div class="card-body">
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($all_problem_notifications)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    {{-- <th>Título</th> --}}
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    {{-- <th>Categoría</th>
                                    <th>Descripción</th> --}}
                                    {{-- @canany(['publicServices.edit','publicServices.destroy']) --}}
                                    <th>Opción</th>
                                    {{-- @endcanany --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_problem_notifications as $notification)
                                    <tr>
                                        {{-- <td>{{$notification->data['title']}}</td> --}}
                                        <td>{{$notification->data['description']}}</td>

                                        <td>
                                            @if ($notification->read_at)
                                                Leída
                                            @else
                                                Pendiente
                                            @endif
                                        </td>

                                        {{-- <td>{{$publicService->name}}</td>
                                        <td>{{$publicService->subcategory->name}}</td> --}}

                                        {{-- @can('publicServices.show') --}}
                                        <td width='10px'>
                                            <a href="{{route('request.socialProblem', [$notification->data['post']['id'], $notification->id])}}" class="btn btn-info">Ver</a>
                                        </td>
                                        {{-- @endcan --}}

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="text-center">Niguna notificación registrada</p>
                        @endif
                    </div>
                </div>
            </div>
            {{-- <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$publicServices->total()}}</p>
                <nav>
                    {{$publicServices->links()}}
                </nav>
            </div> --}}
        </div>
    </div>
</div>
@endsection