<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{asset('assets')}}/"
    data-template="horizontal-menu-template-no-customizer">
<head>
    <meta charset="utf-8"/>
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Siponic</title>

    <meta name="description" content=""/>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"/>

    <!-- Icons -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/fonts/materialdesignicons.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/fonts/fontawesome.css')}}"/>
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/node-waves/node-waves.css')}}"/>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/css/rtl/core.css')}}" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/css/rtl/theme-default.css')}}" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="{{asset('/assets/css/demo.css')}}"/>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/typeahead-js/typeahead.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/apex-charts/apex-charts.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/swiper/swiper.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/toastr/toastr.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/sweetalert2/sweetalert2.css')}}"/>

    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/select2/select2.css')}}"/>

    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/flatpickr/flatpickr.css')}}"/>
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/css/pages/cards-statistics.css')}}"/>
    {{-- <link rel="stylesheet" href="{{asset('/assets/vendor/css/pages/cards-analytics.css')}}"/> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Helpers -->
    <script src="{{asset('/assets/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('/assets/js/config.js')}}"></script>
    <style>
        .is-invalid + .select2-container--bootstrap .select2-selection--single {
            border: 1px solid #f44336;
        }
        #cover-spin {
            position: fixed;
            width: 100%;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: none;
        }

        @-webkit-keyframes spin {
            from {
                -webkit-transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        #cover-spin::after {
            content: '';
            display: block;
            position: absolute;
            left: 48%;
            top: 40%;
            width: 40px;
            height: 40px;
            border-style: dotted;
            border-color: #20b2aa;
            border-top-color: transparent;
            border-width: 4px;
            border-radius: 50%;
            -webkit-animation: spin .8s linear infinite;
            animation: spin .8s linear infinite;
        }

        .navbar-logo {
            width: 200px;
            height: 63px;
            object-fit: contain;
        }

    </style>


</head>

<body>