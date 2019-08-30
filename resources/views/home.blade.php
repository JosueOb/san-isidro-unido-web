@extends('layouts.dashboard')
@section('page-subtitle')
    San Isidro Unido
@endsection
@section('page-header')
    Sistema Web
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
            <div class="card-body">
                <h3 class="text-center"> Bienvenido</h3>
                <p class="text-center"><span class="font-weight-bold">Usuario</span>
                    : {{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>
                <p class="text-center"><span class="font-weight-bold">Rol</span>: {{Auth::user()->getRol() ? Auth::user()->getRol()->name : 'Sin rol'}}</p>
                @if (Auth::user()->position)
                    <p class="text-center"><span class="font-weight-bold">Cargo</span>
                        : {{Auth::user()->position->name}}</p>
                @endif
                <img src="{{Auth::user()->avatar}}" alt="user name" class="rounded-circle d-block mr-auto ml-auto">
            </div>
        </div>
    </div>
</div>
@endsection
