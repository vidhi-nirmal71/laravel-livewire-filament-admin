<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-compact layout-menu-fixed"  data-theme="theme-default" data-style="light" data-template="vertical-menu-template" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ config('app.name') }}</title>

    <meta name="description" content="" />
    {{-- <link rel="shortcut icon" href="{{ asset('img/workspace-fav.png') }}" type="image/png"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}?v=0.01" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}?v=0.1" />

    <link rel="stylesheet" href="{{ asset('css/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}">    

    <script src="{{ asset('js/helpers.js') }}?v=0.02"></script>
    <script src="{{ asset('js/config.js') }}?v=0.1"></script>

    @livewireStyles

    @yield('head')

</head>

<body>
    <div class="layout-wrapper layout-content-navbar user-workspace">
        <div class="layout-container">
            {{-- @include('layout.sidebar') --}}
            @include('layout.custom_alert_popup'){{-- Alert popup --}}
            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">
                        <!-- Left side: Page Title -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <a href="{{$href ?? ''}}" class="card-header cardHeader masterLinkTitle">{{$title ?? ''}}</a>
                            </div>
                        </div>
                    
                        <!-- Right side: Cart + User -->
                        <div class="d-flex align-items-center ms-auto">
                            <!-- Cart Icon -->
                            <div class="me-3 mt-4">
                                <livewire:cart-icon :key="'cart-icon'" />
                            </div>
                    
                            <!-- User Login/Profile -->
                            <ul class="navbar-nav flex-row align-items-center">
                                @auth
                                    <!-- User is logged in -->
                                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                            <div class="avatar avatar-online">
                                                <img src="{{ Auth::user()->profile_image_path ? asset('storage/profile-images/'.Auth::user()->profile_image_path) : url('/img/avatar.jpg') }}" 
                                                     alt="User Avatar" class="rounded-circle" width="40" height="40" id="navbarMainAvtarId" />
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('profile') }}">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-user me-2"></i>
                                                        <span>Profile</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-power-off me-2"></i>
                                                        <span>Logout</span>
                                                    </div>
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @else
                                    <!-- User is not logged in -->
                                    <li class="nav-item">
                                        <a href="{{ route('login') }}" class="nav-link">
                                            <i class="bx bx-log-in me-1"></i> Login
                                        </a>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                    
                </nav>
                <div class="content-wrapper ">
                    {{-- container-p-y --}}
                    <div class="container-xxl flex-grow-1 container-p-y mainBodyContent">
                        @yield('content')
                        
                        <!-- Toast with Placements -->
                        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
                            <div class="bs-toast toast m-2" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" id="AppToast">
                                <div class="toast-header">
                                    <div class="me-auto fw-semibold"></div>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body" id="toastContent">
                                </div>
                            </div>
                        </div>
                        <!-- Toast with Placements -->
                    </div>
                </div>
                <div id="page-loader" style="display: none">
                    <div class="loader"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        @livewireScripts
    </div>
    <script> var APP_URL = "{{ URL::to('/') }}";</script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/perfect-scrollbar.js') }}?v=0.01"></script>
    
    {{-- <script src="{{ asset('admin/js/button.js') }}?v=0.1"></script> --}}
    <script src="{{ asset('js/menu.js') }}?v=0.1"></script>
    <script src="{{ asset('js/main.js') }}?v=0.1"></script>
    <script src="{{ asset('js/app.js') }}?v=0.01"></script>


    <script src="{{ asset('js/jquery.validate.min.js') }}?v=0.1"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/jquery.form.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}?v=0.01"></script>
    <script>
        @if (session('message'))
            showToast('bg-success', "{{ session('message') }}");
        @endif

        @if (session('error'))
            showToast('bg-danger', "{{ session('error') }}");
        @endif

        $('#AppToast').on('hidden.bs.toast', function () {
            $('.toast').removeClass('bg-danger bg-success');
        });
    </script>

    <script src="{{ asset('js/confetti.browser.min.js') }}"></script>
    @yield('script')

    @stack('scripts')
</body>

</html>