<div class="form-group @if ($errors->has('barcode')) is-invalid @endif">
    {{ Form::label('buyer_name', 'Nombre del Comprador:') }}
    {{ Form::text('buyer_name', null, array('class="form-control"')) }}
    @if ($errors->has('barcode')) 
    <div class="small alert alert-warning">
        {{ $errors->first('buyer_name', ':message') }} 
    </div>
    @endif
</div>     

<p></p>

{{ Form::submit('Guardar', array('class'=>'btn  btn-primary col-xs-6')) }}
{{ link_to_route('buyers.index', 'Cancelar', [],array('class'=>'btn  btn-outline-secondary col-xs-6')) }}