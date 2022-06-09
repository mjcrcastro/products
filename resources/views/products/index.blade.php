@extends('master')

@section('css')
<!-- Custom styles for this page -->
<link rel="stylesheet" type="text/css" href="/vendor/datatables/css/dataTables.bootstrap4.min.css"/>
<link href="/vendor/datatables/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
@stop

@section('main')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid px-4">
    <h1 class="mt-4">Productos</h1> 
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.html">Inventario</a></li>
        <li class="breadcrumb-item active">Productos</li> 
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Listado de Productos
        </div>
        <div class="card-body">
            <table class="table display" id="productsTable" width="100%" cellspacing="0">
                <thead>
                    <tr >
                        <th></th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Precio</th>
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
                        <a class="btn btn-block text-nowrap btn-primary" href="{{ route('products.create')}}" role="button">Nuevo Producto  
                            <svg class="bi" width="24" height="24" fill="currentColor">
                            <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#plus-circle"/>
                            </svg>
                        </a>
                    </div>
                    <div id="showCities" class="col-md">
                        <a class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Ver Producto 
                            <svg aling ="class="bi" width="24" height="24" fill="currentColor">
                            <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#arrow-right-circle"/>
                            </svg>
                        </a>
                    </div>
                    <div id="editProduct" class="col-md">
                        <a class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Editar
                            <svg class="bi" width="24" height="24" fill="currentColor">
                            <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#pencil-square"/>
                            </svg>
                        </a>
                    </div>
                    <div id ="deleteProduct" class="col-md">
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

    @stop

    @section('scripts')

    <!-- Page level plugins -->
    <script type="text/javascript" src="/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="/vendor/datatables/js/dataTables.select.min.js" type="text/javascript"></script>
    <!-- Page level custom scripts -->
    <script type='text/javascript'>
    /*
     * Displays list of products using
     * a datatables jQuery plugin on table id="example"
     */
    $(document).ready(function () {
        var editButton = $('#editProduct');
        var deleButton = $('#deleteProduct');
        var table = $('#productsTable').DataTable({
            "processing": true,
            "serverSide": true,
            "select": {
                style: 'single'
            },
            "ajax": {
                "url": "{{ url('/products_ajax') }}",
                "type": "GET",
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                }
            ],
            "columns": [//tells where (from data) the columns are to be placed
                {"data": "id"},
                {"data": "barcode"},
                {"data": "description"},
                {"data": "price"}
            ]
        });
        table //here we change 
                .on('select', function (e, dt, type, indexes) {
                    var rowData = table.rows(indexes).data().toArray();
                    editButton.html('<a class="btn btn-block text-nowrap btn-primary" href="/products/' + rowData[0]['id'] + '/edit" role="button">Editar <svg class="bi" width="24" height="24" fill="currentColor"><use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#pencil-square"/></svg></a>');
                    deleButton.html('<form method="POST" action="/products/' + rowData[0]['id'] + '" accept-charset="UTF-8">' +
                            '<input name="_method" type="hidden" value="DELETE">' +
                            '<input name="_token" type="hidden" value="' + $('meta[name="csrf-token"]').attr('content') + '">' +
                            '<button class="btn btn-block text-nowrap btn-primary " onclick="if(!confirm(&#039;Are you sure to delete this item?&#039;)){return false;};" type="submit" value="Delete">Borrar <svg class="bi" width="24" height="24" fill="currentColor"><use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#x-circle"/></svg></button>' +
                            '</form>');
                })
                .on('deselect', function (e, dt, type, indexes) {
                    editButton.html('<a class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Editar <svg class="bi" width="24" height="24" fill="currentColor"><use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#pencil-square"/></svg></a>');
                    deleButton.html('<a class="btn btn-block text-nowrap btn-disabled" href="#" role="button">Borrar <svg class="bi" width="24" height="24" fill="currentColor"><use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#x-circle"/></svg></a>');
                })
                .on('search.dt', function () {
                    table.rows('.selected').deselect();
                });
    });
    </script>


    @stop