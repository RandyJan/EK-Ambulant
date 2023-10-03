<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/images/logo/favicon.ico">

    <!-- plugins css -->
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" href="/bower_components/PACE/themes/blue/pace-theme-minimal.css" />
    <link rel="stylesheet" href="/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" />

    <link rel="stylesheet" href="/bower_components/selectize/dist/css/selectize.default.css" />

    <!-- core css -->
    <link href="/assets/css/ei-icon.css" rel="stylesheet">
    <link href="/assets/css/themify-icons.css" rel="stylesheet">
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/css/animate.min.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link href="/assets/css/styles.css" rel="stylesheet">
    <!-- page plugins css -->
    <link rel="stylesheet" href="/css/plugins/iziToast.min.css" />

    <style>
        .boxx {
            border: 1px solid gray;
        }

        #printPdf {
            position: fixed;
            top: 0px;
            left: 0px;
            display: block;
            padding: 0px;
            border: 0px;
            margin: 0px;
            visibility: hidden;
            opacity: 0;
        }

        @media(min-width:320px) {

            .sticky-side {
                display: none;
            }
        }

        @media(min-width:600px) {
            .sticky-side {
                min-width: 30%;
                display: block;
                padding-bottom: 50px;
                position: sticky;
                right: 0;
                top: 65px;
                margin-left: 15px;
            }

            .side-content {
                overflow: auto;
            }
        }


        .tr {
            display: table-row;
        }

        .row-group {
            display: table-row-group;
        }

        #cart-order .editable {
            color: purple;
        }

        .disable-select {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .word-break{
            word-break: break-word;
        }
    </style>

    @yield('css')
</head>

<body style="">

    {{-- CONTENT  --}}
    <div class="app">
        <div class="layout">
            @include('layouts.browser.sidenav')

            <!-- Page Container START -->
            <div class="page-container">

                @include('layouts.browser.topnav')
                <!-- Theme Toggle Button START -->
                {{-- <button class="theme-toggle btn btn-rounded btn-icon">
                    <i class="ti-palette"></i>
                </button> --}}
                <!-- Theme Toggle Button END -->

                <!-- Content Wrapper START -->
                {{-- <div class="main-content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div> --}}


                <div class="main-content">
                    <!-- <div class="content-container"> -->
                        <div class="container-fluid">
                            @yield('content')
                        </div>
                    <!-- </div> -->

                   {{--<div class="sticky-side border" style="">

                        <div class="side-content " style="overflow-y: auto; height: 90%">
                            <table class="table table-responsive-sm table-borderless disable-select" id="cart-order">
                                <thead>
                                    <tr class="font-size-13">
                                        <td>#</td>
                                        <td>Name</td>
                                        <td>Price</td>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="font-size-13 clickable" data-toggle="collapse" id="row2" data-target=".row2">
                                        <td>99</td>
                                        <td>HALF ROASTED CHICKEN MEAL</td>
                                        <td>375.00</td>

                                    </tr>
                                    <tr class="collapse row2 editable">
                                        <td>1</td>
                                        <td>COCA COLA</td>
                                        <td>20.00</td>

                                    </tr>
                                    <tr class="collapse  row2">
                                        <td>1</td>
                                        <td>Half Roasted Chicken</td>
                                        <td>0</td>
                                    </tr>
                                    <tr class="collapse  row2">
                                        <td>1</td>
                                        <td>Side DISH 1</td>
                                        <td>0</td>
                                    </tr>
                                    <!--  -->
                                    <tr class="font-size-13" data-toggle="collapse" id="row2" data-target=".row3">
                                        <td>2</td>
                                        <td>CHEESEDOG SANDWICH</td>
                                        <td>100.00</td>
                                    </tr>
                                    <tr class="collapse row3 editable">
                                        <td>1</td>
                                        <td>COCA COLA</td>
                                        <td>20.00</td>

                                    </tr>
                                    <tr class="collapse  row3 editable">
                                        <td>1</td>
                                        <td>Pineapple</td>
                                        <td>30.00</td>
                                    </tr>
                                    <tr class="collapse  row3 editable">
                                        <td>2</td>
                                        <td>CHEESEDOGS</td>
                                        <td>0</td>
                                    </tr>


                                </tbody>
                            </table>


                        </div>

                    </div>--}}


                </div>


                @include('layouts.browser.footer')

            </div>


        </div>

    </div>
    {{-- END OF CONTENT --}}

    <script src="/assets/js/vendor.js"></script>
    <script src="/assets/js/app.min.js"></script>
    <script src="/js/plugins/iziToast.min.js"></script>


    <script src="/bower_components/selectize/dist/js/standalone/selectize.min.js"></script>

    <script src="/assets/js/sweetalert2@9.7.1.min.js"></script>
    <script src="/js/plugins/vfs_fonts.js"></script>
    <script src="/js/config.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <!-- page js -->
    @yield('js')
</body>

</html>
