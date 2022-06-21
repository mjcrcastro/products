@extends('master')

@section('css')
<!-- Custom styles for this page -->
<link href="/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="/vendor/datatables/css/dataTables.bootstrap4.min.css"/>
<link href="/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>

<link href="/vendor/datatables/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
@stop


@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="editingMode" content ="{{ $editingMode }}">
<meta name="purchaseId" content ="{{ $id ?? '' }}">
@stop

@section('main')
<div class="container-fluid px-4">
    <h1 class="mt-4">Nueva Compra</h1> 

    <div class="card mb-4">
        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="index.html">Inventario</a></li>
            <li class="breadcrumb-item"><a href=" {{ route('purchases.index')}} ">Listado de Compras</a></li>
            <li class="breadcrumb-item active">Nueva Compra</li> 
        </ol>
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Proveedor</label>
                        </div>
                        <select class="custom-select form-control" id="providerSelect">
                        </select>
                    </div>
                </div>

                <div class="col-md">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Comprador</label>
                        </div>
                        <select class="custom-select form-control" id="buyerSelect">
                        </select>
                    </div>
                </div>

                <div  class="col-md">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Factura</label>
                        </div>
                        <input id="purchaseInvoiceNumber" type="text" class="form-control" placeholder="Factura de Proveedor" aria-label="Cantidad" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div id ="cost" class="col-md">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Fecha</label>
                        </div>
                        <input id="purchaseDate" type="date" class="form-control" placeholder="Fecha de Compra" aria-label="Costo" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>

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
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Cantidad</label>
                        </div>
                        <input id="amountId" type="text" class="form-control" placeholder="Cantidad comprada" aria-label="Cantidad" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div id ="cost" class="col-md">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Costo</label>
                        </div>
                        <input id="costId" type="text" class="form-control" placeholder="Costo total (no unitario)" aria-label="Costo" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md">
                    <a id="cancel" class="btn col-12 text-nowrap btn-secondary" href="/purchases/" role="button">
                        <svg class="bi" width="24" height="24" fill="currentColor">
                        <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#arrow-left"/>
                        </svg>
                        Cancelar  
                    </a>
                </div>

                <div class="col-md">
                    <a id="addProduct" class="btn col-12 text-nowrap btn-primary" href="#" role="button">Agregar Producto  
                        <svg class="bi" width="24" height="24" fill="currentColor">
                        <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#plus-circle"/>
                        </svg>
                    </a>
                </div>
                
                <div class="col-md">
                    <a id="deleteProduct" class="btn col-12 text-nowrap btn-warning" href="#" role="button">Borrar Producto  
                        <svg class="bi" width="24" height="24" fill="currentColor">
                        <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#x-circle"/>
                        </svg>
                    </a>
                </div>

                <div  class="col-md btn-disabled">
                    <a id="saveButton" class="btn col-12 text-nowrap  btn-primary" href="#" role="button">Guardar Compra
                        <svg class="bi" width="24" height="24" fill="currentColor">
                        <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#save"/>
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
    var counter = 1;
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
            },
            {
                "targets": [2],
                "visible": true,
                "className": 'text-left'
            },
            {
                "targets": [3],
                "visible": true,
                "className": 'text-right',
                "render": $.fn.dataTable.render.number(',', '.', 2, '')//formats the number
            },
            {
                "targets": [4],
                "visible": true,
                "className": 'text-right',
                "render": $.fn.dataTable.render.number(',', '.', 2, '')//formats the number
            }
            
        ]
    });

    table //here we change 
            .on('search.dt', function () {
                table.rows('.selected').deselect();
            });
            
            
    //remove row when told so
    $('#deleteProduct').click(function () {
        table.row('.selected').remove().draw(false);
    });
    
    $("#productSelect").select2({
        theme: "bootstrap4",
        placeholder: "Seleccione el producto",
        selectOnClose: true,
        ajax: {
            url: '{{ url("/select2ajax")  }}',
            dataType: 'json'
        }
    });
    $("#providerSelect").select2({
        theme: "bootstrap4",
        placeholder: "Seleccione el proveedor",
        selectOnClose: true,
        ajax: {
            url: '{{ url("/select_providers_ajax")  }}',
            dataType: 'json'
        }
    });
    $("#buyerSelect").select2({
        theme: "bootstrap4",
        placeholder: "Seleccione el comprador",
        selectOnClose: true,
        ajax: {
            url: '{{ url("/select_buyers_ajax")  }}',
            dataType: 'json'
        }
    });
    $('#addProduct').on('click', function () {

        if (isValid($('#productSelect').val(), $('#amountId').val(), $('#costId').val(), true)) {

            dtRow = new Array();
            drRow = [
                counter,
                $('#productSelect').val(),
                $('#productSelect option:selected').text(),
                $('#amountId').val(),
                $('#costId').val()];
            table.row.add(drRow).draw(false);
//clean up values
            $('#productSelect').val(null).trigger('change');
            $('#amountId').val(null);
            $('#costId').val(null);
            $('#productSelect').select2('open');
            counter++;
        } else {
            alert('Registro no válido');
        }

    });
    function isValid(oProduct, oAmount, oCost, flagOnError) {
//validates product, amount and cost values, 
//when flagOnError is true, it also updates 
//the UI to notify of errors
        validity = true;
        $('#costId').removeClass('is-invalid');
        $('#amountId').removeClass('is-invalid');
        $('#productSelect').removeClass('is-invalid');
        if (!$.isNumeric(oProduct)) {
            if (flagOnError) {
                $('#productSelect').addClass('is-invalid');
            }
            validity = false;
        }

        if (!$.isNumeric(oAmount) || oAmount <= 0) {
            if (flagOnError) {
                $('#amountId').addClass('is-invalid');
            }
            validity = false;
        }

        if (!$.isNumeric(oCost) || oCost <= 0) {
            if (flagOnError) {
                $('#costId').addClass('is-invalid');
            }
            validity = false;
        }

        return validity;
    }

    //posting purchase to the server
    $('#saveButton').on('click', function () {
        var rowsData = table.rows().data().toArray();

        if ($('meta[name="editingMode"]').attr('content') === 'create') {
            //when creating new purchase
            var jsonDataNew = {"provider_id": $('#providerSelect').val(),
                "buyer_id": $('#buyerSelect').val(),
                "purchase_date": $('#purchaseDate').val(),
                "purchase_invoice": $('#purchaseInvoiceNumber').val(),
                "products": rowsData};
            $.ajax({
                type: "POST",
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                contentType: "application/json",
                url: "{{ url('/purchases') }}",
                data: JSON.stringify(jsonDataNew),
                success: function (data, status, xhr) {
                    alert('Compra Registrada');
                    window.location.href = '{{ url("/purchases")  }}';
                },
                async: false,
                dataType: 'json'
            });
        } else if ($('meta[name="editingMode"]').attr('content') === 'edit') {
            //When updading an existing purchase
            //add purchase_id
            debugger;
            var jsonDataEdit = {"provider_id": $('#providerSelect').val(),
                "buyer_id": $('#buyerSelect').val(),
                "purchase_id": $('meta[name="purchaseId"]').attr('content'),
                "purchase_date": $('#purchaseDate').val(),
                "purchase_invoice": $('#purchaseInvoiceNumber').val(),
                "products": rowsData};
            debugger;
            $.ajax({
                type: "PATCH",
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/purchases') }}/" + $('meta[name="purchaseId"]').attr('content'),
                data: JSON.stringify(jsonDataEdit),
                success: function (data, status, xhr) {
                    alert('Compra Actualizada');
                    window.location.href = '{{ url("/purchases")  }}';
                },
                async: false,
                dataType: 'json'});

        }
    });
    //retrieve purchase when in editing mode
    if ($('meta[name="editingMode"]').attr('content') === 'edit') {
        //Retrieve the Purchase data, 
        $.ajax({
            type: "GET",
            contentType: "application/json",
            url: "{{ url('/api/get_purchase') }}/" + $('meta[name="purchaseId"]').attr('content'),
            success: function (data, status, xhr) {
                debugger;
                //first the header
                //need to add the option first, otherwise option will not be selected even when asked to do so
                //for Provider
                var newOptionProvider = new Option(data['purchase']['provider_name'], data['purchase']['provider_id'], false, true);
                //for Buyer
                var newOptionBuyer = new Option(data['purchase']['buyer_name'], data['purchase']['buyer_id'], false, true);
                //
                $('#providerSelect').append(newOptionProvider);
                $('#buyerSelect').append(newOptionBuyer);
                $('#purchaseInvoiceNumber').val(data['purchase']['purchase_invoice_number']);
                $('#purchaseDate').val(data['purchase']['purchase_date']);
                debugger;
                for (var i = 0; i < data['purchase_details'].length; i++) {
                    dtRow = new Array();
                    drRow = [
                        counter,
                        data['purchase_details'][i]['product_id'],
                        data['purchase_details'][i]['product_description'],
                        data['purchase_details'][i]['amount'],
                        data['purchase_details'][i]['cost']
                    ];
                    table.row.add(drRow).draw(false);
                    counter++;
                }

            },
            async: false,
            dataType: 'json'
        });
    }
    ;
});
</script>
@stop


