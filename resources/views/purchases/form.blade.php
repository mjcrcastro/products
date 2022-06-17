<div class="form-group @if ($errors->has('barcode')) is-invalid @endif">
    {{ Form::label('name', 'Nombre:') }}
    {{ Form::text('name', null, array('class="form-control"')) }}
    @if ($errors->has('name')) 
    <div class="small alert alert-warning">
        {{ $errors->first('name', ':message') }} 
    </div>
    @endif
</div>     

<div class="form-group @if ($errors->has('description')) is-invalid @endif">
    {{ Form::label('address', 'Dirección:') }}
    {{ Form::text('address', null, array('class="form-control"')) }}
    @if ($errors->has('address')) 
    <div class="small alert alert-warning">
        {{ $errors->first('address', ':message') }} 
    </div>
    @endif
</div>
<div class="form-group @if ($errors->has('price')) is-invalid @endif">
    {{ Form::label('whatsapp', 'WhatsApp:') }}
    {{ Form::text('whatsapp', null, array('class="form-control"')) }}
    @if ($errors->has('price')) 
    <div class="small alert alert-warning">
        {{ $errors->first('whatsapp', ':message') }} 
    </div>
    @endif
</div>

<div class="form-group @if ($errors->has('price')) is-invalid @endif">
    {{ Form::label('notes', 'Notas:') }}
    {{ Form::text('notes', null, array('class="form-control"')) }}
    @if ($errors->has('notes')) 
    <div class="small alert alert-warning">
        {{ $errors->first('notes', ':message') }} 
    </div>
    @endif
</div>

<p></p>

{{ Form::submit('Guardar', array('class'=>'btn  btn-primary col-xs-6')) }}
{{ link_to_route('providers.index', 'Cancelar', [],array('class'=>'btn  btn-outline-info col-xs-6')) }}