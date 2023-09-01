<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favico.ico">

    <!-- plugins css -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" href="bower_components/PACE/themes/blue/pace-theme-minimal.css" />
    <link rel="stylesheet" href="bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" />

    <!-- core css -->
    <link href="assets/css/ei-icon.css" rel="stylesheet">
    <link href="assets/css/themify-icons.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">

    <!-- page plugins css -->
    <link rel="stylesheet" href="/css/plugins/iziToast.min.css" />
</head>
<body>
    <input type="text" hidden id="__api" value="{{ config('setup.api') }}">
    <input type="text" hidden id="__api_printer" value="{{ config('setup.api_printer') }}">
    {{-- CONTENT  --}}
    <div class="app">
        <div class="authentication">
            <div class="sign-in-2">
                    {{-- style="background-image: url('assets/images/others/img-30.jpg')" --}}
                <div class="container-fluid no-pdd-horizon bg" >
                    <div class="row">
                        <div class="col-md-10 mr-auto ml-auto">
                            <div class="row">
                                <div class="mr-auto ml-auto full-height height-100">
                                    <div class="vertical-align full-height">
                                        <div class="table-cell">
                                            <div class="card">
                                                <div class="card-body">
                                               
                                                <div class="d-flex justify-content-around">
                                                    <div class="text-center"> 
                                                        <span>BRANCH ID</span>
                                                        <span class="label label-info mrg-left-5 d-block">{{ $device->branch->branch_id }}</span>
                                                    </div>
                                                    <div class="text-center"> 
                                                        <span>OUTLET</span> 
                                                        <span class="label label-info mrg-left-5 d-block">
                                                        {{ $device->
                                                        getOutletByBranchId($device->branch_id)->description }}</span>
                                                    </div>
                                                    <div class="text-center"> 
                                                        <span>TERMINAL ID</span>
                                                        <span class="label label-info mrg-left-5 d-block">{{$device->pos_id}}</span>
                                                    </div>
                                             
                                                </div>
                                                    <div class="pdd-horizon-30 pdd-vertical-30">
                                                        <div class="mrg-btm-30 text-center">
                                                            <div class="img-responsive inline-block"></div>
                                                            <img class="img-responsive inline-block" src="/assets/images/logo/logo.png" alt="">
                                                          
                                                        </div>

                                                        @include('alerts.alert')

                                                        <p class="mrg-btm-15 font-size-13">Please enter your user name and password to login</p>
                                                        <form action="login" method="POST" class="ng-pristine ng-valid">
                                                            @csrf
                                                            <div class="form-group">
                                                                <input name="username" id="username" type="text" class="form-control" placeholder="User name">
                                                            </div>
                                                            <div class="form-group">
                                                                <input name="password" id="password" type="password" class="form-control" placeholder="Password">
                                                            </div>
                                                            <div class="checkbox font-size-13 inline-block no-mrg-vertical no-pdd-vertical">
                                                                {{-- <input id="agreement" name="agreement" type="checkbox">
                                                                <label for="agreement">Keep Me Signed In</label> --}}
                                                            </div>
                                                            
                                                            <div>
                                                                Device ID :
                                                                <h2 class="text-center text-bold">
                                                                    {{ request()->cookie('device_id') }}
                                                                </h2>
                                                            </div>
                                                            
                                                            <!-- <div class="pull-right">
                                                                <a href="#">Forgot Password?</a>
                                                            </div>   -->
                                                            
                                                            <div class="mrg-top-20">
                                                                {{--<a href="{{ route('device-id.reset') }}" class="btn btn-info text-left text-white">Reset Device</a>--}}
                                                                <button type="submit" id="btn-login" class="btn btn-info pull-right">Login</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END OF CONTENT --}}

    <script src="assets/js/vendor.js"></script> 
    <script src="assets/js/app.min.js"></script> 
    <script src="/js/plugins/iziToast.min.js"></script> 
    <script src="js/config.js"></script>
    <!-- page js -->
    <script>
        $(document).ready(function(){
            clearStorage();
        });
    </script>
</body>
</html>