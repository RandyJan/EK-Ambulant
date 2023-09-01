<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Device ID | {{ config('app.name') }}</title>

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
                                                    <div class="pdd-horizon-30 pdd-vertical-30">
                                                        <div class="mrg-btm-30 text-center">
                                                            <div class="img-responsive inline-block"></div>
                                                            <img class="img-responsive inline-block" src="/assets/images/logo/logo.png" alt="">

                                                        </div>

                                                        @include('alerts.alert')
                                                        <p class="mrg-btm-15 font-size-13">Please enter Device ID for this Device</p>
                                                        <form action="{{ route('device-id-form.store') }}" method="POST" class="ng-pristine ng-valid">
                                                            @csrf
                                                            <div class="form-group">
                                                                <input name="device_id" type="text" class="form-control text-center input-lg" placeholder="Enter Device ID">
                                                            </div>
                                                            @if(config('ambulant.enable_kiosk_function') == 1)
                                                            {{-- {{config('ambulant.enable_kiosk_function')}} --}}
                                                            <div class="form-group">
                                                                <label for="device_type">Device type</label>
                                                                <select class="form-control" name="device_type" id="device_type">
                                                                  <option value="order_taker">Order Taking</option>
                                                                  <option value="kiosk">Kiosk</option>
                                                                </select>
                                                            </div>
                                                            @endif
                                                            <div class="form-group">
                                                                <label for="barcode_type">Barcode type</label>
                                                                <select class="form-control" name="barcode_type" id="barcode">
                                                                  <option value="QRCODE">QRCODE</option>
                                                                  <option value="CODE39">BARCODE</option>
                                                                  {{-- <option value="CODE39">CODE39</option>
                                                                  <option value="CODE128">CODE128</option>
                                                                  <option value="CODE128A">CODE128A</option>
                                                                  <option value="CODE128B">CODE128B</option>
                                                                  <option value="CODE128C">CODE128C</option>
                                                                  <option value="EAN13">EAN13</option>
                                                                  <option value="UPC">UPC</option> --}}
                                                                </select>
                                                              </div>

                                                            <!-- <div class="form-group">
                                                                <input name="security_key" type="text" class="form-control" placeholder="Security Key">
                                                            </div> -->
                                                            <div class="">
                                                                <button type="submit" id="btn-submit" class="btn btn-inverse btn-primary w-100">SET</button>
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
            //clearStorage();
        });
    </script>
</body>
</html>
