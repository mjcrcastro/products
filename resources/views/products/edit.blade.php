@extends('master')

@section('main')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="container-fluid">
                <div class="col-sm">
                    <h6 class="m-0 font-weight-bold text-secondary"> Editar Producto {{ $product->description }} </h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                {{ Form::model($product, array('method'=>'PATCH', 'route'=> array('products.update', $product->id)))  }}
                @include('products.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@stop