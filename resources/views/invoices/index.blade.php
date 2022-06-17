@extends('master')

@section('css')
<!-- Custom styles for this page -->
<link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="/vendor/datatables/css/dataTables.bootstrap4.min.css"/>
<link href="/vendor/datatables/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
@stop

@section('main')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid px-4">
    <h1 class="mt-4">Facturas</h1> 


    <div class="card mb-4">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.html">Inventario</a></li>
            <li class="breadcrumb-item active">Listado de Facturas</li> 
        </ol>
        <div class="card-body">
            <table class="table display" id="invoicesTable" width="100%" cellspacing="0">
                <thead>
                    <tr >
                        <th></th>
                        <th>Factura Móvil</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Factura Móvil</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md">
                            <a class="btn btn-block text-nowrap btn-primary" href="{{ route('invoices.create')}}" role="button">Nueva Factura  
                                <svg class="bi" width="24" height="24" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#plus-circle"/>
                                </svg>
                            </a>
                        </div>
                        <div class="col-md">
                            <a id="showInvoice" class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Ver 
                                <svg aling ="class="bi" width="24" height="24" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#arrow-right-circle"/>
                                </svg>
                            </a>
                        </div>
                        <div class="col-md">
                            <a id="editInvoice" class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Editar
                                <svg class="bi" width="24" height="24" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#pencil-square"/>
                                </svg>
                            </a>
                        </div>
                        <div id ="deleteInvoice" class="col-md">
                            <a class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Borrar
                                <svg class="bi" width="24" height="24" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#x-circle"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@stop

@section('scripts')

<!-- Page level plugins -->
<script type="text/javascript" src="/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/vendor/datatables/js/dataTables.select.min.js" type="text/javascript"></script>
<!-- Page level custom scripts -->
<script type='text/javascript'>
/*
 * Displays list of invoices using
 * a datatables jQuery plugin on table id="example"
 */
$(document).ready(function () {
    var showButton = $('#showInvoice');
    var editButton = $('#editInvoice');
    var deleDiv = $('#deleteInvoice');
    var table = $('#invoicesTable').DataTable({
        "processing": true,
        "serverSide": true,
        "select": {
            style: 'single'
        },
        "ajax": {
            "url": "{{ url('/invoices_index') }}",
            "type": "GET",
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        "columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [4],
                render: $.fn.dataTable.render.number(',', '.', 2, ''),
                className: 'text-right'
            }
        ],
        "columns": [//tells where (from data) the columns are to be placed
            {"data": "id"},
            {"data": "invoicenumber_mobile"},
            {"data": "customername"},
            {"data": "invoicedate"},
            {"data": "invoicetotal"}
        ]
    });
    table //here we change 
            .on('select', function (e, dt, type, indexes) {
                var rowData = table.rows(indexes).data().toArray();
                //manage the edit button            
                editButton.attr('href', '/invoices/' + rowData[0]['id'] + '/edit');
                editButton.addClass('btn-primary');
                editButton.removeClass('btn-disabled');
                //manage show button
                showButton.attr('href', '/invoices/' + rowData[0]['id']);
                showButton.addClass('btn-primary');
                showButton.removeClass('btn-disabled');

                //manage the delete button
                deleDiv.html('<form method="POST" action="/invoices/' + rowData[0]['id'] + '" accept-charset="UTF-8">' +
                        '<input name="_method" type="hidden" value="DELETE">' +
                        '<input name="_token" type="hidden" value="' + $('meta[name="csrf-token"]').attr('content') + '">' +
                        '<button class="btn btn-block text-nowrap btn-primary " onclick="if(!confirm(&#039;Are you sure to delete this item?&#039;)){return false;};" type="submit" value="Delete">Borrar <svg class="bi" width="24" height="24" fill="currentColor"><use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#x-circle"/></svg></button>' +
                        '</form>');
            })
            .on('deselect', function (e, dt, type, indexes) {
                //manage edit button
                editButton.attr('href', '#'); //remove href
                editButton.removeClass('btn-primary'); //remove primary class
                editButton.addClass('btn-disabled');  //add disabled class
                //manage show button
                showButton.attr('href', '#'); //remove href
                showButton.removeClass('btn-primary'); //remove primary class
                showButton.addClass('btn-disabled');  //add disabled class

                deleDiv.html('<a class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Borrar <svg class="bi" width="24" height="24" fill="currentColor"><use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#x-circle"/></svg></a>');

                //deleButton.html('<a class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Borrar <svg class="bi" width="24" height="24" fill="currentColor"><use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#x-circle"/></svg></a>');
            })
            .on('search.dt', function () {
                table.rows('.selected').deselect();
            });
});



</script>


@stop