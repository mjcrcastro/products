@extends('master')

@section('main')

<div class="container-fluid px-4">

    <div class="card shadow mt-4 mb-0">
        <div class="card-header mb-0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md">
                        <ol id="breadCrumbs" class="breadcrumb ml-4 mt-4 mb-4">
                            <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="./">Listado de Compradores</a></li>
                            <li class="breadcrumb-item active">Comprador {{ $buyer->buyer_name  }}</li> 
                        </ol>
                    </div>
                </div>
            </div>
        </div>



    </div>

    <div class="card">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><b>Nombre:</b> {{ $buyer->buyer_name }}</li>
        </ul>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md">
                        {{ link_to_route('buyers.index','Volver',null,array('id'=>'purchasesIndex','class'=>'btn col-12 text-nowrap btn-outline-secondary')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@stop