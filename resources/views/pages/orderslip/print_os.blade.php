<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print OS</title>

    <link href="/assets/css/font-awesome.min.css" rel="stylesheet">


    <style>
        @media print {

            @page {
                width: 58mm;
                margin: 0;
                padding: 0;
            }
        }

        @media screen {
            #invoicePOS {
                /* width: 48mm; */
                margin: 0 auto;
                padding: 0 2mm !important;
            }
            svg{
            width:100%;
            /* margin-bottom: 10; */
            /* transform-origin: top left;

            transform: rotate(90deg) translate(0px, -180px)!important; */
        }
        }

    </style>

    <style>
        body {
            padding: 0;
            margin: 2%;
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
            background: #eee;
            /* margin-bottom:5%; */
        }

        #invoicePOS {
            width: 58mm;
            /* margin: 0 auto; */
            background: #FFF;
            /* padding: 0; */
            /* padding-bottom: 13mm !important; */
            box-shadow: 0 .5mm 2mm rgba(0, 0, 0, .3);
        }

        #invoicePOS h1 {
            font-size: 1.15rem;
            color: #222;
        }



        #top .info {
            text-align: center;
        }

        #invoicePOS h3 {

            font-weight: 300;
            line-height: 2em;
        }

        #invoicePOS p {
            color: #666;
            line-height: 1.2em;
        }



        #invoicePOS .info {
            display: block;

        }

        /* #top {
            padding-top: 1rem
        } */

        /* table */
        #invoicePOS table {
            width: 100%;
            border-collapse: collapse;
        }


        #invoicePOS #bot {
            /* padding-bottom: 10mm !important; */

        }

        /* table */
        .table-borderless tbody+tbody,
        .table-borderless td,
        .table-borderless th,
        .table-borderless thead th {
            border: 0;
        }

        .table-sm td,
        .table-sm th {
            padding: 0.3rem;
        }

        th {
            text-align: inherit;
        }

        .text-center {
            text-align: center;
        }

        .my-0 {
            margin-bottom: 0;
            margin-top: 0;
        }

        .mb-0 {
            margin-bottom: 0;
        }


        .barcode {
            width: 100%;
            height: 100%;
            margin-bottom: 5%;

        }

        #qrcode img {
            margin: auto;
        }

        footer {
            bottom: 5;
            transform: rotate(90deg);
            /* min-height:  420px; */
        }
        svg{
            width:100%;
            margin-bottom: 5%;
            /* transform-origin: top left;

            transform: rotate(90deg) translate(0px, -180px)!important; */
        }
        .notice{
            width:80%;
            margin:auto;
            min-height:100vh;
            display:flex;
            flex-direction:column;
            justify-content: center;
            align-items:center;

        }

        .notice > div{
            /* padding: 25px; */
            /* border-radius: 10px; */
            background: #f3b23b;
            text-align:center;
            color: white;
        }

        .notice a{
            color:#916411;
        }

    </style>

</head>

<body>
    @if(! Auth::user()->activeOrder() )
        <div class="notice">
            <div>
                <h2>Ooops! You have no current orderslip yet. <br>This is either because someone else take over your orderslip or it has been removed </h2>
                <h3>Redirect to <a href="/">Home</a> after 3 seconds</h3>
            </div>
        </div>
    @else
    <div id="invoicePOS">

        <div id="top">
            <div class="info" class="">
                <h2>Enchanted Kingdom</h2>
            </div>
            <!--End Info-->
        </div>
        <!--End InvoiceTop-->

        <div id="mid">
            <div class="info">
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="title">OS</td>
                            <td class="desc">{{Auth::user()->activeOrder()->osnumber}}</td>
                        </tr>

                        <tr>
                            <td>Server</td>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <td>Date/Time</td>
                            <td>@php $encoded = new DateTime(Auth::user()->activeOrder()->encoded_date);  @endphp
                                {{  $encoded->format('Y-m-d h:i:s a')}}</td>
                        </tr>
                        <tr>
                            <td>Head Count</td>
                            <td>{{ Auth::user()->activeOrder()->total_hc }}</td>
                        </tr>
                        <tr>
                            <td>Currency</td>
                            <td>PHP</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <!--End Invoice Mid-->

        <div id="bot">
            <div id="table">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th style="min-width:10%">Qty</th>
                            <th>Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $mealstub_pro = null; $mealstub_sn=null;
                        @endphp
                        @foreach(Auth::user()->activeOrder()->printComponents() as $detail)

                            @if($detail->mealstub_product_id != null && $detail->main_product_comp_id != null && $detail->main_product_component)
                            @else
                                @if($detail->mealstub_serialnumber != null)
                                    @if( $detail->mealstub_serialnumber != $mealstub_sn)
                                    <tr class="cart-item stub">
                                        <td></td>
                                        <td>{{number_format(1)}}</td>
                                        <td class="">CS-{{ chunk_split($detail->mealstub_serialnumber, 18) }}</td>
                                    </tr>
                                        @php $mealstub_sn = $detail->mealstub_serialnumber; @endphp
                                    @endif
                                @endif
                                <tr>
                                    <td><i class="{{ $detail->order_type == 1 ? 'fa fa-dot-circle-o text-primary' : 'fa fa-shopping-bag text-primary' }}"></i> </td>
                                    <td>{{number_format($detail->qty)}}</td>
                                    <td>
                                        {!! $detail->product_id != $detail->main_product_id ? '&nbsp;&nbsp;&nbsp;-':'' !!}

                                        {!! ( $detail->mealstub_product_id != null && $detail->product_id == $detail->main_product_id ? ($detail->mealstub_product_id != $detail->product_id ? "-":''):'' ) !!}

                                        {{ chunk_split($detail->sitePart->product_name,18) }}

                                    </td>
                                </tr>
                            @endif

                        @endforeach

                    </tbody>
                </table>
                <div>
                    <h4 class="text-center mb-0">OS NUMBER</h4>
                    <h3 class="text-center my-0">{{trim(Auth::user()->activeOrder()->os_no)}}</h3>
                    {{--start template --}}
                    {{-- <div class="barcode-cont"  data-barcode-val="1234-56789101112131415123">
                        <svg class="barcode" style="display:block; margin:auto; "
                            jsbarcode-format="code39"
                            jsbarcode-value="1234-56789101112131415"
                            jsbarcode-textPosition="top"
                            jsbarcode-textmargin="2"

                            jsbarcode-height="100"
                            jsbarcode-width="1" jsbarcode-fontSize="14"
                            >
                        </svg>
                    </div> --}}
                    {{-- end template --}}

                    @if( request()->cookie('barcode_type') == "QRCODE")
                        <div id="qrcode" data-barcode="{{trim(Auth::user()->activeOrder()->os_no)}}"></div>
                    @else
                        <div class="barcode-cont"  data-barcode-val="{{ trim(Auth::user()->activeOrder()->os_no) }}">
                            <svg class="barcode" 
                                jsbarcode-format="{{ strtolower(request()->cookie('barcode_type')) }}"
                                jsbarcode-value="{{ trim(Auth::user()->activeOrder()->os_no) }}"
                                jsbarcode-textPosition="top"
                                jsbarcode-textmargin="4"
                                jsbarcode-fontoptions="bold"
                                jsbarcode-height="60"
                                jsbarcode-width="2"
                                jsbarcode-fontSize="20"
                                
                                >
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
            <!--End Table-->

            <footer style="height: 70px">
            </footer>

        </div>
        <!--End InvoiceBot-->
    </div>
    @endif
    


    <!--End Invoice-->


     <script src="/assets/js/vendor.js"></script>
     <script src="/js/config.js"></script>
     <script src="/assets/js/JsBarcode.all.min.js"></script>
     <script src="/assets/js/qrcode.min.js"></script>
     
    <script>
        $(document).ready(function () {
   
            $barcode_container = $('.barcode-cont');
            if($barcode_container != null){
                // JsBarcode(".barcode").init();
                let barcode_data = $('.barcode-cont').attr("data-barcode-val");
                // console.log(barcode_data);
                JsBarcode(".barcode", barcode_data, {
                    width: 5,
                    height: 240,
                    fontSize: 20,
                    textPosition: "top",
                    displayValue: false
                });

                // $barcode_img_height = $('.barcode').height();
                // $barcode_img_height = $barcode_img_height + 250;
                // $('.barcode-cont').css("height", $barcode_img_height + 'px');
            }

            // initialize barcodes
            let qrcodeElem = document.getElementById("qrcode");
            if(qrcodeElem != null){
                // console.log(qrcodeElem.dataset.barcode);
                var qrcode = new QRCode(qrcodeElem, {
                    text: qrcodeElem.dataset.barcode.trim(),
                    width: 128,
                    height: 128,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.Q
                });
            }

            if($('.notice').html() != null ){
                setTimeout(() => {
                    redirectTo('/');
                }, 3000);
            }
           
            // }else{
                    // w=window.open();
                    // w.document.write(printContents);
                    // w.print();
                    // w.close();
                   
                // console.log(window.print);
                // window.print();
                // if (window.matchMedia) {
                // var mediaQueryList = window.matchMedia('print');
                // mediaQueryList.addListener(function(mql) {
                //     if (mql.matches) {
                //         // beforePrint();
                //     } else {
                //         redirectTo('/');
                //     }
                // });
          
            // }




        });


    </script>
</body>

</html>
