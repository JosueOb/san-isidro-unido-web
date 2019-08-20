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
        <div class="card card-primary">
            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('danger'))
                <div class="alert alert-danger" role="alert">
                        {{ session('danger') }}
                    </div>
                @endif
                <h3 class="text-center"> Bienvenido</h3>
                <p class="text-center"><span class="font-weight-bold">Usuario</span>
                    : {{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>
                <p class="text-center"><span class="font-weight-bold">Rol</span>: {{Auth::user()->getRol() ? Auth::user()->getRol()->name : 'Sin rol'}}</p>
                <img src="{{Auth::user()->avatar}}" alt="user name" class="rounded-circle d-block mr-auto ml-auto">
                
            </div>
        </div>
    </div>
</div>
@endsection
