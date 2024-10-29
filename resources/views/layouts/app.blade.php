<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>@yield('title', 'Simlab Elektro')</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* Warna baru yang lebih modern dan elegan */
            :root {
                --primary-color: #147be1;
                --secondary-color: #000000;
                --accent-color: #ff0000;
                --hover-color: #df1414;
            }

            .sb-sidenav {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            }
            
            .sb-sidenav-dark .sb-sidenav-menu .nav-link {
                color: #ffffff !important;
                font-weight: 500;
                border-left: 3px solid transparent;
                transition: all 0.3s ease;
            }
            
            .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
                color: #ffffff !important;
                background-color: var(--hover-color);
                border-left: 3px solid #ffffff;
            }
            
            .sb-sidenav-dark .sb-sidenav-menu .nav-link.active {
                color: #ffffff !important;
                background-color: var(--accent-color);
                border-left: 3px solid #ffffff;
            }
            
            .sb-topnav {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .sb-topnav .navbar-brand {
                color: #ffffff !important;
                font-weight: 600;
                letter-spacing: 0.5px;
            }
            
            .sb-topnav .nav-link {
                color: #ffffff !important;
                font-weight: 500;
            }
            
            .sb-topnav .nav-link:hover {
                color: var(--accent-color) !important;
            }
            
            .sb-sidenav-dark .sb-sidenav-footer {
                background-color: var(--secondary-color);
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            /* Tambahan styling untuk membuat tampilan lebih modern */
            .sb-sidenav-menu-heading {
                color: rgba(255, 255, 255, 0.7) !important;
                font-size: 0.8rem;
                letter-spacing: 1px;
                text-transform: uppercase;
            }

            .sb-nav-fixed #layoutSidenav #layoutSidenav_nav {
                box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        {{-- navbar --}}
        @extends('layouts._partials.navbar')

        {{-- sidebar --}}
        @extends('layouts._partials.sidebar')
        
        {{-- @extends('layouts._partials.footer') --}}
       
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        @stack('scripts')
    </body>
</html>
