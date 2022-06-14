<div class="form-group @if ($errors->has('barcode')) is-invalid @endif">
    {{ Form::label('barcode', 'Código:') }}
    {{ Form::text('barcode', null, array('class="form-control"')) }}
    @if ($errors->has('barcode')) 
    <div class="small alert alert-warning">
        {{ $errors->first('barcode', ':message') }} 
    </div>
    @endif
</div>     

<div class="form-group @if ($errors->has('description')) is-invalid @endif">
    {{ Form::label('description', 'Descripción:') }}
    {{ Form::text('description', null, array('class="form-control"')) }}
    @if ($errors->has('description')) 
    <div class="small alert alert-warning">
        {{ $errors->first('description', ':message') }} 
    </div>
    @endif
</div>
<div class="form-group @if ($errors->has('price')) is-invalid @endif">
    {{ Form::label('price', 'Precio:') }}
    {{ Form::text('price', null, array('class="form-control"')) }}
    @if ($errors->has('price')) 
    <div class="small alert alert-warning">
        {{ $errors->first('price', ':message') }} 
    </div>
    @endif
</div>

<p></p>

{{ Form::submit('Guardar', array('class'=>'btn  btn-primary col-xs-6')) }}
{{ link_to_route('products.index', 'Cancelar', [],array('class'=>'btn  btn-outline-info col-xs-6')) }}