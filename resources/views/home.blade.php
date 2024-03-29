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
                    : {{Auth::user()->getFullName()}}</p>
                <p class="text-center"><span class="font-weight-bold">Roles</span>:
                    @foreach (Auth::user()->getWebSystemRoles() as $role)
                        @if ($role->pivot->state)
                            {{$role->name}}<br>
                        @endif
                    @endforeach
                @if (Auth::user()->position)
                    <p class="text-center"><span class="font-weight-bold">Cargo</span>
                        : {{Auth::user()->position->name}}</p>
                @endif
                <img src="{{Auth::user()->getAvatar()}}" alt="user name" class="user_avatar">
            </div>
        </div>
    </div>
</div>
@endsection
