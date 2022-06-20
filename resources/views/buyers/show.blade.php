@extends('master')

@section('main')
<ol id="breadCrumbs" class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
    <li class="breadcrumb-item"><a href="./">Listado de Compradores</a></li>
    <li class="breadcrumb-item active">Comprador {{ $buyer->buyer_name  }}</li> 
</ol>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="container-fluid">
            <div class="col-sm">
                <h6 class="m-0 font-weight-bold text-secondary"> Tarjeta de Comprador  </h6>
            </div>
        </div>
    </div>
    <div class="card-body">

        <ul class="list-group list-group-flush">
            <li class="list-group-item"><b>Nombre:</b> {{ $buyer->buyer_name }}</li>
        </ul>
    </div>
</div>

@stop