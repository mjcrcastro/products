@extends('master')

@section('main')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            
        </div>
        <div class="card-body">
            <div class="container-fluid">
                {{ Form::open(array('route'=>'products.store')) }}
                @include('products.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop