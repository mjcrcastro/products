@extends('master')

@section('main')
<div class="container-fluid px-4">
    <div class="card mt-4">
        <div class="card-header mb-0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md">
                        <ol id="breadCrumbs" class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="./">Listado de Provedores</a></li>
                            <li class="breadcrumb-item active">Proveedor {{ $provider->name  }}</li> 
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-0">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><b>Nombre:</b> {{ $provider->name }}</li>
            <li class="list-group-item"><b>Dirección: </b>{{ $provider->address }}</li>
            <li class="list-group-item"><b>WhatsApp: </b>{{ $provider->whatsapp }}</li>
            <li class="list-group-item"><b>Notas: </b>{{ $provider->notes }}</li>
        </ul>
    </div>
    <div class="card shadow">
        <div class="card-header py-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md">
                        {{ link_to_route('providers.index','Volver',null,array('id'=>'purchasesIndex','class'=>'btn col-12 text-nowrap btn-outline-secondary')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@stop