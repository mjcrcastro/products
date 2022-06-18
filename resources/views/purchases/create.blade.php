@extends('master')

@section('css')
<!-- Custom styles for this page -->
<link href="/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="/vendor/datatables/css/dataTables.bootstrap4.min.css"/>
<link href="/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>

<link href="/vendor/datatables/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
@stop

@section('main')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid px-4">
    <h1 class="mt-4">Nueva Compra</h1> 

    <div class="card mb-4">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.html">Inventario</a></li>
            <li class="breadcrumb-item"><a href=" {{ route('purchases.index')}} ">Listado de Compras</a></li>
            <li class="breadcrumb-item active">Nueva Compra</li> 
        </ol>
        <div class="card-body">
            <table class="table display" id="purchasesTable" width="100%" cellspacing="0">
                <thead>
                    <tr >
                        <th></th>
                        <th></th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Producto</label>
                        </div>
                        <select class="custom-select form-control" id="productSelect">

                        </select>
                    </div>
                </div>

                <div  class="col-md">
                    <div class="input-group mb-3">
                        <input id="amountId" type="text" class="form-control" placeholder="Cantidad" aria-label="Cantidad" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div id ="cost" class="col-md">
                    <div class="input-group mb-3">
                        <input id="costId" type="text" class="form-control" placeholder="Costo" aria-label="Costo" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <a id="addProduct" class="btn btn-block text-nowrap btn-primary" href="#" role="button">Agregar Producto  
                        <svg class="bi" width="24" height="24" fill="currentColor">
                        <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#plus-circle"/>
                        </svg>
                    </a>
                </div>

                <div  class="col-md btn-disabled">
                    <a id="editProvider" class="btn btn-block text-nowrap" href="#" role="button">Guardar Compra
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                            <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                    </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

@stop

@section('scripts')

<!-- Page level plugins -->
<script type="text/javascript" src="/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="/vendor/select2/js/select2.full.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/vendor/datatables/js/dataTables.select.min.js" type="text/javascript"></script>
<!-- Page level custom scripts -->
<script type='text/javascript'>
/*
 * Displays list of products using
 * a datatables jQuery plugin on table id="purchasesTable"
 */
$(document).ready(function () {
    var editButton = $('#editProvider');
    var showButton = $('#showProvider');
    var deleDiv = $('#deleteProvider');
    var counter = 0;
    var table = $('#purchasesTable').DataTable({
        "select": {
            style: 'single'
        },
        "columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [1],
                "visible": false,
                "searchable": false
            }
        ]
    });
    table //here we change 
            .on('search.dt', function () {
                table.rows('.selected').deselect();
            });

    $("#productSelect").select2({
        theme: "bootstrap4",
        selectOnClose: true,
        ajax: {
            url: '{{ url("/select2ajax")  }}',
            dataType: 'json'
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    $('#addProduct').on('click', function () {

       if (isValid($('#productSelect').val(), $('#amountId').val(), $('#costId').val())) {
        table.row.add([
            counter,
            $('#productSelect').val(),
            $('#productSelect option:selected').text(),
            $('#amountId').val(),
            $('#costId').val()]
            ).draw(false);

        //clean up values
        $('#productSelect').text(null);
        $('#productSelect').val(null);
        $('#amountId').val(null);
        $('#costId').val(null);
        
        $('#productSelect').select2('open');

        counter++;

        }else{
        alert('Registro no válido');
       }
        
    });

    function isValid (oProduct, oAmount, oCost) {
        validity = true; 
        $('#costId').removeClass('is-invalid');
        $('#amountId').removeClass('is-invalid');
        $('#productSelect').removeClass('is-invalid');
        if (!$.isNumeric(oProduct)) {
            $('#productSelect').addClass('is-invalid');
            validity = false;
         }  
         if (!$.isNumeric(oAmount) || oAmount <= 0) {
            $('#amountId').addClass('is-invalid');
            validity = false;
         } 

         if (!$.isNumeric(oCost) || oCost <= 0 ) {
            $('#costId').addClass('is-invalid');
            validity = false;
         }  

         return validity;
    }
});
</script>

@stop
