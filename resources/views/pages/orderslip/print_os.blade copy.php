<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    
    <style>
         @media print{
           
            @page{
              width: 58mm;
              margin: 0;
              padding: 0;
            }

           
            
        } 
        
        @media screen {
            #invoice-POS{
                width: 54mm;
                margin: 0 auto;
                padding: 0 2mm !important;
            }
        }
        </style>

<style>
  
    body{
        padding: 0;
        margin: 0 ;
        font-size: 10pt;
        font-family: Arial, Helvetica, sans-serif;
        background: #eee;
    }
        #invoice-POS {
            width: 58mm;
            margin: 0 auto;
            background: #FFF;
            padding: 0 ;
            padding-bottom: 13mm !important;
            box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
        }
        
        #invoice-POS h1 {
            font-size: 1.15rem;
            color: #222;
        }
        
          
        
        #top .info{
            text-align: center;
        }
        #invoice-POS h3 {
           
            font-weight: 300;
            line-height: 2em;
        }
        
        #invoice-POS p {
         
            color: #666;
            line-height: 1.2em;
        }
        
        
            
            #invoice-POS .info {
                display: block;
                
            }
            #top{
                padding-top: 1rem
            }
            /* table */
            #invoice-POS table {
                width: 100%;
                border-collapse: collapse;
            } 
            
            
            #invoice-POS #bot{
                /* padding-bottom: 10mm !important; */
                
            }

            /* table */
            .table-borderless tbody+tbody, .table-borderless td, .table-borderless th, .table-borderless thead th {
                border: 0;
            }
            
            .table-sm td, .table-sm th{
                padding: 0.3rem;
            }

            th {
                text-align: inherit;
            }
            .text-center{
                text-align: center;
            }
            .my-0{
                margin-bottom: 0;
                margin-top:0;
            }
            .mb-0{
                margin-bottom: 0;                
            }
            

            .barcode {
                /* width: 100%;
                height: 100%; */
            }

            #qrcode img{
                margin: auto;   
            }

            footer {
                bottom: 0;
                transform: rotate(90deg);
                min-height:  420px;
            }   
            svg{
                transform-origin: top left;
                transform: rotate(90deg) translate(0px, -180px)!important;
            }

            </style>

</head>

<body>
    <div id="invoice-POS">
        
        <div id="top">
            <div class="info" class=""> 
                <h2>Enchanted Kingdom</h2>
            </div><!--End Info-->
        </div><!--End InvoiceTop-->
        
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
                            <td>Currency</td>
                            <td>PHP</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div><!--End Invoice Mid-->
        
        <div id="bot">
            <div id="table">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Qty</th>
                            <th>Item</th>    
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(Auth::user()->activeOrder()->printComponents() as $detail)

                            @if($detail->mealstub_product_id != null && $detail->main_product_comp_id != null && $detail->main_product_component) 
                            @else 
                                <tr>
                                    <td>{{number_format($detail->qty)}}</td>
                                    <td> 
                                        {!! $detail->product_id != $detail->main_product_id ? '&nbsp;&nbsp;&nbsp;-':'' !!}

                                        {!! ( $detail->mealstub_product_id != null && $detail->product_id == $detail->main_product_id ? ($detail->mealstub_product_id != $detail->product_id ? "-":''):'' ) !!}
                                         
                                        {{ $detail->sitePart->product_description }}
                                    </td>
                                </tr>
                            @endif      
                                 
                        @endforeach
                        
                    </tbody>
                </table>
            </div><!--End Table-->
            
            <footer>
                <h4 class="text-center mb-0">OS NUMBER</h4>
                    <!-- <div class="barcode-cont" style="height: 420px" data-barcode-val="{{Auth::user()->activeOrder()->os_no}}">
                        <svg class="barcode" style="display:block; margin:auto;" preserveAspectRatio="xMidYMid slice" jsbarcode-format="code39" jsbarcode-value="{{trim(Auth::user()->activeOrder()->os_no)}}" jsbarcode-textPosition="top" jsbarcode-textmargin="2" jsbarcode-fontoptions="bold" jsbarcode-height="40" jsbarcode-width="1" jsbarcode-fontSize="19">
                        </svg>
                        {{-- <svg class="barcode" style="display:block; margin:auto;" preserveAspectRatio="xMidYMid slice" >
                        </svg> --}}
                    </div> -->
                <h3 class="text-center my-0" >{{Auth::user()->activeOrder()->os_no}}</h3>
                <div id="qrcode" data-barcode="{{Auth::user()->activeOrder()->os_no}}"></div>
            </footer>
            
        </div><!--End InvoiceBot-->
    </div><!--End Invoice-->
    
    <script src="/assets/js/vendor.js"></script>
    <script src="/js/config.js"></script>
    <script src="/assets/js/JsBarcode.all.min.js"></script>
    <script src="/assets/js/qrcode.min.js"></script>
    
    <script >
        
        $(document).ready(function() {
            // pull all the order conten

            // initialize barcodes
            JsBarcode(".barcode").init();         
            let barcode_data = $('.barcode-cont').attr("data-barcode-val");
           
            JsBarcode(".barcode", barcode_data.trim() , {
                format: "code39",
                width: 1,
                height: 100,
                fontSize: 14,
                textPosition:"top"
            });  

            /*
            // qr code
            let qrcodeElem = document.getElementById("qrcode");
            var qrcode = new QRCode(qrcodeElem, {
	            text: qrcodeElem.dataset.barcode.trim(),
	            width: 128,
	            height: 128,
	            colorDark : "#000000",
	            colorLight : "#ffffff",
	            correctLevel : QRCode.CorrectLevel.Q
            });
            */
            // window.onload = function() { 
                window.print();
            // }
        });

     </script>
</body>

</html>
