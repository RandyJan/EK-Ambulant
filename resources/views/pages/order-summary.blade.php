@extends('layouts.master')

@section('title','Order Summary')
@section('css')
    <style>
    .card-primary-custom.inverse{
        background-color: #f6f6f6;
        border-color: #17a2b8;
        border-radius:6px;
        box-shadow: none;

    }
    .card-primary-custom.inverse:hover{
        background-color: #e1e1fa;
        color: #625ee3;
    }
    .card-primary-custom .title{
        color: #625ee3;
    }


    </style>
@endsection
@section('content')

@include('alerts.alert')
<h4 class="card-title">Order Summary For the Day</h4>
<div class="alert alert-info row" role="alert">
    <div class="col-12 col-sm-12"><span><strong>Paid  - Total Amount: </strong></span><span> {{ $os_status['paid_total_amount'] }}</span></div>
    <div class="col-12 col-sm-6" ><span><strong>Paid  - Count: </strong></span><span>{{ $os_status['paid_count'] }}</span></div>
    <div class="col-12 col-sm-6"><span><strong>Unpaid - Count: </strong></span><span>{{ $os_status['unpaid_count'] }}</span></div>


</div>

<div class="row">
    @foreach($os as $key => $item)

        <div class="col-md-6">
            <div class="card view-info mb-1 card-primary-custom inverse view-os"
                data-branch-id="{{ $item->branch_id }}"
                data-os-id="{{ $item->orderslip_header_id }}"
                data-outlet-id="{{ $item->outlet_id }}"
                data-device-id="{{ $item->device_no }}">

                <div class="card-body d-flex justify-content-between py-2 align-items-center">
                    <div>
                        <h5 class="title mb-0"> <strong>OS# {{$item->osnumber}}</strong></h5>
                        <div>
                            <i class="ti-time mr-2"></i>
                            <span>{{ \Carbon\Carbon::parse($item->encoded_date)->format('Y-m-d g:i A') }}</span>
                        </div>
                        <div>
                            <i class="ti-money mr-2"></i>
                            <span>
                                @if ($item->total_amount)
                                    {{$item->total_amount}}
                                @else
                                    0.00
                                @endif
                            </span>
                        </div>

                    </div>
                    <div class="">
                        @if( $item->encoded_by == auth()->user()->username && $item->is_paid == 1)
                            <span class="tag tag-info "style="">Paid</span>
                        @else
                            <span class="tag invisible"></span>
                        @endif
                    </div>

                </div>
            </div>
            {{-- <button
                type="button"
                class="btn btn-inverse btn-primary btn-md-12 border-info " style="width:100%;"
                >
                @if( $item->encoded_by == auth()->user()->username && $item->is_paid == 1)
                <span class="tag tag-success"style="float:left;">Paid&nbsp;</span>
                @else
                <span class="tag" style="display:hidden"></span>
                @endif
                <span>OS # {{$item->osnumber}}</span>
            </button> --}}
        </div>

    @endforeach
</div>
<div class="d-flex justify-content-end mt-2">
    {{ $os->links() }}
</div>

<div class="modal fade" id="modal-view-os">
    <div class="modal-dialog modal-lg modal-dialog-scrollable " role="document">
        <div class="modal-content">
            <div class="modal-header border d-flex align-items-center">
                <div>
                    {{-- TODO: if paid turn the modal title to text-danger else text-primary --}}
                    <h3 class="modal-title text-primary" id="vo_os_number">#</h3>
                    <div class="">
                        <div>
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


@endsection

@section('js')
<script src="../bower_components/datatables/media/js/jquery.dataTables.js"></script>
<script>
    $('#os-slips').DataTable();
    $(document).ready(function(){
        $('.view-os').on('click', function(){
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
                    modal.find('#date_created').text( res.header.date_created );
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
