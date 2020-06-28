@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Emergencias
@endsection
@section('page-header')
    Emergencias reportadas
@endsection
@section('item-emergency')
    active
@endsection
@section('item-emergency-collapse')
    show
@endsection
@section('item-emergency-list')
    active
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
                <form action="{{route('search.emergencies')}}" method="GET">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select class="custom-select @error('searchOption') is-invalid @enderror" name="searchOption" required>
                                <option value="">Buscar</option>
                                <option value="1"
                                @if (old('searchOption')== 1 || request('searchOption')== 1)
                                    {{'selected'}}
                                @endif
                                >Título</option>
                            </select>
                            
                        </div>
                        <input type="text" class="form-control @error('searchValue') is-invalid @enderror"  name="searchValue" value="{{old('searchValue') ?: request('searchValue')}}" maxlength="50" required>
                        
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @error('searchOption')
                            <span class="invalid-feedback d-inline" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('searchValue')
                            <span class="invalid-feedback d-inline" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="d-inline">Lista de emergencias</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="row mb-2">
                    @error('filterOption')
                        <span class="invalid-feedback d-inline text-center mb-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="col text-center">
                        @php
                            $searchOption = request()->query('searchOption');
                            $searchValue = request()->query('searchValue');
                        @endphp
                        <a href="{{route('emergencies.index')}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Todos</a>
                        <a href="{{route('search.emergencies', [
                            'filterOption'=>1, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Atentidos</a>
                        <a href="{{route('search.emergencies', [
                            'filterOption'=>2, 
                            'searchOption' => $searchOption, 
                            'searchValue' => $searchValue
                        ])}}" class="btn btn-outline-dark btn-sm ml-1 mr-1"><i class="fas fa-filter"></i> Rechazados</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col table-responsive">
                        @if (count($emergencies)>0)
                        <table class="table table-light table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Fecha</th>

                                    @canany(['emergencies.show'])
                                    <th>Opción</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($emergencies as $emergency)
                                    <tr>
                                        <td>{{ $emergency->title }}</td>
                                        <td>{{ $emergency->created_at }}</td>

                                        @can('emergencies.show')
                                        <td width='10px'>
                                            <a href="{{route('emergencies.show', $emergency->id)}}" class="btn btn-info">Ver</a>
                                        </td>
                                        @endcan

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="text-center">Nigún registro de emergencia</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-muted m-0 float-right">Total: {{$emergencies->total()}}</p>
                <nav>
                    {{$emergencies->appends(request()->query())->links()}}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection