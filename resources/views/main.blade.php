@extends('layouts.master')

@section('title','Main')

@section('css')
<style>

    .bg-trans{
        background: transparent;
    }

    @media(max-width:456px){
        .date-time{
            width: 10%;
        }
        .actions{
            min-width: 5%;
        }
        .is-paid{
            min-width: 3%;
        }
    }

    .fab {
        width: 70px;
        height: 70px;
        background-color: red;
        border-radius: 50%;
        box-shadow: 0 6px 10px 0 #666;

        font-size: 50px;
        line-height: 70px;
        color: white;
        text-align: center;

        position: fixed;
        right: 50px;
        bottom: 50px;

        transition: all 0.1s ease-in-out;
    }

    .fab:hover {
    box-shadow: 0 6px 14px 0 #666;
    transform: scale(1.05);
    }
</style>

@endsection

@section('content')

@include('alerts.alert')

@if( request()->cookie('device_type') == 'kiosk')
    {{-- <div class="h-100 align-items-center justify-content-center">
        <div></div>
        <button class="btn btn-lg btn-info ">Create Order</button>
    </div> --}}
    <form action="{{ route('orderslip.create-empty-record') }}" method="post">
        <div class="d-flex h-100 align-items-center justify-content-center">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top img-fluid" src="/assets/images/order.jpg" alt="Card image cap">
                <div class="card-body">
                    @csrf
                <button class="btn btn-primary btn-block">Create Order</button>
                </div>
            </div>
        </div>

    </form>
@else
<div class="mb-2">
    <div class="row d-flex">
        <div>
            <form action="{{ route('orderslip.create-empty-record') }}" method="post">
                @csrf
                <button class="btn btn-lg btn-primary">
                <i class="fa fa-plus"></i>
                Click this box to create new Order Slip
                </button>
            </form>
        </div>
        @if (Auth::user()->activeOrder())
            <div>
                <a class="btn btn-lg btn-info" href="{{ route('categories') }}">
                    <i class="ei-package"></i>
                    <span class="title">Choose Menu</span>
                </a>
            </div>
        @endif

    </div>
    <!-- <button data-toggle="modal" data-target="#side-modal-r" class="btn btn-xs btn-primary">
            <i class="ti-menu"></i>
        </button> -->
    <!-- <a href="{{ route('orderslip.create') }}" class="btn btn-xs btn-primary">
        <i class="ti-filter"></i>
        Click this box to create new Orderslip
    </a> -->





</div>

{{-- card type style --}}
<div class="row">

    <!-- <div class="col-md-6 col-sm-6">
            <a href="/orderslip/create">
                <div class="card">
                    <div class="overlay-dark bg-primary">
                        <div class="card-block">
                            <div class="">
                                <div class="tag tag-warning">Notification</div>
                                <h2>
                                    Click this box to create new Orderslip
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-sm-6">
            <a href="/">
                <div class="card">
                    <div class="overlay-dark bg-info">
                        <div class="card-block">
                            <div class="">
                                <div class="tag tag-warning">Notification</div>
                                <h2>
                                    View Item(s) that is ready for pickup!
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div> -->
    @if(!empty($os))
        @foreach( $os as $key => $item)
        <div class="col-6 col-sm-4 col-md-4 col-lg-3 px-1">
            {{-- <a href="{{ route('orderslip.change', ['id'=>$item->orderslip_header_id, 'device_id' => $item->device_no]) }}"> --}}
                <div class="card mb-1">
                    <div class="overlay-dark {{ ( $item->encoded_by == auth()->user()->username && $item->is_active == 1) ? 'bg-success' : ( $item->is_paid == 1? 'bg-info':'bg-secondary') }}">
                        <div class="card-block p-3">
                            <div class="">
                                <div class="">
                                    @if( $item->encoded_by == auth()->user()->username && $item->is_active == 1)
                                    <span class="tag tag-success">Current</span>
                                    @else
                                    <span class="tag" style="display:hidden"></span>
                                    @endif

                                    @if ($item->is_paid == 1)
                                    <span class="tag text-light pull-right border"> Paid </span>
                                    @else

                                    @endif
                                </div>

                                <h4 class="text-center os-num">OS # {{ $item->osnumber }}
                               </h4>


                                <!-- <div class="text-white">
                                    Pending No : {{ $item->branch_services_id }}
                                </div> -->
                                <div class="text-white text-opacity">
                                    <span class="d-flex align-items-center">
                                        <i class="ti-calendar mr-2"></i>
                                        <span>{{ \Carbon\Carbon::parse($item->encoded_date)->format('Y-m-d g:i A') }}</span>
                                    </span>
                                    <span class="d-flex align-items-center">
                                        <i class="ti-mobile mr-2"></i>
                                        <span>{{ $item->device_no }}</span>
                                    </span>
                                    @if($item->qdate)
                                    <span class="d-flex align-items-center">
                                        <i class="ti-time mr-2"></i>
                                        <span>
                                        {{ ( \Carbon\Carbon::parse($item->encoded_date)->diffInHours(\Carbon\Carbon::parse($item->qdate)) ) }}:{{ ( \Carbon\Carbon::parse($item->encoded_date)->diffInMinutes(\Carbon\Carbon::parse($item->qdate)) ) % 60 }}:{{ ( \Carbon\Carbon::parse($item->encoded_date)->diffInSeconds(\Carbon\Carbon::parse($item->qdate)) % 60 ) }}
                                        </span>
                                    </span>
                                    @else
                                    <span class="d-flex align-items-center">
                                        <i class="ti-time mr-2"></i>
                                        <span>
                                        -:--
                                        </span>
                                    </span>
                                    @endif
                                </div>
                                <hr class="mt-1 mb-2">
                                    <div class="btn-group btn-group-lg pull-right" role="group" aria-label="Basic example">
                                        {{-- <button type="button" class="btn text-white border bg-trans  "><i class="ei ei-exchange-5"></i></button> --}}

                                        @if( $item->is_paid != 1 )
                                        <button
                                            data-orderslip-header-id="{{ $item->orderslip_header_id }}"
                                            data-device-id="{{ $item->device_no }}"
                                            data-os-number="{{ $item->osnumber }}"
                                            type="button"
                                            class="btn text-white border bg-danger btn-change-os"
                                            ><i class="ei ei-exchange-5"></i></button>
                                        {{--
                                        <a
                                        class="btn text-white border bg-trans"
                                        href="{{ route('orderslip.change', ['id'=>$item->orderslip_header_id, 'device_id' => $item->device_no]) }}"><i class="ei ei-deal"></i></a>
                                        --}}

                                        @endif

                                        <button
                                            type="button"
                                            class="btn text-white border bg-info btn-view-os"
                                            {{-- data-toggle="modal"
                                            data-target="#modal-view-os" --}}
                                            data-branch-id="{{ $item->branch_id }}"
                                            data-os-id="{{ $item->orderslip_header_id }}"
                                            data-outlet-id="{{ $item->outlet_id }}"
                                            data-device-id="{{ $item->device_no }}"
                                        ><i class="ei ei-show"></i></button>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            {{-- </a> --}}
        </div>
        @endforeach
    @endif



</div>
<div class="d-flex justify-content-center flex-wrap mt-2">
    {{ $os->links() }}
</div>


<div class="modal fade" id="modal-view-os">
    <div class="modal-dialog modal-lg modal-dialog-scrollable " role="document">
        <div class="modal-content">
            <div class="modal-header border d-flex align-items-center">
                <div>
                    {{-- TODO: if paid turn the modal title to text-danger else text-primary --}}
                    <h3 class="modal-title text-danger" id="vo_os_number">#</h3>
                    <div class="">
                        <div>
                             <i class="ei ei-captain" aria-label="server name icon"></i> <label id="cce_name"></label>
                        </div>
                        <div>
                            <i class="ei ei-calendar" aria-label="cashier name icon"></i> <label id="date_created">
                           </label>
                        </div>
                    </div>
                </div>

                <a class="modal-close " href="#" data-dismiss="modal">
                    <i class="ti-close"></i>
                </a>

            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-sm  table-hover">
                            <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            {{-- main item lang kasi wlang components --}}
                            <tbody id="osd_container">
                                <tr aria-controls="group-of-rows-3">
                                    <td>1</td>
                                    <td>Chicken Ala King</td>
                                </tr>
                            </tbody>
                            {{-- end of group 3 --}}
                        </table>
                    </div>

                </div>

                {{-- instructions in order --}}
                <!-- <div>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis, vero? Magnam suscipit sequi fugit ipsum tempore blanditiis reprehenderit vitae ratione, quia hic dolores quos unde porro excepturi illum vel! Facilis.
                </div> -->

            </div>
            {{-- make the modal footer "button takeover" visible only if the orderslip is still not paid else hide it
                so that the ambulant can no longer takeover the orderslip
                --}}
            <div class="modal-footer">
                <h3 id="total_amount">Total: 00.00</h3>
                <!-- <button class="btn btn-primary"><i class="ei ei-deal"></i> Take Over</button> -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="welcome">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                   <h1>WELCOME!</h1>
                   <br>
                </div>
                <div class="text-center">
                    <img class="img-responsive mrg-horizon-auto mrg-vertical-25" src="/assets/images/user.png" alt="">
                    <h4>{{ strtolower(Auth::user()->name) == 'evelyn' ? 'Ms, Beautiful':'' }} {{ Auth::user()->name }}</h4>

                    <!-- <h5>DATE:{{ date('d-M-y')}}</h5>
                    <h5>TIME:{{ date('g:i A')}}</h5> -->

                    {{-- <p>@Ambulant</p>

                        <a href="javascript:void(0);">
                            <i class="ti-home pdd-right-5"></i>
                            <span>Branch</span>
                            <span class="label label-info mrg-left-5"> {{ config('ambulant.branch_id') }}</span>
                        </a>
                        <div class="mt-2"></div>
                        <a href="javascript:void(0);">
                            <i class="ti-shopping-cart pdd-right-5"></i>
                            <span>Outlet</span>
                            <span class="label label-info mrg-left-5">{{ auth()->user()->outlet_id }}</span>
                        </a>
                        <div class="mt-2"></div>
                        <a href="javascript:void(0);">
                            <i class="ti-mobile pdd-right-5"></i>
                            <span>Device</span>
                            <span class="label label-info mrg-left-5">{{ auth()->user()->device_no }} </span>
                        </a>
                    --}}
                </div>
                <hr>
                {{-- <h2 class="text-center">Login Details</h2>
                <div class="table-overflow">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Branch</th>
                                <td class="text-right">
                                <span class="label label-info mrg-left-5">{{ getBranchName()->branch_name }}</span>
                                </td>

                            </tr>
                            <tr>
                                <th>Outlet</th>
                                <td class="text-right">
                                <span class="label label-info mrg-left-5">{{getUserOutletId()}}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Device</th>
                                <td class="text-right">
                                <span class="label label-info mrg-left-5">{{getDeviceName()->name}}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>POS ID</th>
                                <td class="text-right">
                                <span class="label label-info mrg-left-5">{{ getUserPosId()->pos_id }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}
            </div>

        </div>
    </div>
</div>
@endif

@endsection
@section('js')
    <!-- page plugins js -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.js"></script>

    <script>
        $('#os-slips').DataTable();

        $(document).ready(function(){

            @if (session('loggedin'))
            $('#welcome').modal('toggle');

            // Swal.fire({
            //     title: 'Welcome!',
            //     imageUrl: "images/thumbs-up.jpg"
            //     timer: 2000,
            //     showConfirmButton: false});
            @endif


            $('.btn-change-os').on('click', function(){
                var self = $(this);
                var data = {
                    orderslip_header_id : self.data('orderslip-header-id'),
                    device_id : self.data('device-id'),
                    osnumber : self.data('os-number')
                };

                // /orderslip/1/23/change     23=device_id | 1=orderslip_header_id

                Swal.fire({
                    title: 'Do you want to takeover selected Order Slip # <br>'+ data.osnumber +'?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText:'No',
                    confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.value) {
                            redirectTo(`/orderslip/${data.orderslip_header_id}/${data.device_id}/change`);
                        }
                });

            });

            $('.btn-view-os').on('click', function(){
                var self = $(this);

                var data = {
                    branch_id: self.data('branch-id'),
                    os_id: self.data('os-id'),
                    outlet_id: self.data('outlet-id'),
                    device_id: self.data('device-id')
                };

                post('/orderslip-info', data, function(res){
                    console.log(res);
                    var modal = $('#modal-view-os');
                    var totalAmount = 0;


                    if( res.header.total_amount > 0){
                        totalAmount = res.header.total_amount;
                    }

                    modal.find('#vo_os_number').text( '#' + res.header.os_number );
                    modal.find('#cce_name').text( res.header.cce_name);
                    modal.find('#date_created').text( res.header.date_created);
                    modal.find('#total_amount').text( 'Total: '+ totalAmount);

                    modal.find('#osd_container').empty();

                    $.each(res.details, function(k,v){
                        modal.find('#osd_container').append(`
                            <tr aria-controls="group-of-rows-3">
                                <td> ${ v.qty } </td>
                                <td> ${ v.amount ? v.amount:'' } </td>
                                <td>

                                ${ v.product_id == v.main_product_id ?

                                    `
                                    <b>${ v.name }</b>
                                    `
                                    :
                                    `
                                    <strong>-</strong> ${ v.name }
                                    `
                                }



                                </td>
                            </tr>
                        `);
                    });

                    modal.modal('toggle');
                });

               {{-- console.log(self);
                // data-branch-id="{{ $item->branch_id }}"
                //                         data-os-id="{{ $item->orderslip_header_id }}"
                //                         data-outlet-id="{{ $item->outlet_id }}"
                //                         data-device-id="{{ $item->device_no }}"
                --}}
            });

        });

    </script>
@endsection
