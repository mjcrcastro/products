<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Facturación</title>
        <link href="/css/styles.css" rel="stylesheet" />
        <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"  />

        @yield('css')

    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Sistema de Inventario y Facturación</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Reportes</div>
                            <a class="nav-link" href="/products">
                                <svg class="bi" width="16" height="16" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#table"/>
                                </svg>
                                <span>&nbspVentas</span>
                            </a>
                            <div class="sb-sidenav-menu-heading">Registro de Transacciones</div>
                                                        
                            <a class="nav-link" href="/invoices">
                                <svg class="bi" width="16" height="16" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#table"/>
                                </svg>
                                <span>&nbspFacturas</span>
                             </a>
                             <a class="nav-link" href="/purchases">
                                <svg class="bi" width="16" height="16" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#table"/>
                                </svg>
                                <span>&nbspCompras</span>
                             </a>
                            
                            
                            <div class="sb-sidenav-menu-heading">Tablas</div>
                             
                            <a class="nav-link" href="/products">
                                <svg class="bi" width="16" height="16" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#table"/>
                                </svg>
                                <span>&nbspProductos</span>
                            </a>
                             <a class="nav-link" href="/providers">
                                <svg class="bi" width="16" height="16" fill="currentColor">
                                <use xlink:href="/vendor/bootstrap/img/bootstrap-icons.svg#table"/>
                                </svg>
                                <span>&nbspProveedores</span>
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    @yield('main')
                </main>

            </div>
        </div>
        <script src="/js/scripts.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="/vendor/jquery/jquery.min.js"></script>
        <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')
    </body>
</html>
