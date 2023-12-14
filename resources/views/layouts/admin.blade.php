<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title', config('app.name'))</title>
    @stack('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/header-footer.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/apexcharts/apexcharts.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/apexcharts/apexcharts.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-js/dashboard-function.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="main-site ccj-panel">
    <?php
    $currentURL = Route::currentRouteName();
    ?>
    <div class="page-body-wrapper">
        <div class="sidebar-wrapper sidebar-offcanvas" id="sidebar">
            <div class="sidebar-logo">
                <a class="brand-logo" href="{{ url('home') }}">
                    <img class="" src="{{ assets('assets/admin-images/logo.svg') }}" alt="">
                </a>
            </div>
            <div class="sidebar-nav">
                <nav class="sidebar">
                    <ul class="nav">
                        <li class="nav-item @if ($currentURL == 'home') active @endif">
                            <a class="nav-link" href="{{ url('home') }}">
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M10.5 19.9V4.1C10.5 2.6 9.86 2 8.27 2H4.23C2.64 2 2 2.6 2 4.1V19.9C2 21.4 2.64 22 4.23 22H8.27C9.86 22 10.5 21.4 10.5 19.9Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M22 10.9V4.1C22 2.6 21.36 2 19.77 2H15.73C14.14 2 13.5 2.6 13.5 4.1V10.9C13.5 12.4 14.14 13 15.73 13H19.77C21.36 13 22 12.4 22 10.9Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M22 19.9V18.1C22 16.6 21.36 16 19.77 16H15.73C14.14 16 13.5 16.6 13.5 18.1V19.9C13.5 21.4 14.14 22 15.73 22H19.77C21.36 22 22 21.4 22 19.9Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item @if ($currentURL == 'Users') active @endif">
                            <a class="nav-link" href="{{ url('users') }}">
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M9.16 10.87C9.06 10.86 8.94 10.86 8.83 10.87C6.45 10.79 4.56 8.84 4.56 6.44C4.56 3.99 6.54 2 9 2C11.45 2 13.44 3.99 13.44 6.44C13.43 8.84 11.54 10.79 9.16 10.87Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M16.41 4C18.35 4 19.91 5.57 19.91 7.5C19.91 9.39 18.41 10.93 16.54 11C16.46 10.99 16.37 10.99 16.28 11"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M4.16 14.56C1.74 16.18 1.74 18.82 4.16 20.43C6.91 22.27 11.42 22.27 14.17 20.43C16.59 18.81 16.59 16.17 14.17 14.56C11.43 12.73 6.92 12.73 4.16 14.56Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M18.34 20C19.06 19.85 19.74 19.56 20.3 19.13C21.86 17.96 21.86 16.03 20.3 14.86C19.75 14.44 19.08 14.16 18.37 14"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="menu-title">User Management</span>
                            </a>
                        </li>

                        <li class="nav-item @if ($currentURL == 'ManageBooking' || $currentURL == 'InquiryRequest' || $currentURL == 'ViewTransactionHistory') active @endif">
                            <a class="nav-link" href="{{ url('manage-booking') }}">
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path d="M8 2V5" stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16 2V5" stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M3.5 9.09H20.5" stroke="white" stroke-width="1.5"
                                            stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z"
                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M15.6947 13.7H15.7037" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M15.6947 16.7H15.7037" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.9955 13.7H12.0045" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.9955 16.7H12.0045" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8.29431 13.7H8.30329" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8.29431 16.7H8.30329" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="menu-title">Manage Booking</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="chat.html">
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M5.46 18.49V15.57C5.46 14.6 6.22 13.73 7.3 13.73C8.27 13.73 9.14 14.49 9.14 15.57V18.38C9.14 20.33 7.52 21.95 5.57 21.95C3.62 21.95 2 20.32 2 18.38V12.22C1.89 6.6 6.33 2.05 11.95 2.05C17.57 2.05 22 6.6 22 12.11V18.27C22 20.22 20.38 21.84 18.43 21.84C16.48 21.84 14.86 20.22 14.86 18.27V15.46C14.86 14.49 15.62 13.62 16.7 13.62C17.67 13.62 18.54 14.38 18.54 15.46V18.49"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="menu-title">Manage Virtual Tour</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="service-scheduler.html">
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M9 10C10.1046 10 11 9.10457 11 8C11 6.89543 10.1046 6 9 6C7.89543 6 7 6.89543 7 8C7 9.10457 7.89543 10 9 10Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M2.67 18.95L7.6 15.64C8.39 15.11 9.53 15.17 10.24 15.78L10.57 16.07C11.35 16.74 12.61 16.74 13.39 16.07L17.55 12.5C18.33 11.83 19.59 11.83 20.37 12.5L22 13.9"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="menu-title">Manage Photo Booth</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="manage-jobs.html">
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M15.51 2.83H8.49C6 2.83 5.45 4.07 5.13 5.59L4 11H20L18.87 5.59C18.55 4.07 18 2.83 15.51 2.83Z"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M21.99 19.82C22.1 20.99 21.16 22 19.96 22H18.08C17 22 16.85 21.54 16.66 20.97L16.46 20.37C16.18 19.55 16 19 14.56 19H9.44C8 19 7.79 19.62 7.54 20.37L7.34 20.97C7.15 21.54 7 22 5.92 22H4.04C2.84 22 1.9 20.99 2.01 19.82L2.57 13.73C2.71 12.23 3 11 5.62 11H18.38C21 11 21.29 12.23 21.43 13.73L21.99 19.82Z"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M4 8H3" stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M21 8H20" stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M12 3V5" stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M10.5 5H13.5" stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M6 15H9" stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M15 15H18" stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="menu-title">Taxi Booking Requests</span>
                            </a>
                        </li>

                        <li class="nav-item @if ($currentURL == 'Tours' || $currentURL == 'AddTour') active @endif">
                            <a class="nav-link" href="{{ url('tours') }}">
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path d="M8 2V5" stroke="white" stroke-width="2" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16 2V5" stroke="white" stroke-width="2" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M3.5 9.09H20.5" stroke="white" stroke-width="2"
                                            stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M13.37 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5V13"
                                            stroke="white" stroke-width="2" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.9955 13.7H12.0045" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8.29431 13.7H8.30329" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8.29431 16.7H8.30329" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M14.3908 20.5775C13.8058 20.0925 13.4058 19.4075 13.2258 18.6075L13.0208 17.6775C12.9208 17.2225 13.1908 16.7075 13.6258 16.5325L14.3358 16.2475L17.0908 15.1425C17.5708 14.9525 18.1008 14.9525 18.5808 15.1425L21.3358 16.2475L22.0458 16.5325C22.4808 16.7075 22.7508 17.2225 22.6508 17.6775L22.4458 18.6075C22.0908 20.2075 20.8358 21.3475 19.0258 21.3475H16.6458"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M17.8358 21.3477V15.3477" stroke="white" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="menu-title">Manage Tour</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                                <span class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path d="M9.32001 6.5L11.88 3.94L14.44 6.5" stroke="white" stroke-width="1.5"
                                            stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.88 14.18V4.01" stroke="white" stroke-width="1.5"
                                            stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4 12C4 16.42 7 20 12 20C17 20 20 16.42 20 12" stroke="white"
                                            stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="menu-title">Logout</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="body-wrapper">
            <div class="header">
                <nav class="navbar">
                    <div class="navbar-menu-wrapper">
                        <ul class="navbar-nav f-navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link nav-toggler" data-toggle="minimize">
                                    <img src="{{ assets('assets/admin-images/menu-icon.svg') }}">
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav">
                            <li class="nav-item noti-dropdown dropdown">
                                <a class="nav-link  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="noti-icon">
                                        <img src="{{ assets('assets/admin-images/notification.svg') }}"
                                            alt="user">
                                        <span class="noti-badge">3</span>
                                    </div>
                                </a>
                                <div class="dropdown-menu">

                                </div>
                            </li>
                            <li class="nav-item profile-dropdown dropdown">
                                <a class="nav-link dropdown-toggle" id="profile" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <div class="profile-pic">
                                        <div class="profile-pic-image">
                                            <img src="{{ assets('assets/admin-images/admin-icon.png') }}"
                                                alt="user">
                                        </div>
                                        <div class="profile-pic-text">
                                            <h3>{{ Auth::user()->fullname ?? '' }}</h3>
                                            <p>Administrator</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">
                                        <i class="las la-user"></i> Profile
                                    </a>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="dropdown-item">
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                        <i class="las la-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item profile-dropdown dropdown">
                                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                                    type="button" data-toggle="offcanvas">
                                    <span class="icon-menu"><img
                                            src="{{ assets('assets/admin-images/menu-icon.svg') }}"></span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            @yield('content')
        </div>
    </div>
    <script>
        var base_url = "{{ url('/') }}";
        $(document).ready(function() {
            if ("{{ Session::has('success') }}") {
                toastr.success(" {{ Session::get('success') }} ");
            }
            if ("{{ Session::has('error') }}") {
                toastr.error(" {{ Session::get('error') }} ");
            }
            if ("{{ Session::has('warn') }}") {
                toastr.warning(" {{ Session::get('warn') }} ");
            }
        });
        $(document).ready(function() {
            $("#preloader").hide();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @stack('js')
</body>

</html>
