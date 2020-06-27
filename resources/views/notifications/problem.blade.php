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
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Lista de notificaciones</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col table-responsive">
                        @if (count($all_problem_notifications)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_problem_notifications as $notification)
                                    <tr>
                                        <td>{{$notification->data['description']}}</td>
                                        <td>{{$notification->created_at}}</td>
                                        <td>
                                            @if ($notification->read_at)
                                                Leída
                                            @else
                                                <strong>Pendiente</strong>
                                            @endif
                                        </td>

                                        {{-- @can('publicServices.show') --}}
                                        <td width='10px'>
                                            <a href="{{route('socialProblemReport.show', $notification->id)}}" class="btn btn-info">Ver</a>
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
            @if (count($all_problem_notifications)>0)
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$all_problem_notifications->total()}}</p>
                <nav>
                    {{$all_problem_notifications->links()}}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection