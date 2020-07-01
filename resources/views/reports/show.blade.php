@extends('layouts.dashboard')
@section('page-subtitle')
    Módulo Informes
@endsection
@section('page-header')
    Informe de actividad
@endsection
@section('item-repot')
    active
@endsection
@section('item-report-collapse')
    show
@endsection
@section('item-report-list')
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
            <div class="card-header">
                    <h4 class="d-inline">Detalle de informe</h4>
                    @can('reports.edit')
                        <a href="{{route('reports.edit',$report->id)}}" class="btn btn-secondary float-right" id='edit'><i class="far fa-edit"></i> Editar</a>
                    @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <p><strong>Título:</strong> {{$report->title}}</p>
                        <p><strong>Descripción:</strong> {{$report->description}}</p>
                        <p><strong>Fecha:</strong> {{$report->created_at}}</p>
                        <p><strong>Estado:</strong> {{$report->state ? 'Activo': 'Inactivo'}}</p>

                        
                        @if (count($images))
                        <p><strong>Imágenes:</strong></p>
                        <small class="text-muted "> <strong>Recuerda:</strong> puedes seleccionar la imágen para verla de tamaño completo</small>
                        <div class="gallery-images">
                            {{-- Se presentan las imágenes seleccionadas por el usuario --}}
                            @foreach ($images as $image)
                            <div class="gallery-item">
                                <a href="{{$image->getLink()}}" target="_blank">
                                    <img src={{$image->getLink()}} alt='image_report_{{$image->id}}'>
                                </a>
                            </div>
                        @endforeach
                        </div>
                        @endif

                        {{-- Se presentan el documento seleccionado por el usuario --}}
                        @if (count($documents))
                        <p><strong>Documento:</strong></p>
                        <small class="text-muted "> <strong>Recuerda:</strong> puedes seleccionar el documento para verlo de tamaño completo</small>
                        <div class="gallery-documents">
                            @foreach ($documents as $document)
                            <div class="gallery-item">
                                
                                <i class="fas fa-file-pdf image-document"></i>
                                <a href="{{$document->getLink()}}" class="link-document" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection