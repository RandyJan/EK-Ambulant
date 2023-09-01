@extends('layouts.master')
@section('title','Mealstub')

@section('content')
    @include('alerts.alert')

    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="row">
                     <!-- <div class="row justify-content-md-center"> -->
                    <div class="col-md-12">
                        <video id="video" width="100%" height="300" class="boxx"></video>
                        <button id="btn-start" class="btn btn-info btn-block">
                            Click to Scan
                        </button>
                        <input type="hidden" id="scanned-code">
                    </div>
                </div>
                <div class="row justify-content-md-12">
                    <!-- <div class="row justify-content-md-center"> -->
                    <div class="col-md-12">
                        <div id="sourceSelectPanel" style="display:none">
                            <div class="form-group">
                                <label for="sourceSelect">Change source:</label>
                                <select id="sourceSelect" class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <!-- <div class="input-group-prepend" style="flex-flow:column">
                                <span class="input-group-text"><input type="checkbox" id="is-manual" aria-label="Checkbox for following text input"></span>
                            </div> -->
                            <input type="text" class="form-control" id="stub" name="stub" placeholder="Mealstub #" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="btn-manual-check">Check</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <!-- <div class="col-md-6">
                <div class="">
                        <input type="text" id="stub" name="stub" class="form-control boxx" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg"></br>
                        <button id="btn-manual-check" class="btn btn-info btn-block">
                                Submit
                        </button>
                </div>
            </div> -->

        </div>

    </div>


@endsection

@section('css')
    <style>
        .boxx{
            border: 1px solid gray;
        }

    </style>
@endsection

@section('js')
    <script src="/js/plugins/zxing.js"></script>
    <script>
        $(document).ready( function(){

            if (hasGetUserMedia()) {
                // Good to go!
                loadCam();
            } else {
                alert('getUserMedia() is not supported by your browser');
            }

            var scannedCode = $('#scanned-code');
            setInterval(() => {
                // console.log('checking...');
                if( scannedCode.val() != ''){
                    codeChecker( scannedCode.val() );
                    scannedCode.val('');
                }
            }, 500);

        });

        function hasGetUserMedia() {
            return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
        }

        function loadCam(){
            let selectedDeviceId;
            const codeReader = new ZXing.BrowserMultiFormatReader();
            console.log('ZXing code reader initialized');

            codeReader.getVideoInputDevices().then((videoInputDevices) => {

                const sourceSelect = document.getElementById('sourceSelect');
                selectedDeviceId = videoInputDevices[0].deviceId;

                if (videoInputDevices.length >= 1) {

                    videoInputDevices.forEach((element) => {
                        const sourceOption  = document.createElement('option');
                        sourceOption.text   = element.label;
                        sourceOption.value  = element.deviceId;
                        sourceSelect.appendChild(sourceOption);
                    });

                    sourceSelect.onchange = () => {
                        selectedDeviceId = sourceSelect.value;
                    };

                    const sourceSelectPanel = document.getElementById('sourceSelectPanel');
                    sourceSelectPanel.style.display = 'block';
                }

                document.getElementById('btn-start').addEventListener('click', () => {
                    codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
                        if (result) {
                            console.log('result: '+result);
                            $('#scanned-code').val(result);
                            // reset
                            codeReader.reset();
                        }
                        if (err && !(err instanceof ZXing.NotFoundException)) {
                            console.error('err: '+err);
                            // document.getElementById('result').textContent = err
                        }
                    });
                    // console.log(`Started continous decode from camera with id ${selectedDeviceId}`);
                });

                // document.getElementById('resetButton').addEventListener('click', () => {
                //     codeReader.reset();
                //     // document.getElementById('result').textContent = '';
                //     console.log('Reset.');
                // });

            }).catch((err) => {
                console.error(err);
            });
        }
        /**


        function codeChecker(code){
            Swal.showLoading();
            post('/mealstub/checker', {code:code}, function(res){
                Swal.hideLoading();

                if(res.success == false){
                    Swal.fire(res.message);
                    return;
                }

                Swal.fire({
                    title: 'MealStub is Valid!',
                    text: "Do you want to claim it?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Please!',
                }).then((result) => {
                    console.log(result);
                    if(result.value) {

                        console.log(res.data.reference_id);

                        // var url = '{{ route("mealstub-product", ":ref_id") }}';
                        // url = url.replace(':ref_id', res.data.reference_id);
                        // redirectTo(url);

                        // post('/mealstub/verify', {
                        //     reference: res.data.reference_id,
                        //     serial_number: res.data.serial_number
                        // },function(ress){

                        //     if(ress.success == false){
                        //         Swal.fire(ress.message);
                        //         return;
                        //     }
                        //     console.log(ress);
                        //     // showSuccess('',ress.message, function(){
                        //         setStorage('loc', JSON.stringify({
                        //             // branch_id: ress.data.branch_id,
                        //             outlet: ress.data.outlet_id,
                        //             srn: res.data.serial_number,
                        //             reference_id : res.data.reference_id
                        //             // components: ress.data.components
                        //             })) ;
                        //         redirectTo('/mealstub/'+res.data.reference_id);
                        //         // redirectTo(`/mealstub/${res.data.serial_number}/modify`);
                        //     // });

                        // });

                        Swal.fire({
                            title: 'Take out or Dine in',
                            // text: "Do you want to claim it?",
                            // icon: 'info',
                            showCancelButton: true,
                            cancelButtonText: 'Dine in',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Take out',
                        }).then((result) => {
                            if(result.value){
                                isTakeOut = 2;
                            }else{
                                isTakeOut = 1;
                            }
                            post('/mealstub/claim2', {
                                    reference: res.data.reference_id,
                                    serial_number: res.data.serial_number,
                                    is_take_out: isTakeOut
                                },function(ress){

                                    if(ress.success == false){
                                        Swal.fire(ress.message);
                                        return;
                                    }

                                    showSuccess('',ress.message, function(){
                                        location.reload();
                                    });

                                });

                        });





                    }
                });
            });
        }
         */

        function codeChecker(code){
            // Swal.showLoading();
            post('/mealstub/checker', {code:code}, function(res){
                // Swal.hideLoading();
                console.log(res);

                if(res.success == false){
                    Swal.fire(res.message);
                    return;
                }

                // show modal
                let modal = $('#mealstub_verification');
                modal.modal({ backdrop: 'static'});
                $("#mealstub_verification .title").text('Claim Stub is Valid');
                $("#mealstub_verification .message").text('Do you want to claim it?');
                modal.modal('show');

                $('#to').on('click' , ()=>{
                    let isTakeOut = 2;
                   modal.modal('toggle');
                    claim(isTakeOut, res);

                });

                $('#di').on('click' , ()=>{
                    let isTakeOut = 2;
                   modal.modal('toggle');
                    // modal.hide();
                    claim(isTakeOut, res);

                });


            });
        }

        function claim(isTakeOut, res){
            post('/mealstub/claim2', {
                    reference: res.data.reference_id,
                    serial_number: res.data.serial_number,
                    is_take_out: isTakeOut
                },function(ress){

                    if(ress.success == false){
                        Swal.fire(ress.message);
                        return;
                    }

                    showSuccess('',ress.message, function(){
                        location.reload();
                    });

                });
        }


        $('#btn-manual-check').on('click', function(){
            // alert('tsk');
            var input = $('#stub');
            if( input.val().trim() == ''){
                alert('Stub number is required');
                return;
            }

            codeChecker( input.val() );
        });

    </script>
@endsection
