@extends('master')

@section('main')
<ol id="breadCrumbs" class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
    <li class="breadcrumb-item"><a href="./">Listado de Provedores</a></li>
    <li class="breadcrumb-item active">Proveedor {{ $provider->name  }}</li> 
</ol>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="container-fluid">
            <div class="col-sm">
                <h6 class="m-0 font-weight-bold text-secondary"> Tarjeta de Proveedor  </h6>
            </div>
        </div>
    </div>
    <div class="card-body">

        <ul class="list-group list-group-flush">
            <li class="list-group-item"><b>Nombre:</b> {{ $provider->name }}</li>
            <li class="list-group-item"><b>Dirección: </b>{{ $provider->address }}</li>
            <li class="list-group-item"><b>WhatsApp: </b>{{ $provider->whatsapp }}</li>
            <li class="list-group-item"><b>Notas: </b>{{ $provider->notes }}</li>
        </ul>
    </div>
</div>

@stop