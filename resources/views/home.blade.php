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
                <h3 class="text-center"> Bienvenido</h3>
                <p class="text-center"><span class="font-weight-bold">Usuario</span>
                    : {{$user->first_name}} {{$user->last_name}}</p>
                <p class="text-center"><span class="font-weight-bold">Rol</span>: {{$user->roles[0]->name}}</p>
                <img src="{{$user->avatar}}" alt="user name" class="rounded-circle d-block mr-auto ml-auto">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
