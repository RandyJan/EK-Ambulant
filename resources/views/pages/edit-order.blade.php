@extends('layouts.master')
@section('title','Edit Order')

@section('content')
<div class="row" id="container">
    <div class="col-md-12">
            <div class="widget-profile-1 card">
                <div class="profile border bottom">
                    <img id="product-image" class="mrg-top-30" src="" alt="" style="width:200px; height:200px;">
                    <h4 class="mrg-top-20 no-mrg-btm text-semibold" id="product_name">...</h4>
                    <p id="product_price">0.00</p>
                </div>
                <div class="pdd-horizon-20 pdd-vertical-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mrg-top-1 text-center">
                                <div class="input-group">
                                    <input id="m-product-qty" type="number" class="form-control" placeholder="Qty" value="1" min="1">
                                    <div class="input-group-append" id="button-addon4">
                                        <button class="btn btn-danger" type="button" id="btn-m-minus"><i class="ti-minus"></i></button>
                                        <button class="btn btn-success" type="button" id="btn-m-plus"><i class="ti-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <ul class="list tick bullet-primary p-3 nmc">
                                {{-- <li>Lorem ipsum dolor sit amet</li>
                                <li>Consectetur adipiscing elit</li>
                                <li>Integer molestie lorem at massa</li>
                                <li>Facilisis in pretium nisl aliquet</li>
                                <li>Nulla volutpat aliquam velit </li> --}}
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="checkbox border bottom">
                                <input id="is_takeout" type="checkbox" >
                                <label for="is_takeout">Is Takeout?</label>
                            </div>
                            <div class="components-container">
                                {{-- <div class="mrg-top-0">
                                    <div id="accordion-ask-2" class="accordion border-less" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-ask-2" href="#collapse-ask-2" aria-expanded="false">
                                                        <span>Product Component(1)</span>
                                                        <i class="icon ti-arrow-circle-down"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse-ask-2" class="panel-collapse collapse" style="">
                                                <div class="panel-body">
                                                    <div class="row border bottom">
                                                        <div class="col-md-8">
                                                            </span>
                                                            <span class="mrg-left-0 font-size-14 text-dark ">BABY BCK RIBS ML (₱ 0.00)</span>
                                                        </div>
                                                        <div class="col-md-4 text-right">
                                                            <p class="mrg-top-10">
                                                                <span>(0)</span>
                                                                <a href="#" class="btn btn-danger btn-inverse btn-xs no-mrg-btm mrg-left-10 border-radius-4">
                                                                    <i class="fa fa-minus"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-success btn-inverse btn-xs no-mrg-btm mrg-left-10 border-radius-4">
                                                                    <i class="fa fa-plus"></i>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer border top">
                    <ul class="list-unstyled list-inline text-right pdd-vertical-5">
                        <li class="list-inline-item" id="grand-total">
                            TOTAL : 0.00
                        </li>
                        <li class="list-inline-item">
                            <button class="btn btn-info add-to-order" data-toggle="modal" data-target="#modal-order-review">Submit</button>
                        </li>
                        <!-- <li class="list-inline-item">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-lg">Change guest no.</button>
                        </li> -->
                    </ul>
                </div>
            </div>
    </div>
</div>


<div class="modal fade" id="modal-order-review">
    <div class="modal-dialog modal-lg modal-dialog-scrollable " role="document">
        <div class="modal-content">
            <div class="modal-header border d-flex align-items-center">
                <div>
                    <h3 class="modal-title text-primary">Order Review</h3>
                </div>
                <a class="modal-close" href="#" data-dismiss="modal">
                    <i class="ti-close"></i>
                </a>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th width="12%" class="text-right">Qty</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody id="osd_container">

                                <tr aria-controls="group-of-rows-4">
                                    <td>Chicken Ala King</td>
                                    <td class="text-left">x1</td>
                                    <td>800</td>
                                    <td class="text-right">$100</td>
                                </tr>

                            </tbody>
                        </table>
                        <div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table>
                            <tbody>
                                <tr aria-controls="group-of-rows-2">
                                <td>
                                </td>
                                    <td id="total_amount" class="col-sm-12 text-right"  style="font-size:20px;padding-right:3px;" >
                                        &emsp;Total: 00.00
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </div>

            </div>
            <div class="modal-footer" style="padding:5px;">
                <button class="btn btn-primary" id="confirm" style="float: right;margin-top:1em;font-size:15px;">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="/js/pages/edit-order.js"></script>
@endsection
