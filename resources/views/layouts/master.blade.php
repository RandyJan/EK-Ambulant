<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="is_superadmin" content="{{Auth::user()->is_superadmin }}">
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
        @media(min-width:320px) {

            .sticky-side {
                display: none;
            }

        }
        /* .modal-full {
            min-width: 20%;
            margin-left: 80;
        }

        .modal-full .modal-content {
            min-height: 100vh;
        } */

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

        .not-allowed{
           cursor: not-allowed;
        }

        .cart-item:hover{
            cursor:pointer;
        }


    </style>

    @yield('css')
</head>

<body style="">

    {{-- CONTENT  --}}
    <div class="app">
        <div class="layout">
            @include('layouts.sidenav')

            <!-- Page Container START -->
            <div class="page-container">

                @include('layouts.topnav')

                @include('layouts.sidepanel')

                @include('layouts.theme-config')

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


                <div class="main-content d-flex justify-content-between">
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


                @include('layouts.footer')

            </div>


        </div>

    </div>

    @include('pages.mealstub.mealstub_modal')

 <!-- Print Modal  -->
 <div class="modal fade h-100" id="print-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full h-100"  role="document">
        <div class="modal-content h-100">
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button> -->
            <div class="modal-body" id="modal-body">

                <iframe id="os-iframe"src="/orderslip/print-preview" class="h-75" target="_parent" width="100%" height="80%" frameborder="0" allowTransparency="true" ></iframe>
                <button id="btn-Print" class="btn btn-lg btn-block btn-primary mt-6" onclick="printDiv('print-content')">PRINT</button>
            </div>
        </div>
    </div>
</div>


    {{-- END OF CONTENT --}}


    <script src="/assets/js/vendor.js"></script>
    <script src="/assets/js/app.min.js"></script>
    <script src="/js/plugins/iziToast.min.js"></script>


    <script src="/bower_components/selectize/dist/js/standalone/selectize.min.js"></script>
    <script src="/bower_components/jquery-validation/dist/jquery.validate.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.7.1/dist/sweetalert2.all.min.js"></script> --}}

    {{-- <script src="/js/plugins/pdfmake.min.js"></script> --}}
    <script src="/assets/js/sweetalert2@9.7.1.min.js"></script>
    <script src="/js/plugins/vfs_fonts.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/pdfmake.min.js"></script> --}}
    <script src="/js/config.js"></script>
    <script src="https://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>

    <script src="/assets/js/vendor.js"></script>
     <script src="/js/config.js"></script>
     <script src="/assets/js/JsBarcode.all.min.js"></script>
     <script src="/assets/js/qrcode.min.js"></script>

     <script>

        $('#btn-Print').on('click',function(){
                $("#os-iframe").get(0).contentWindow.print();
                window.location.href = '/';
        });
        $('#print-modal').modal('handleUpdate');

    </script>
    <!-- page js -->
    <script>
        $(document).ready(function() {
            $('#headcount').on('click', function() {
                var self = $(this);

                var data = {
                    branch_id: self.data('branch-id'),
                    os_id: self.data('os-id'),
                    outlet_id: self.data('outlet-id'),
                    device_id: self.data('device-id')
                };

                post('/orderslip-info', data, function(res){
                    if(res.header.is_paid == 1){
                        return;
                    }
                    if( res.header.total_hc == res.header.total_hc){
                        Swal.fire({
                            title: 'Enter HeadCount',
                            input: 'number',
                            inputValue: res.header.total_hc,
                            inputAttributes: {
                                autocapitalize: 'off',
                                min: 1
                            },
                            inputValidator: (input)=>{
                                if(input<=0){
                                    return 'Quantity is invalid'
                                }
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Save',
                            showLoaderOnConfirm: true,

                            preConfirm: (input) => {
                                return input;
                            },

                            allowOutsideClick: () => !Swal.isLoading()

                        }).then((result) => {
                            if (result.value) {
                                // console.log(result);
                                data._method = 'PATCH';
                                data.head_count = result.value;

                                post('/orderslip/headcount', data, function(ress){
                                    // console.log(ress);
                                    // showSuccess('',ress.message);
                                });
                                const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                onOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                                })

                                Toast.fire({
                                icon: 'success',
                                title: 'Headcount has been saved'
                                })
                            }
                            location.reload();
                        });
                        return;

                    }
                });


            });

            $('#txt-searchbox').on('keypress', function(e) {
                if (e.which == 13) {
                    redirectTo('/products?name=' + $(this).val());

                }
            });

            $('#breakdown').on('click.show.bs.collapse', function(){
                // alert();
                var self = $(this);

                var data = {
                    branch_id: self.data('branch-id'),
                    os_id: self.data('os-id'),
                    outlet_id: self.data('outlet-id'),
                    device_id: self.data('device-id')
                };

                post('/orderslip-info', data, function(res){
                    // console.log(res);
                    var cart = $('#collapseCart');

                    cart.find('#container_cart').empty();

                    $.each(res.details, function(k,v){
                        cart.find('#container_cart').append(`
                            <p>${ v.qty } </p>
                        `);


                    });    // cart..collapse('show');
                });
            });

            /*  Getting Customer Info */
            /*  When user click customer-info button */
            $('#customer-info').on('click',function(){
                $('#modal-customer-info').modal('show');
                $('#modal-customer-info #save_info').prop('disabled', true);


                $('#modal-customer-info input[name=phone_number]').keyup(function() {

                        $('#modal-customer-info #save_info').prop('disabled', false);
                });
                 /*Save Customer Info when user click save button */
                 $('#modal-customer-info #save_info').on('click', () => {


                    let data ={
                    phone_number:$('#modal-customer-info input[name=phone_number]').val(),
                    customer_name: $('#modal-customer-info input[name=customer_name]').val(),
                    bdate : $('#modal-customer-info input[name=bdate]').val()
                    }
                    // console.log(data);



                    /**Save to Database */
                    post('/customer-info',data, function(response) {

                        // console.log(response);
                        if (response.success == false) {
                            showError('', response.message, function() {});
                            return;
                        }
                        Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Successfully added info',
                        showConfirmButton: false,
                        timer: 1000
                        });


                    });
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            resetActiveOrder();

            showGuestModalInfo();
            // saveGuestInfo();
            // changeType();
            print();
            btnInstruction();

            dblClick();
        });

        function resetActiveOrder(){
            // /orderslip/resetActiveOrder
            let data = "{{ isset ( Auth::user()->activeOrder()['OSDATE'] ) ?  Auth::user()->activeOrder()['OSDATE'] : null }}";
            // console.log('resetActiveOrder',data);
            post('/orderslip/resetActiveOrder', {}, function(res){
                if(res.success == false){
                    showError('', res.message, function() {});
                    return;
                }

                if(res.data > 0){
                    location.reload();
                }

            });
        }

        function showGuestModalInfo() {
            $('#guest-modal').on('show.bs.modal', function(e) {
                let guest_number = $(e.relatedTarget).data('guestNumber'),
                    guest_type = $(e.relatedTarget).data('guestType'),
                    table_no = $(e.relatedTarget).data('tableNo'),
                    os_no = $(e.relatedTarget).data('osNo');

                // populate modal title
                $('#guest-number').text('Guest #' + guest_number + ' ').data("guestNo", guest_number);
                $('#os-no').text(os_no).data("osNo", os_no);
                $('#table-no').text(table_no).data("tableNo", table_no);
                // console.log(e.relatedTarget, $("#guest-number").data(),  $('#os-no').data(),  $('#table-no').data() );


                // set selected checkbox based on the guest type
                let type = guest_type.toLowerCase().trim();
                if (type == 'regular') {
                    $(this).find('input[name="guest-type"]#type-reg').prop("checked", true);
                } else if (type == 'senior') {
                    $(this).find('input[name="guest-type"]#type-sc').prop("checked", true);
                } else if (type == 'pwd') {
                    $(this).find('input[name="guest-type"]#type-pwd').prop("checked", true);
                } else if (type == 'zero rated') {
                    $(this).find('input[name="guest-type"]#type-zr').prop("checked", true);
                } else {
                    return;
                }

                // hide creds when type is regular
                if (type != 'regular') {
                    $(this).find('#creds').removeClass("d-none");
                } else {
                    $(this).find('#creds').addClass('d-none');
                }

                // get the info about the guests from the button

                let guest_name, guest_id, guest_address, guest_tin;
                guest_name = $(e.relatedTarget).data('guestName').trim();
                guest_id = $(e.relatedTarget).data('guestId');
                guest_address = $(e.relatedTarget).data('guestAddress').trim();
                guest_tin = $(e.relatedTarget).data('guestTin').trim();


                if (guest_name == null) {
                    guest_name = "";
                }
                if (guest_id == null) {
                    guest_id = "";
                }
                if (guest_address == null) {
                    guest_address = "";
                }
                if (guest_tin == null) {
                    guest_address = "";
                }


                // console.log(guest_name, guest_id, guest_address, guest_tin);
                // populate the creds fields
                $('#guest-modal input#guest-name').val(guest_name);
                $('#guest-modal input#guest-discid').val(guest_id);
                $('#guest-modal input#guest-address').val(guest_address);
                $('#guest-modal input#guest-tin').val(guest_tin);


            })
        }

        function saveGuestInfo() {
            $('#guest-modal button#save').on('click', () => {
                // get the checked type

                let guest_type = $('#guest-modal input[name="guest-type"]:checked').val();
                let guest_number = $('#guest-modal #guest-number').data("guestNo");
                let guest_name = "",
                    guest_id = "",
                    guest_address = "",
                    guest_tin = "";

                // if the type is non-regular get the creds
                if (guest_type != 'Regular') {
                    guest_name = $('#guest-modal input#guest-name').val().trim();
                    guest_id = $('#guest-modal input#guest-discid').val().trim();
                    guest_address = $('#guest-modal input#guest-address').val().trim();
                    guest_tin = $('#guest-modal input#guest-tin').val().trim();
                }

                let table_no = $('#guest-modal #table-no').data("tableNo");
                let os_no = $('#guest-modal #os-no').data("osNo");

                // console.log('saveGuestInfo: ', guest_name, guest_id, guest_address, guest_tin);

                let data = {
                    os_no: os_no,
                    table_no: table_no,

                    guest_no: guest_number,
                    guest_type: guest_type,
                    guest_name: guest_name,
                    guest_id: guest_id,
                    guest_address: guest_address,
                    guest_tin: guest_tin,

                };


                post('/updateGuest', data, function(response) {

                    if (response.success == false) {
                        showError('', response.message, function() {});
                        return;
                    }

                    $('#guest-modal').modal('hide');

                });

            });
        }

        function changeType() {
            $('#guest-modal input[name="guest-type"]').change(function() {
                if ($(this).attr('id') != 'type-reg') {
                    $('#guest-modal').find('#creds').removeClass('d-none');
                } else {
                    $('#guest-modal').find('#creds').addClass('d-none');
                }

            });
        }


        // ======================
        // print request
        //=======================

        function print() {
            $('#print-bill').on('click', function() {

                if($('.counter').text() == 0){
                    // console.log("No items in cart")
                    return;
                }
                // console.log('hey');

                var docDefinition = {
                    content: [
                        /**
                         * Transaction Header
                         */
                        {
                            text: [
                                'ENCHANTED KINGDOM\n' +
                                'San Lorenzo South, Sta. Rosa Laguna\n' +
                                'VAT Registered TIN: 004-149-597-0000\n',
                                'Tel No. 584-3535 (Sta Rosa Park)\n',
                                'Tel no. 830-3535 (Makati Sales Office)\n',
                                'MIN: XXXXXXXX \t',
                                'SN: XXXXXXXX\n\n',
                                'FOR EVALUATION PURPOSES ONLY\n\n',
                                '========================================\n',
                            ],
                            fontSize: '9',
                            alignment: 'center'
                        },
                    ],

                    //pageSize: 'A5',
                    pageSize: {
                        width: 220,
                        height: 'auto'
                    },
                    // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
                    pageMargins: [5, 25, 5, 5],
                };

                // pdfMake.createPdf(docDefinition).print();
                // const pdfDocGenerator = pdfMake.createPdf(docDefinition);
                // pdfDocGenerator.getDataUrl((dataUrl) => {
                //     const targetElement = document.querySelector('#iframeContainer');
                //     const iframe = document.createElement('iframe');
                //     iframe.src = dataUrl;
                //     targetElement.appendChild(iframe);
                // });
                // pdfMake.createPdf(docDefinition).print({}, window.frames['printPdf']);


                // post('/orderslip/print', {}, function(response) {
                //     console.log(response.message);
                //     if( response.success == false){
                //         return;
                //     }
                //     showSuccess('',response.message, function(){

                //     });
                // });

                var self = $(this);

                var data = {
                    branch_id: self.data('branch-id'),
                    os_id: self.data('os-id'),
                    outlet_id: self.data('outlet-id'),
                    device_id: self.data('device-id')
                };

                post('/orderslip-info', data, function(res){
                    // console.log(data, res);
                    if( res.header.total_hc == null || res.header.total_hc <= 0){
                        Swal.fire({
                            title: 'Enter HeadCount',
                            input: 'number',
                            inputValue: 0,
                            inputAttributes: {
                                autocapitalize: 'off',
                                min: 1
                            },
                            inputValidator: (input)=>{
                                if(input<=0){
                                    return 'Quantity is invalid'
                                }
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Proceed to Printing',
                            showLoaderOnConfirm: true,

                            preConfirm: (input) => {
                                return new Promise((resolve) => {

                                resolve (input);

                            });
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.value) {
                                console.log(result);
                                data._method = 'PATCH';
                                data.head_count = result.value;

                                post('/orderslip/headcount', data, function(ress){
                                    console.log('ge',ress);

                                    // showSuccess('',ress.message);
                                    if(ress.success == false){
                                        showError('', ress.message, function () {});
                                        return;
                                    }


                                    data._method = 'PATCH';
                                    post('/orderslip/set-duration', data, function(ress){
                                        console.log(ress);


                                        $( '#os-iframe' ).attr('src', function (i, val) {
                                                return val;
                                            }).on('load', function() {
                                                $(this).get(0).contentWindow.print();
                                            });

                                        //    test();
                //                         $("#os-iframe").get(0).contentWindow.print();
                // window.location.href = '/';


                                        // showSuccess('',ress.message);
                                    });



                                });



                            }

                        });
                     return;

                    }
                    printiftheresvalue();
                    function printiftheresvalue() {
                        data._method = 'PATCH';
                    post('/orderslip/set-duration', data, function(ress){
                        // console.log(ress);
                        // redirect('/orderslip/print-preview');
                        console.log('test3');
                        $("#os-iframe").get(0).contentWindow.print();

                        // showSuccess('',ress.message);

                    });


                    }




                });



            });
        }



        //=======================
        // sample when long press
        //=======================
        function longpress(callback) {
            // (function() {

            // Create variable for setTimeout
            var delay;

            // Set number of milliseconds for longpress
            var longpress = 1300;

            let listItems = $(".cart-detail-item");
            $.each(listItems, function(idx, value) {
                let listitem =
                    $(this).mousedown(function(e) {
                        var self = $(this);

                        function check() {
                            console.log('long pressed', self);
                        }
                        delay = setTimeout(check, longpress);
                    }).mouseup(function() {
                        clearTimeout(delay);

                    }).mouseout(function() {
                        clearTimeout(delay);
                    });
            });

        }

        function isLongPress() {
            var startTime, endTime, longpress;
            $(".cart-detail-item").on('click', function() {
                console.log(longpress);
                if (longpress) {
                    alert("redirect to editing product page", $(this).find('[data-toggle="popover"]'));


                    $(this).find('[data-toggle="popover"]').popover('hide');


                } else {
                    console.log("show popover", $(this).find('.title'));
                    $(this).find('[data-toggle="popover"]').popover('show');


                }

            });

            $(".cart-detail-item").on('mousedown', function() {
                startTime = new Date().getTime();
            });

            $(".cart-detail-item").on('mouseup', function() {
                endTime = new Date().getTime();
                longpress = (endTime - startTime > 500) ? true : false;
            });
        }

        function dblClick() {
            /*
            $('.cart-detail-item').on('click', function() {
                var $button = $(this);
                if ($button.data('alreadyclicked')) {
                    $button.data('alreadyclicked', false); // reset


                    if ($button.data('alreadyclickedTimeout')) {
                        clearTimeout($button.data('alreadyclickedTimeout')); // prevent this from happening
                    }
                    // do what needs to happen on double click.
                    $(this).find('[data-toggle="popover"]').popover('hide');

                    $('.cart-detail-item [data-toggle="popover"]').popover('hide');

                    console.log("redirect oi to edit the osdetail");
                } else {
                    $button.data('alreadyclicked', true);

                    var alreadyclickedTimeout = setTimeout(function() {
                        $button.data('alreadyclicked', false); // reset when it happens
                        // do what needs to happen on single click.
                        // use $button instead of $(this) because $(this) is
                        // no longer the element
                        let current_popover = $($button).find('[data-toggle="popover"]');
                        current_popover.popover('show');
                    }, 300); // <-- dblclick tolerance here
                    $button.data('alreadyclickedTimeout', alreadyclickedTimeout); // store this id to clear if necessary
                }
                return false;
            });
            */
            $('.cart-item > td').not('.cart-remove').on('click', function() {
                var $button = $(this);
                if ($button.data('alreadyclicked')) {
                    $button.data('alreadyclicked', false); // reset


                    if ($button.data('alreadyclickedTimeout')) {
                        clearTimeout($button.data('alreadyclickedTimeout')); // prevent this from happening
                    }
                    // do what needs to happen on double click.

                    // console.log("redirect oi to edit the osdetail");
                    btnProductEdit($(this).parent());
                } else {
                    $button.data('alreadyclicked', true);

                    var alreadyclickedTimeout = setTimeout(function() {
                        $button.data('alreadyclicked', false); // reset when it happens
                        // do what needs to happen on single click.
                        // use $button instead of $(this) because $(this) is
                        // no longer the element

                    }, 300); // <-- dblclick tolerance here
                    $button.data('alreadyclickedTimeout', alreadyclickedTimeout); // store this id to clear if necessary
                }
                return false;
            });

        }


        /** ITEM DELETION */
        $('.cart-remove-item').on('click', function(){
            var self = $(this);
            var data = {
                branch_id : self.data('branch-id'),
                header_id : self.data('orderslip-id'),
                osd_id : self.data('orderslip-detail-id'),
                device_id : self.data('device-id'),
                outlet_id : self.data('outlet-id'),
                product_id : self.data('product-id'),
                mealstub_product_id: self.data('mealstub-product-id'),
                sequence : self.data('sequence'),
                // _method : 'DELETE'
            };

            post('/orderslip/is_paid', data, function (res) {
                if(res.success == false){
                    showError('', res.message, function() {});
                    return;
                }

                if(res.data == 1){
                    showError('', 'Ooops, you cannot modify the item when the order is already paid', function() {});
                    return;
                }

                data._method = 'DELETE';
                Swal.fire({
                    title: 'Are you sure you want to delete the selected item?',
                    text: "You won't be able to revert this",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Please Remove!'
                    }).then((result) => {
                        if (result.value) {
                            // console.log('remove remove');
                            post('/orderslip/remove-selected-item',
                                data,
                                function(ress){
                                    console.log('remove', ress);
                                    if(res.success == false){
                                        console.log('remove success ' ,res.success )
                                        console.log('remove success ' ,res.message )
                                        showError('', ress.message, function () {});
                                        return;
                                    }

                                redirectTo('');
                            });
                        }
                });
            });


            // console.log(data);



        });

        /**
         * Instruction per os
         */
        function btnInstruction(){
            $('#btn-add-instruction').on('click', function(){

                console.log('ok');

                var self = $(this);
                var data = {
                    branch_id: self.data('branch-id'),
                    os_id: self.data('os-id'),
                    outlet_id: self.data('outlet-id'),
                    device_id: self.data('device-id')
                };



                Swal.showLoading();
                post('/orderslip/get-instruction', data, function(res){
                    Swal.hideLoading();

                    if(res.success == false){
                        Swal.fire(res.message);
                        return;
                    }


                    // ok
                    Swal.fire({
                        title: 'Instruction',
                        input: 'textarea',
                        inputValue: res.data.remarks,
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        // showCancelButton: true,
                        showCloseButton: true,
                        confirmButtonText: 'Save',
                        showLoaderOnConfirm: true,

                        preConfirm: (input) => {
                            return input;
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.value) {
                            console.log(result);
                            data._method = 'PATCH';
                            data.remarks = result.value?result.value:'';

                            console.log(data);
                            post('/orderslip/update-instruction', data, function(ress){
                                console.log(ress);
                                showSuccess('',ress.message, function(){

                                });
                            });
                        }
                    });

                });

            });
        }

        function btnProductEdit(element){

            var header_id  = element.data('orderslip-id');
            var main_product_id = element.data('product-id');
            var sequence = element.data('sequence');
            var device_id = element.data('device-id');
            var branch_id = element.data('branch-id');
            var outlet_id = element.data('outlet-id');

            var data = {
                header_id : header_id,
                branch_id : branch_id,
                outlet_id : outlet_id,
                device_id : device_id,
                main_product_id : main_product_id,
                sequence : sequence,
                data : null
            };



            // check if the orderslip is paid or not
            post('/orderslip/is_paid', data, function (res) {
                if(res.success == false){
                    showError('', res.message, function() {});
                    return;
                }

                if(res.data == 1){
                    showError('', 'Ooops, you cannot modify the item when the order is already paid', function() {});
                    if(localStorage.getItem('edit-order-slip') !=null){
                        localStorage.removeItem('edit-order-slip');
                        localStorage.removeItem('nmc');
                        redirectTo('/');
                    }
                    return;
                }

                if(element.hasClass('stub')){

                    // show modal
                    let modal = $('#mealstub_verification');
                    modal.modal({ backdrop: 'static'});
                    $("#mealstub_verification .title").text('Edit Claim stub');
                    $("#mealstub_verification .message").text('Do you want to claim it?');
                    modal.modal('show');
                    let isTakeOut = 0;

                    data.mealstub_number = element.data('mealstub-num');

                    $('#to').on('click' , ()=>{
                        isTakeOut = 2;
                        data.ordertype = isTakeOut;
                        updateOStypeMealstub(data, modal);

                    });

                    $('#di').on('click' , ()=>{
                        isTakeOut = 1;
                        data.ordertype = isTakeOut;
                        updateOStypeMealstub(data, modal);

                    });



                }else{

                    setStorage('edit-order-slip', JSON.stringify(data));
                    redirectTo('/edit-order');
                }

            });



        }

        function updateOStypeMealstub(data, modal){
            data._method = 'PATCH';
            post('/mealstub/update_os_type', data, function(ress){
                // console.log('update', ress);
                if(ress.success == false){
                    showError('', ress.message, function () {

                    });
                    // console.log('update failed', ress);
                    return;
                }
                modal.modal("toggle");
                showSuccess('',ress.message, function(){
                    redirectTo('');
                });


            });
        }
    </script>
    @yield('js')
</body>

</html>
