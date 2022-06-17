@extends('master')

@section('main')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="container-fluid">
                <div class="col-sm">
                    <h6 class="m-0 font-weight-bold text-secondary"> Editar Proveedor {{ $provider->description }} </h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                {{ Form::model($provider, array('method'=>'PATCH', 'route'=> array('providers.update', $provider->id)))  }}
                @include('providers.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@stop