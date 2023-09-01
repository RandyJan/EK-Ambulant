<!-- Side Panel START -->
<div class="side-panel">
    <div class="side-panel-wrapper bg-white gmd-3">
        <ul class="nav nav-tabs" role="tablist">
            <!-- <li class="nav-item active">
                <a class="nav-link" href="#orderslips" role="tab" data-toggle="tab">
                    <span>PO</span>
                </a>
            </li> -->
            {{--
            <li class="nav-item ">
                <a class="nav-link" href="#current" role="tab" data-toggle="tab">
                    <span>Current</span>
                </a>
            </li>--}}
            <li class="nav-item active">
                <a class="nav-link d-flex flex-column justify-content-center " href="#profile" role="tab" data-toggle="tab">
                    @php
                        $active = Auth::user()->activeOrder();
                    @endphp
                    <span class="font-size-15 {{ ( $active && $active->is_paid == 1 ) ? 'text-info' : 'text-dark' }}" style="line-height:1.2">OS #: {{ $active ? $active->osnumber : '' }} </span>
                    <span class="font-size-15 text-dark" style="line-height:1.2">Transaction #:</span>

                </a>
            </li>

            <!-- <li class="nav-item ">
                <a class="nav-link" href="#todo-list" role="tab" data-toggle="tab">
                    <span>Todo</span>
                </a>
            </li> -->
            <li class="panel-close">
                <a class="side-panel-toggle" href="javascript:void(0);">
                    <i class="ti-close"></i>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- chat START -->
            <!-- <div id="current" role="tabpanel" class="tab-pane fade in active">
                <div class="profile scrollable ps-container ps-theme-default ps-active-y"> -->

            @if( Auth::user()->activeOrder() )
            <div class="ps-container ps-theme-default">

                {{--<div class="row text-center pdd-vertical-20">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6 border right">
                                        <div class="pdd-vertical-10">
                                            <span class="font-size-18 text-dark ">#{{ Auth::user()->activeOrder() ? Auth::user()->activeOrder()->osnumber : '' }}</span>
                <small>Orderslip</small>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="pdd-vertical-10">
                <span class="font-size-18 text-dark">#{{ Auth::user()->activeOrder() ? Auth::user()->activeOrder()->total_hc : '' }}</span>
                <small>Headcount</small>
            </div>
        </div>
    </div>
</div>
</div>--}}



</div>
@endif

<!-- </div>
            </div> -->
<!-- chat END -->

<!-- profile START -->
<div id="profile" role="tabpanel" class="tab-pane fade in active">
    <div class="profile scrollable ps-container ps-theme-default ps-active-y">

        <div class="pl-3 pr-2 ">
            <!-- <h5 class="text-dark mrg-btm-20">Items</h5> -->

            @if( Auth::user()->activeOrder() )
            {{-- {{ Auth::user()->activeOrder()->sa() }} --}}
            {{--
                <div class="row text-center pt-4 pb-3">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6 border right">
                                <div class="pdd-vertical-10">
                                    <span class="font-size-18 text-dark">
                                        {{ number_format(Auth::user()->activeOrder()->discount_amount, 2) }}
                                    </span>
                                    <small>Discount(s)</small>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <div class="pdd-vertical-10">
                                    <span class="font-size-18 text-dark">
                                        {{ number_format(Auth::user()->activeOrder()->net_amount, 2) }}
                                    </span>
                                    <small>Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            --}}
            <!-- <div class="d-flex flex-row justify-content-center mb-2">
                {{--<button class="btn btn-sm btn-outline-primary  mb-0" id="print-bill" >Print Bill</button>--}}
                {{-- <button data-toggle="modal" data-target="#default-modal" class="btn btn-sm btn-primary">Trigger</button> --}}
            </div> -->
            <div class="row text-white text-opacity bg-primary"  style="margin-right:-5px;">
                <div class="col-6 d-flex align-items-center justify-content-center border right"
                    id="print-bill"
                    data-branch-id="{{ Auth::user()->activeOrder()->branch_id }}"
                    data-os-id="{{ Auth::user()->activeOrder()->orderslip_header_id }}"
                    data-outlet-id="{{ Auth::user()->activeOrder()->outlet_id }}"
                    data-device-id="{{ Auth::user()->activeOrder()->device_no }}"
                >
                    <div class="py-3">
                        <a class="text-white text-opacity" href="javascript:void(0);">Checkout</a>
                    </div>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-center border right"
                    id="btn-add-instruction"
                    data-branch-id="{{ Auth::user()->activeOrder()->branch_id }}"
                    data-os-id="{{ Auth::user()->activeOrder()->orderslip_header_id }}"
                    data-outlet-id="{{ Auth::user()->activeOrder()->outlet_id }}"
                    data-device-id="{{ Auth::user()->activeOrder()->device_no }}"
                >
                    <div class="py-3">
                        <a class="text-white text-opacity" href="javascript:void(0);">INSTRUCTION</a>
                    </div>
                </div>
                <div class="col-12 d-flex align-items-center">
                    <div class="">TOTAL: </div>
                    <div class="text-right ml-auto" id="cart-total-amount">  {{ number_format(Auth::user()->activeOrder()->net_amount, 2) }}</div>
                </div>
            </div>

            {{--<div class=" d-flex flex-column justify-content-around p-2 mb-1">
                <div class="font-size-15 text-dark" style="">
                    <span>OS: #</span> <span>{{ Auth::user()->activeOrder() ? Auth::user()->activeOrder()->osnumber : '' }}</span>
                </div>
                <div class="font-size-15 text-dark" style="">
                    <span>Trans #:</span>
                </div>
            </div>--}}
            <!-- <div style="overflow-y:auto; calc(100vh - 181px)"> -->
            <div>
                <table class="table table-sm table-responsive-sm" >
                    <thead>
                        <tr>
                            <th></th>
                            <th>Qty</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody >
                  {{--
                    @foreach(Auth::user()->activeOrder()->printComponents() as $detail)

                            @if($detail->mealstub_product_id != null && $detail->main_product_comp_id != null && $detail->main_product_component)
                            @else
                                <tr>
                                    <td>{{number_format($detail->qty)}}</td>
                                    <td>
                                        {!! $detail->product_id != $detail->main_product_id ? '&nbsp;&nbsp;&nbsp;-':'' !!}

                                        {!! ( $detail->mealstub_product_id != null && $detail->product_id == $detail->main_product_id ? ($detail->mealstub_product_id != $detail->product_id ? "-":''):'' ) !!}


                                        @if( $detail->product_id == $detail->main_product_id)
                                            @if($detail->postmix_id != 0 )
                                                <strong>{{ $detail->sitePart->product_description }}</strong>
                                            @else
                                                {{ $detail->sitePart->product_description }}
                                            @endif
                                        @else
                                            {{ $detail->sitePart->product_description }}
                                        @endif
                                    </td>
                                    @if( $detail->product_id == $detail->main_product_id)
                                        @if($detail->mealstub_product_id == null)
                                    <td>
                                        <a
                                        href="javascript:;"
                                        class="btn btn-danger btn-xs cart-remove-item"
                                        data-branch-id="{{ $detail->branch_id }}"
                                        data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                        data-orderslip-detail-id="{{ $detail->orderslip_detail_id }}"
                                        data-device-id="{{ $detail->device_number }}"
                                        data-outlet-id="{{ $detail->outlet_id }}"
                                        data-product-id="{{ $detail->product_id }}"
                                        data-mealstub-product-id="{{ $detail->mealstub_product_id }}"
                                        data-sequence="{{ $detail->sequence }}"
                                            > <i class="fa fa-trash"></i> </a>
                                    </td>
                                        @elseif($detail->product_id == $detail->mealstub_product_id && $detail->main_product_id == $detail->mealstub_product_id )
                                        <td>
                                        <a
                                        href="javascript:;"
                                        class="btn btn-danger btn-xs cart-remove-item"
                                        data-branch-id="{{ $detail->branch_id }}"
                                        data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                        data-orderslip-detail-id="{{ $detail->orderslip_detail_id }}"
                                        data-device-id="{{ $detail->device_number }}"
                                        data-outlet-id="{{ $detail->outlet_id }}"
                                        data-product-id="{{ $detail->product_id }}"
                                        data-mealstub-product-id="{{ $detail->mealstub_product_id }}"
                                        data-sequence="{{ $detail->sequence }}"
                                            > <i class="fa fa-trash"></i> </a>
                                    </td>
                                        @endif
                                    @endif
                                </tr>
                            @endif

                    @endforeach
                    --}}
                    {{--
                    @php $mealstubCurrent = null; @endphp
                    @foreach(Auth::user()->activeOrder()->printComponents() as $detail)
                        @if($detail->mealstub_product_id != null)
                            @if( $detail->mealstub_product_id != $mealstubCurrent)

                            <tr class="">
                                <td>{{number_format(1)}}</td>
                                <td class=""> <strong><span style="font-size:85%">CS-{{ $detail->mealstub_product_id }}</span><strong></td>
                                <td>
                                    <a
                                    href="javascript:;"
                                    class="btn btn-danger btn-xs cart-remove-item"
                                    data-branch-id="{{ $detail->branch_id }}"
                                    data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                    data-orderslip-detail-id="{{ $detail->orderslip_detail_id }}"
                                    data-device-id="{{ $detail->device_number }}"
                                    data-outlet-id="{{ $detail->outlet_id }}"
                                    data-product-id="{{ $detail->product_id }}"
                                    data-mealstub-product-id="{{ $detail->mealstub_product_id }}"
                                    data-sequence="{{ $detail->sequence }}"
                                        > <i class="fa fa-trash"></i> </a>
                                </td>
                            </tr>
                                @php $mealstubCurrent = $detail->mealstub_product_id; @endphp
                            @endif
                        @endif
                        --}}

                    @php
                        $mealstub_pro = null; $mealstub_sn=null;

                    @endphp
                    @foreach(Auth::user()->activeOrder()->printComponents() as $detail)
                        @if($detail->mealstub_serialnumber != null)
                            @if( $detail->mealstub_serialnumber != $mealstub_sn)
                            <tr class="cart-item stub"
                                data-branch-id="{{ $detail->branch_id }}"
                                data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                data-orderslip-detail-id="{{ $detail->orderslip_detail_id }}"
                                data-device-id="{{ $detail->device_number }}"
                                data-outlet-id="{{ $detail->outlet_id }}"
                                data-product-id="{{ $detail->product_id }}"
                                data-mealstub-product-id="{{ $detail->mealstub_product_id }}"
                                data-mealstub-num="{{$detail->mealstub_serialnumber}}"
                                data-sequence="{{ $detail->sequence }}">

                                <td></td>
                                <td>{{number_format(1)}}</td>
                                <td class=""><strong>CS-{{ $detail->mealstub_serialnumber }}<strong></td>
                                <td class="cart-remove">
                                    <a
                                    href="javascript:;"
                                    class="btn btn-danger btn-xs cart-remove-item"
                                    data-branch-id="{{ $detail->branch_id }}"
                                    data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                    data-orderslip-detail-id="{{ $detail->orderslip_detail_id }}"
                                    data-device-id="{{ $detail->device_number }}"
                                    data-outlet-id="{{ $detail->outlet_id }}"
                                    data-product-id="{{ $detail->product_id }}"
                                    data-mealstub-product-id="{{ $detail->mealstub_product_id }}"
                                    data-sequence="{{ $detail->sequence }}"
                                        > <i class="fa fa-trash"></i> </a>
                                </td>
                            </tr>
                                @php $mealstub_sn = $detail->mealstub_serialnumber; @endphp
                            @endif
                        @endif


                        @if($detail->mealstub_product_id != null && $detail->main_product_comp_id != null)
                        @else
                            @if( $detail->product_id == $detail->main_product_id)
                                @if($detail->mealstub_product_id == null)
                                    <tr class="cart-item"
                                        data-branch-id="{{ $detail->branch_id }}"
                                        data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                        data-device-id="{{ $detail->device_number }}"
                                        data-outlet-id="{{ $detail->outlet_id }}"
                                        data-product-id="{{ $detail->product_id }}"
                                        data-sequence="{{ $detail->sequence }}">
                                @else
                                    <tr class="cart-item " >
                                @endif
                                    {{-- <td><i class="{{ $detail->order_type == 1 ? 'fa fa-shopping-bag text-primary' : 'fa fa-dot-square-o text-primary' }}"></i> </td> --}}
                                    <td><i class="{{ $detail->order_type == 1 ? 'fa fa-dot-circle-o text-primary' : 'fa fa-shopping-bag text-primary' }}"></i> </td>
                                    <td class="">{{number_format($detail->qty)}}</td>
                                    <td> <strong>{{  chunk_split($detail->sitePart->product_description, 16) }}</strong> </td>
                                    @if($detail->mealstub_product_id == null)
                                        <td class="cart-remove">
                                            <a
                                            href="javascript:;"
                                            class="btn btn-danger btn-xs cart-remove-item"
                                            data-branch-id="{{ $detail->branch_id }}"
                                            data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                            data-orderslip-detail-id="{{ $detail->orderslip_detail_id }}"
                                            data-device-id="{{ $detail->device_number }}"
                                            data-outlet-id="{{ $detail->outlet_id }}"
                                            data-product-id="{{ $detail->product_id }}"
                                            data-mealstub-product-id="{{ $detail->mealstub_product_id }}"
                                            data-sequence="{{ $detail->sequence }}"
                                                > <i class="fa fa-trash"></i> </a>
                                        </td>
                                    @endif
                                </tr>
                            @else
                                <tr class="">
                                    {{-- <td><i class="{{ $detail->order_type == 1 ? 'fa fa-shopping-bag text-primary' : 'fa fa-dot-circle-o text-primary' }}"></i> </td> --}}
                                    <td><i class="{{ $detail->order_type == 1 ? 'fa fa-dot-circle-o text-primary' : 'fa fa-shopping-bag text-primary' }}"></i> </td>
                                    <td class="">{{number_format($detail->qty)}}</td>
                                    <td>&nbsp;&nbsp; - {{  chunk_split($detail->sitePart->product_description, 16) }} </td>
                                    <td></td>
                                </tr>

                            @endif

                            {{-- <tr class="cart-item">
                                <td class="">{{number_format($detail->qty)}}</td>
                                <td>

                                    {!! $detail->product_id != $detail->main_product_id ? '&nbsp;&nbsp;&nbsp;-':'' !!}

                                    {!! ( $detail->mealstub_product_id != null && $detail->product_id == $detail->main_product_id ? ($detail->mealstub_product_id != $detail->product_id ? "-":''):'' ) !!}
                                    @if( $detail->product_id == $detail->main_product_id)
                                        @if($detail->postmix_id != 0 )
                                            <strong>{{ $detail->sitePart->product_description }}</strong>
                                        @else
                                            <strong>{{ $detail->sitePart->product_description }}</strong>
                                        @endif
                                    @else
                                        {{ $detail->sitePart->product_description }}
                                    @endif
                                </td>
                                @if( $detail->product_id == $detail->main_product_id)
                                    @if($detail->mealstub_product_id == null)
                                    <td>
                                        <a
                                        href="javascript:;"
                                        class="btn btn-danger btn-xs cart-remove-item"
                                        data-branch-id="{{ $detail->branch_id }}"
                                        data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                        data-orderslip-detail-id="{{ $detail->orderslip_detail_id }}"
                                        data-device-id="{{ $detail->device_number }}"
                                        data-outlet-id="{{ $detail->outlet_id }}"
                                        data-product-id="{{ $detail->product_id }}"
                                        data-mealstub-product-id="{{ $detail->mealstub_product_id }}"
                                        data-sequence="{{ $detail->sequence }}"
                                            > <i class="fa fa-trash"></i> </a>
                                    </td>
                                    @elseif($detail->product_id == $detail->mealstub_product_id && $detail->main_product_id == $detail->mealstub_product_id )
                                    <td>
                                        <a
                                        href="javascript:;"
                                        class="btn btn-danger btn-xs cart-remove-item"
                                        data-branch-id="{{ $detail->branch_id }}"
                                        data-orderslip-id="{{ $detail->orderslip_header_id }}"
                                        data-orderslip-detail-id="{{ $detail->orderslip_detail_id }}"
                                        data-device-id="{{ $detail->device_number }}"
                                        data-outlet-id="{{ $detail->outlet_id }}"
                                        data-product-id="{{ $detail->product_id }}"
                                        data-mealstub-product-id="{{ $detail->mealstub_product_id }}"
                                        data-sequence="{{ $detail->sequence }}"
                                            > <i class="fa fa-trash"></i> </a>
                                    </td>
                                    @endif
                                @endif
                            </tr> --}}
                        @endif

                    @endforeach
                    </tbody>

                </table>
            </div>

            <!-- <div class="row text-white text-opacity bg-primary">
                <div class="col-4  d-flex align-items-center justify-content-center" id="print-bill">
                    <div class="py-3">
                        <a class="text-white text-opacity" href="javascript:void(0);">PRINT</a>
                    </div>
                </div>
                <div class="col-8 d-flex align-items-center">
                    <div class="">TOTAL: </div>
                    <div class="text-right ml-auto">  {{ number_format(Auth::user()->activeOrder()->net_amount, 2) }}</div>
                </div>
            </div> -->
            <!-- <ul class="list-unstyled list-info scrollable" id="cart" style="overflow-y: auto; height: calc(100vh - 265px);"> -->

            {{--
                <li class="cart-detail-item">
                                            <a href="#" id="" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?">
                                                <!-- <img class="thumb-img" src="/assets/images/avatars/thumb-1.jpg" alt=""> -->
                                                <div class="info" style="padding-left:0px;">

                                                    <span class="title">{{ \Str::words($detail->description,4,'...') }} </span>
            <span class="sub-title">x <b>{{ $detail->qty }}</b></span>
            <span class="float-object">{{ number_format($detail->net_amount) }}</span>

            </div>
            </a>
            </li>--}}

            <!-- <li>
                                            <a href="#">
                                                <img class="thumb-img" src="/assets/images/avatars/thumb-4.jpg" alt="">
                                                <div class="info">
                                                    <span class="title">Samuel Field</span>
                                                    <span class="sub-title">have send you a request</span>
                                                    <span class="float-object">7d</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="thumb-img bg-info text-center font-size-25 font-secondary">
                                                        <span class="text-white">E</span>
                                                </span>
                                                <div class="info">
                                                    <span class="title">Espire</span>
                                                    <span class="sub-title">Welcome on Board</span>
                                                    <span class="float-object">7d</span>
                                                </div>
                                            </a>
                                        </li> -->
            <!-- </ul> -->
            {{-- <div class="row text-white text-opacity bg-primary"  style="margin-right:-5px;">
                <div class="col-4 d-flex align-items-center justify-content-center border right" id="print-bill">
                    <div class="py-3">
                        <a class="text-white text-opacity" href="javascript:void(0);">PRINT</a>
                    </div>
                </div>
                <div class="col-8 d-flex align-items-center">
                    <div class="">TOTAL: </div>
                    <div class="text-right ml-auto">  {{ number_format(Auth::user()->activeOrder()->net_amount, 2) }}</div>
                </div>
            </div> --}}
            @endif

        </div>
    </div>
</div>
<!-- profile END -->
</div>
</div>
</div>

<!-- Side Panel END -->
<!-- Collapse -->

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
                            <i class="ei ei-calendar" aria-label="cashier name icon"></i> <label id="date_created"></label>
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

<!-- Modal -->
<div class="modal fade" id="guest-modal" tabindex="-1" role="dialog" aria-labelledby="guest-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border">
                <div class="modal-title" id="">
                    <h5 id="guest-number"></h5>
                    <div style="font-size:90%">
                        <div class="d-inline-block mr-3">
                            <span class="">Table : </span><span id="table-no" data-table-no=""></span>
                        </div>
                        <div class="d-inline-block d-none">
                            <span class="">OS : </span><span id="os-no" data-os-no=""></span>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
                <div class="modal-title" id=""></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="type">
                    <label for="">Type</label>
                    <div class="form-row">
                        <div class="col-6 col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="guest-type" id="type-reg" value="Regular">
                                <label class="form-check-label" for="">
                                    Regular
                                </label>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="guest-type" id="type-sc" value="Senior">
                                <label class="form-check-label" for="">
                                    Senior
                                </label>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="guest-type" id="type-pwd" value="PWD">
                                <label class="form-check-label" for="">
                                    PWD
                                </label>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="guest-type" id="type-zr" value="Zero-Rated">
                                <label class="form-check-label" for="">
                                    Zero Rated
                                </label>
                            </div>
                        </div>



                    </div>
                </div>
                <div id="creds" class="d-none" style="">
                    <hr>
                    <label for="">Discount Credentials</label>
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Name" id="guest-name">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="ID" id="guest-discid">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Address (optional)" id="guest-address">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="TIN (optional)" id="guest-tin">
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" id="cancel">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="save">Save changes</button>
            </div>
        </div>
    </div>
</div>


<!-- <iframe id="printPdf" name="printPdf"></iframe> -->
<!-- <div class="modal fade" id="print-billl" tabindex="-1" role="dialog" aria-labelledby="guest-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border">
                <div class="modal-title" id="">
                    <h5></h5>
                    {{-- <div style="font-size:90%">
                        <div class="d-inline-block mr-3">
                            <span class="">Table : </span><span id="table-no" data-table-no=""></span>
                        </div>
                        <div class="d-inline-block d-none">
                            <span class="">OS : </span><span id="os-no" data-os-no=""></span>
                        </div>
                        <div>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-title" id=""></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                    <div id="iframeContainer">

                    </div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" id="cancel">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="save">Save changes</button> --}}
            </div>
        </div>
    </div>
</div> -->
