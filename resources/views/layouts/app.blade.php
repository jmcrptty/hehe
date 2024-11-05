<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title', 'Simlab Elektro')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        /* Warna baru yang lebih modern dan elegan */
        :root {
            --primary-color: #147be1;
            --secondary-color: #000000;
            --accent-color: #ff0000;
            --hover-color: #df1414;
        }

        body {
            overflow-x: hidden;
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

        #layoutSidenav {
            display: flex;
        }

        #layoutSidenav_nav {
            flex-basis: 225px;
            flex-shrink: 0;
            transition: transform .15s ease-in-out;
            z-index: 1038;
            transform: translateX(0);
        }

        #layoutSidenav_content {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            flex-grow: 1;
            min-height: calc(100vh - 56px);
            margin-left: 225px;
            padding: 20px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            #layoutSidenav_nav {
                transform: translateX(-225px);
            }

            #layoutSidenav_content {
                margin-left: 0;
            }
            
            .sb-sidenav-toggled #layoutSidenav_nav {
                transform: translateX(0);
            }
        }

        .container-fluid {
            padding: 1.5rem;
        }

        .card {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    {{-- Navbar --}}
    @include('layouts._partials.navbar')

    <div id="layoutSidenav">
        {{-- Sidebar dengan logika yang lebih baik --}}
        @if(Auth::check())
            @php
                $userRole = strtolower(Auth::user()->Role);
            @endphp
            
            @if($userRole === 'admin' || $userRole === 'super_admin')
                @include('admin.sidebar')
            @elseif($userRole === 'dosen' || $userRole === 'mahasiswa')
                @include('user.sidebar')
            @endif
        @endif

        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            {{-- Footer --}}
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Teknik Elektro 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    @stack('scripts')
</body>
</html>