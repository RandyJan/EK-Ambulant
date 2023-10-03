@extends('layouts.master')

@section('title','Product')

@section('content')
{{-- <div class="container"> --}}
    <div class="row" id="container">
        <div class="col-md-12">
                <div class="widget-profile-1 card">
                    <div class="profile border bottom">
                        <img id="product-image" class="mt-2 img-fluid d-block mx-auto rounded-circle" src="" alt="" style="width:175px; height:175px; object-fit:cover">
                        <h4 class="mrg-top-20 no-mrg-btm text-semibold" id="product_name">...</h4>
                        <p id="product_price" class="mb-0">0.00</p>
                    </div>
                    <div class="pdd-horizon-20 pdd-vertical-20">

                        <div class="row">
                            <div class="col-md-6">
                                    <div class="mrg-top-1 text-center">
                                        <div class="input-group">
                                                <input id="m-product-qty" type="number" class="form-control text-center" placeholder="Qty" value="" min="1">
                                                <div class="input-group-append" id="button-addon4">
                                                    <button class="btn btn-danger" type="button" id="btn-m-minus"><i class="ti-minus"></i></button>
                                                    <button class="btn btn-success" type="button" id="btn-m-plus"><i class="ti-plus"></i></button>
                                                </div>

                                            </div>
                                    </div>
                                    <ul class="list tick bullet-primary p-2 nmc accordion border-less" role="tablist" aria-multiselectable="true">

                                    </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox border bottom">
                                    <input id="is_takeout" type="checkbox" >
                                    <label for="is_takeout">Takeout</label>
                                </div>



                                <div class="components-container">

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
                                <button class="btn btn-info add-to-order" onclick="addtoOrder()">Submit</button>
                            </li>

                        </ul>
                    </div>
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

@section('css')
<link rel="stylesheet" href="/css/plugins/jquery-confirm.min.css" />
@endsection
@section('js')
<script src="/js/plugins/jquery-confirm.min.js"></script>
<script>
// "use strict"
$(document).ready(function(){

    getProduct();


});

function showGuestByTableId(){
    getWithHeader('/table/'+getStorage('selected_table_id')+'/guests',{},function(response){
    if(response.success == false){
            return;
        }

        // forloop the guest counts for selection
        var container = $('#guest-container');
        container.empty();
        var guest_count = response.data.guests;

        for(var y=0; y < guest_count; y++){
            var  guest  = y+1;

            container.append(



                '<div class="col-md-2 col-sm-3 col-3 custom-col-pad" >'+
                    '<div class="card">'+
                        '<span class="badge badge-pill badge-success number d-sm-block d-none">'+ guest +'</span>'+
                        '<img class="card-img-top img-fluid avatar d-none d-sm-block"  src="/assets/images/avatar.png" alt="guest image">'+
                            '<div class="card-body p-0">'+
                                '<div class="bg-success rounded d-block d-sm-none py-2 px-2">'+
                                    '<h2 class="m-0 text-center text-white">'+guest+'</h2>'+
                                '</div>'+
                            '</div>'+
                    '</div>'+
                '</div>'
            );
        }
        // end
        selectGuest();
    });
}

function selectGuest(){
    $(".img-fluid.avatar").on('click', function(){
        let self = $(this);

        setStorage('selected_guest_no', self.data('guest-no'));
        $('#modal-lg').modal('hide');
        return;
    });

}

// checker for components
var has_components = [false, false];

function getProduct(){

    let data = {
        product_id  : '{{ $product_id }}',
        outlet_id   : '{{ $outlet_id }}'
    };

    post('/product', data, function(response){
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }


        displayProduct(response.result,response.base_url);
        // getComponentsOfProduct();
        getComponentsNonModifiableOfProduct();
    });
}

function displayProduct(data, base_url){

    $('#product_name').text(data.description);

    $('#product_price').text('₱'+numberWithCommas(data.price));
    $('#product-image').attr('src', base_url + data.img_path);

    var po = JSON.parse( getStorage('product_order') );
    po = {
        product_id          : parseInt(data.product_id),
        name                : data.description,
        price               : data.price,
        qty                 : 1,
        main_product_id     : parseInt(data.product_id),
        // main_product_component_id   : null,
        // main_product_component_qty  : null,
        total               : (1 * data.price),
        instruction         : "",
        is_take_out         : false,
        part_number         : data.part_number,
        others              :[],
        guest_no            : 0,
        guest_type          : 1,
        discount            : 0,
        total_without_vat   : 0,
        vat                 : 0,
        table_no            : null,
        kitchen_loc         : data.kitchen_loc
    };

    setStorage('product_order', JSON.stringify(po));

    logicDisplay();



    //discount();
}

$('#instruction').on('change', function(){

    var _this = $(this);

    var po = JSON.parse( getStorage('product_order') );
    po.instruction = _this.val();

    setStorage('product_order', JSON.stringify(po));
});

$('#is_takeout').change('change', function(){
    var _this = $(this);
    var po = JSON.parse( getStorage('product_order') );
    po.is_take_out = _this.is(':checked');
    setStorage('product_order', JSON.stringify(po));
});

$('#btn-m-minus').on('click', function(){
    var po = JSON.parse( getStorage('product_order') );
    var pq =   $('#m-product-qty');
    po.qty = pq.val();
    if(po.qty > 1){
        po.qty--;
        po.total = po.qty * po.price;
        // deduct sub component first
        $.each(po.others, function(k,v){
            var qty_to_be_deduct = 1 * v.main_product_component_qty;
            if( (v.others).length > 0 ){
                for(var i = 0; i < (v.others).length; i++){
                    if(qty_to_be_deduct > 0){ // to check if there is qty to be deduct
                        if( v.others[i].qty > 0){

                            if( qty_to_be_deduct <= v.others[i].qty){
                                v.others[i].qty = v.others[i].qty - qty_to_be_deduct;
                                qty_to_be_deduct = 0;
                            }

                            if( v.others[i].qty == 0){ // should be removed if zero
                                var _id = '#'+po.product_id+'-'+v.product_id+'-categories-'+v.others[i].product_id+'-qty';
                                $(_id).val(0);
                                v.others.splice(i, 1);
                            }
                        }
                    }
                }
            }

            // deduct component
            if(qty_to_be_deduct >= 0){
                v.qty = v.qty - qty_to_be_deduct;
            }

        });
    }
    setStorage('product_order', JSON.stringify(po));
    logicDisplay();

});

$('#btn-m-plus').on('click', function(){
    var po = JSON.parse( getStorage('product_order') );
    var pq =   $('#m-product-qty');
    po.qty = pq.val();
    po.qty++;
    po.total = po.qty * po.price;
    $.each(po.others, function(k,v){
        v.qty += v.main_product_component_qty * 1;
    });
    setStorage('product_order', JSON.stringify(po));
    logicDisplay();

    // discount();
});
$("#m-product-qty").on("input", function () {

    var po = JSON.parse( getStorage('product_order') );
    var pq =   $('#m-product-qty');
    po.qty = pq.val();
    po.total = po.qty * po.price;
    $.each(po.others, function(k,v){
       v.qty = po.qty;
       v.qty *= v.main_product_component_qty *1;

    });

    setStorage('product_order', JSON.stringify(po));
    logicDisplay();

});

function componentChecker(){
     // if components contains true then hide the general instructions or the instruction for the whole meal
    if(has_components.includes(true)){

         $('#instruction').closest('.accordion').addClass('d-none');
    }else{

        $('#instruction').closest('.accordion').removeClass('d-none');

    }
}

function getComponentsOfProduct(){
    // let outlet = JSON.parse(getStorage('outlet'));
    let data = {
        product_id  : '{{ $product_id }}',
        outlet_id   : '{{ $outlet_id }}',
        group_by    : 'mc'
    };
    post('/product/components', data, function(response){
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }



        componentsDisplayer(response.result.data);

    });
}

function getComponentsNonModifiableOfProduct(){

    let data = {
        product_id  : '{{ $product_id }}',
        outlet_id   : '{{ $outlet_id }}',
        group_by    : 'nmc'
    };
    post('/product/components', data, function(response){
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }



        var container = $('.nmc');

        container.empty();
        $.each(response.result.data, function(k,v){
            container.append(


                '<li>'+
                    '<div class="panel panel-default">'+
                            '<div class="panel-heading" role="tab">'+
                                '<div class="panel-title">'+
                                    '<a class="collapsed pt-0 pb-2 px-0" >'+
                                        '<span class="text-info" id="nmc-'+v.parent_id+'-'+v.product_id+'">'+parseInt(v.quantity) +'</span>'+
                                        '<span> - </span>'+
                                        '<span class="text-muted"> ' +v.description+'</span>'+
                                    '</a>'+
                                '</div>'+
                            '</div>'+

                    '</div> '+
                '</li>'
            );
        });

        setStorage('none-modifiable-item', JSON.stringify(response.result.data));

    });
}

function componentsDisplayer(data){
    var cc = $('.components-container');
    cc.empty();

    var po = JSON.parse( getStorage('product_order') );

    $.each(data, function(k,v){

        v.quantity = parseInt(v.quantity, 10);
        po.others.push({
            product_id : parseInt(v.product_id),
            name : v.description,
            price : 0,
            qty : v.quantity,
            main_product_id : parseInt(po.product_id),
            main_product_component_id : parseInt(v.product_id),
            main_product_component_qty : v.quantity,
            total : (v.quantity * 0),
            part_number : v.product_partno,
            no_amount : {{ config('ambulant.no_price_diff') }} == 0 ? null : v.no_amount,

            others: []
        });

        var _id = po.product_id+'-'+v.product_id;
        cc.append(
            '<div class="mrg-top-0">'+
                '<div id="accordion-cc-'+k+'" class="accordion border-less" role="tablist" aria-multiselectable="true">'+
                    '<div class="panel panel-default">'+
                        '<div class="panel-heading" role="tab">'+
                            '<h5 class="panel-title">'+
                                '<a class="collapsed" >'+
                                    '<span>'+v.description+' | <i class="text-success" id="'+_id+'">'+v.quantity+'</i></span>'+
                                    '<i class="icon ti-arrow-circle-down" data-toggle="collapse" data-parent="#accordion-cc-'+k+'" href="#collapse-cc-'+k+'" aria-expanded="false"></i> '+
                                '</a>'+
                            '</h5>'+
                        '</div>'+
                        '<div id="collapse-cc-'+k+'" class="panel-collapse collapse" style="">'+
                            '<div class="panel-body" id="'+_id+'-categories'+'"> '+

                            '</div>'+
                        '</div>'+
                    '</div> '+
                '</div>'+
            '</div>'
        );

        getComponentCategories(v.product_id, _id+'-categories', (v.no_amount != null ? v.no_amount : null) );
    });
    setStorage('product_order', JSON.stringify(po));
}

function getComponentCategories(product_id,container, setItemsToZeroAmount){
    let data = {
        product_id  : product_id,
        outlet_id   : '{{ $outlet_id }}'
    };
    post('/product/component/categories', data, function(response){
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }


        componentCategoriesDisplayer(response.result.product, response.result.categories.data, container, setItemsToZeroAmount);
    });
}

function componentCategoriesDisplayer(product,data,container, setItemsToZeroAmount){
    var c = $('#'+container);

    c.empty();



    $.each(data, function(k,v){


        if(v.price <= product.price){
            v.price = 0;
        }else{
            v.price = v.price - product.price;
        }

        if(setItemsToZeroAmount == 1){
            v.price = 0;
        }

        var _id = container+'-'+v.product_id;
        c.append(


            '<div class="row justify-content-between border bottom my-2">'+
                '<div class="">'+

                    '<span class="font-size-14 text-dark ">'+v.short_code+' (₱ '+ numberWithCommas(v.price)+')</span>'+
                '</div>'+

                '<div class="">'+
                    '<div class="input-group input-group-sm ">'+
                        '<input type="text" class="form-control" id="'+_id+'-qty" placeholder="" aria-label="" aria-describedby="button-addon4" value="0" disabled>'+
                        '<div class="input-group-append" id="button-addon4">'+
                            '<button '+
                                'id="'+_id+'-minus"'+
                                'data-main_product_component_id="'+product.product_id+'" '+
                                'data-main_product_id="{{ $product_id }}" '+
                                'data-name="'+v.short_code+'" '+
                                'data-price="'+v.price+'" '+
                                'data-product_id="'+v.product_id+'" '+
                                'class="btn btn-danger btn-inverse" type="button" '+
                            '>'+
                                '<i class="ti-minus"></i>'+
                            '</button>'+
                            '<button '+
                                'id="'+_id+'-plus" '+
                                'data-main_product_component_id="'+product.product_id+'" '+
                                'data-main_product_id="{{ $product_id }}" '+
                                'data-name="'+v.short_code+'" '+
                                'data-price="'+v.price+'" '+
                                'data-product_id="'+v.product_id+'" '+
                                'class="btn btn-success btn-inverse" type="button"'+
                            '>'+
                                '<i class="fa fa-plus"></i>'+
                            '</button>'+
                        '</div>'+
                    '</div>'+
                '</div>'+

            '</div>'

        );

        btnComponentCategoryMinus(_id+'-minus');
        btnComponentCategoryPlus(_id+'-plus');
        });

}


function btnComponentCategoryMinus(id){
    $('#'+id).on('click', function(){
        var data = {
            main_product_component_id : $(this).data('main_product_component_id'),
            main_product_id : $(this).data('main_product_id'),
            name : $(this).data('name'),
            price : $(this).data('price'),
            product_id : $(this).data('product_id')
        };

        // initialize product order
        var po = JSON.parse( getStorage('product_order') );
        $.each(po.others, function(k,v){
            if(data.main_product_component_id == v.main_product_component_id){
                var _index_to_remove = -1;
                $.each(v.others, function(kk,vv){
                    if(data.product_id == vv.product_id){
                        if(vv.qty > 0){
                            v.qty++;
                            vv.qty--;
                            vv.total = vv.price * vv.qty;
                            if(vv.qty == 0){
                                var _id = '#'+po.product_id+'-'+v.product_id+'-categories-'+vv.product_id+'-qty';
                                // $(_id).text(0);

                                $(_id).val(0);


                                _index_to_remove = kk;
                            }
                        }
                    }
                });

                // to remove zero quantity of sub components
                if (_index_to_remove > -1) {
                    v.others.splice(_index_to_remove, 1);
                }
            }
        });
        //
        setStorage('product_order', JSON.stringify(po));
        logicDisplay();
    });
}

function btnComponentCategoryPlus(id){
    $('#'+id).on('click', function(){

        var data = {
            main_product_component_id : $(this).data('main_product_component_id'),
            main_product_id : $(this).data('main_product_id'),
            name : $(this).data('name'),
            price : $(this).data('price'),
            product_id : $(this).data('product_id')
        };

        // initialize product order
        var po = JSON.parse( getStorage('product_order') );

        // check if the selected sub component category is exist in sub component
        $.each(po.others, function(k,v){

            if(data.main_product_component_id == v.main_product_component_id){

                if(v.qty > 0){

                    // adding to sub component if not exist
                    var if_exist = false;
                    $.each(v.others, function(kk,vv){
                        if(data.product_id == vv.product_id){
                            if_exist = true;
                            vv.qty++;
                            vv.total = vv.price * vv.qty;
                            v.qty--;

                        }
                    });

                    if(if_exist == false){
                        v.others.push({
                            product_id : parseInt(data.product_id),
                            name : data.name,
                            price : data.price,
                            qty : 1,
                            main_product_id : parseInt(po.product_id),
                            main_product_component_qty : v.quantity,
                            main_product_component_id : parseInt(v.product_id),
                            total : (data.price * 1),
                            part_number : v.part_number,
                            instructions: ''
                        });
                        v.qty -= 1;
                    }

                }else{
                    showWarning('','No Available Quantity', function(){
                    });
                }
            }
        });

        //
        setStorage('product_order', JSON.stringify(po));

        logicDisplay();

    });
}


function logicDisplay(){
    var grand_total = 0;
    var po = JSON.parse( getStorage('product_order') );
    var nmc = JSON.parse( getStorage('none-modifiable-item') );
    var quantity = $('#m-product-qty');
    var qty=quantity.val();


        /**
         * MAIN PRODUCT SECTION
         */
        grand_total += po.total;
        $('#m-product-qty').val(po.qty);


        /**
         * COMPONENTS SECTION
         */


            $.each(po.others, function(k,v){
                $('#'+po.product_id+'-'+v.product_id).text(v.qty);

            });


            $.each(nmc, function(k,v){
                $('#nmc-'+v.parent_id+'-'+v.product_id).text(parseInt(v.quantity * po.qty));

            });



        /**
         * SUB COMPONENTS SECTION
         */
        $.each(po.others, function(k,v){
            $.each(v.others, function(kk,vv){
                var _id= '#'+po.product_id+'-'+v.product_id+'-categories-'+vv.product_id+'-qty';

                $(_id).val(vv.qty);

                grand_total += vv.total;
            });
        });

    // cmb-guests
    var cmbGuest = $('#cmb-guests');
    var guests = JSON.parse( getStorage('guests') );
    var guest_type = 'Regular';
    $.each(guests, function(k,v){
        if( cmbGuest.val() == v.guest_no){
            guest_type = v.guest_type;
        }
    });

    if( guest_type == 'Regular' ){
        po.guest_type = 1;
    }

    if( guest_type == 'Senior' ){
        po.guest_type = 2;
    }

    if( guest_type == 'PWD' ){
        po.guest_type = 3;
    }

    setStorage('product_order', JSON.stringify(po));

    $('#grand-total').text('TOTAL : ' + numberWithCommas(grand_total));
}

// show Modal for senior citizen button
$(document).ready(function(){
    $(".show-modal1").click(function(){
        $("#seniormodal").modal('show');

    });

});

//show modal for pwd
$(document).ready(function(){
    $(".show-modal2").click(function(){
        $("#pwdmodal").modal('show');

    });

});

$('input[type=radio][name=guest-type]').change(function() {
    var po = JSON.parse( getStorage('product_order') );
    po.guest_type = parseInt(this.value);
    setStorage('product_order', JSON.stringify(po));


});



function discount(){
    var new_price = 0;
    var po = JSON.parse( getStorage('product_order') );

    po.total_without_vat = (po.total/1.12);
    po.vat = po.total_without_vat * .12 ;

    if (po.guest_type == 2 || po.guest_type == 3 ){
        po.discount= po.total_without_vat * .20;

        new_price = po.total_without_vat - po.discount;
    }else{
        po.discount = 0;
        new_price = po.total - po.discount;
    }

    setStorage('product_order', JSON.stringify(po));


    $('#grand-total').text('TOTAL : ' + numberWithCommas(new_price));
}

function tablesCmb(){
    $('#cmb-tables').on('change', function(){
        var self = $(this);

        getGuests(self.val());
    });
}

$('.btn.btn-info.add-to-order').on('click', function(){


    var po = JSON.parse( getStorage('product_order') );
    var nmc = JSON.parse( getStorage('none-modifiable-item') );
    var modal = $('#modal-order-review');
    var totalAmount = po.total;

    modal.find('#osd_container').empty();
    modal.find('#total_amount').text( 'Total: ₱'+ numberWithCommas(totalAmount));

    modal.find('#osd_container').append(`
        <tr>
            <td>${ po.name }</td>
            <td class="text-right">${ po.qty }</td>
            <td class="text-right">₱${ numberWithCommas(po.price) }</td>
            <td class="text-right">₱${ numberWithCommas(po.price*po.qty)}</td>
        </tr>
    `);


    $.each(po.others, function(k,v){

        if(v.qty >0){

            modal.find('#osd_container').append(`
                <tr aria-controls="group-of-rows-3">
                    <td>&emsp;${ v.name }</td>
                    <td class="text-right">${ v.qty }</td>
                    <td class="text-right">₱${ numberWithCommas(v.price) }</td>
                    <td class="text-right">₱${numberWithCommas( v.price *v.qty) }</td>
                </tr>
            `);
        }
        $.each(v.others, function(kk,vv){
            modal.find('#osd_container').append(`
                <tr aria-controls="group-of-rows-3">
                    <td>&emsp;${ vv.name }</td>
                    <td class="text-right">${ vv.qty }</td>
                    <td class="text-right">₱${ numberWithCommas(vv.price)}</td>
                    <td class="text-right">₱${numberWithCommas(vv.price * vv.qty) }</td>
                </tr>
            `);

        });
    });


    $.each(nmc, function(k,v){

        modal.find('#osd_container').append(`
            <tr aria-controls="row">
                <td>&emsp;${ v.description }</td>
                <td class="text-right">${ parseInt(""+v.quantity *po.qty) }</td>
                <td class="text-right">₱${ numberWithCommas(0) }</td>
                <td class="text-right">₱${ numberWithCommas(0) }</td>
            </tr>
        `);
    });

    $.each(po.others, function(k,v){
            $.each(v.others, function(kk,vv){
                var _id= '#'+po.product_id+'-'+v.product_id+'-categories-'+vv.product_id+'-qty';
                $(_id).val(vv.qty);

                totalAmount += vv.total;
            });
            modal.find('#total_amount').text( 'Total: ₱'+ numberWithCommas(totalAmount));
    });
function addtoOrder(){
    // document.getElementById("confirm");

        // modal.find('#confirm').attr('disabled',true);
        event.preventDefault();

        var po = JSON.parse( getStorage('product_order') );
        var nmc = JSON.parse( getStorage('none-modifiable-item') );
        var quantity = $('#m-product-qty');

            if( quantity.val().trim() == 0){
                showWarning('','Quantity is invalid!', function(){
                });
                return;
            }

        po.qty=quantity.val();
        po.total = po.qty * po.price;
        setStorage('product_order', JSON.stringify(po));
        logicDisplay();
        po.none_modifiable_component = nmc
        po.table_no = '';
        po.guest_no = '';

            post('/orderslip',po, function(response){

                if(response.success == false){
                    showWarning('',response.message, function(){
                    });
                    return;
                }
                   modal.modal('toggle');
                   Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Successfully added to cart',
                    showConfirmButton: false,
                    timer: 1000
                    }).then(function() {
                        if(getStorage('selected-category') == null){
                            redirectTo("/category");
                        }else{
                            redirectTo("/category/" + getStorage('selected-category') + "/products");
                        }
                    });
            });

    }



});

function getTables(){
    get('/orderslip/tables', {}, function(res){

        if(res.success == false){
            return;
        }

        var container = $('#cmb-tables');
        container.empty();

        if(res.data.length == 0){
            container.append(
                '<option value=""> NO TABLE RECORD </option>'
            );

            var container = $('#cmb-guests');
            container.empty();
            container.append(
                '<option value=""> NO TABLE RECORD </option>'
            );

            return;
        }

        $.each(res.data, function(k,v){

            container.append(
                '<option value="'+v.table_id+'">TABLE NO. '+ v.table_number +'</option>'


            );
        });

        //
        getGuests(res.data[0].table_id);

    });
}

function getGuests(table_id){
    post('/guestfile/'+ table_id, {}, function(res){

        if(res.success == false){
            return;
        }
        var container = $('#cmb-guests');
        container.empty();

        $.each(res.data, function(k,v){
            container.append(
                '<option value="'+v.guest_no +'">GUEST NO. '+v.guest_no +' ('+v.guest_type +') </option>'
            );
        });



        setStorage('guests', JSON.stringify( res.data ));
        logicDisplay();
        discount();
    });
}

$('#cmb-guests').on('change', function(){
    logicDisplay();
    discount();
});

function addtoOrder(){
    // document.getElementById("confirm");

        // modal.find('#confirm').attr('disabled',true);
        event.preventDefault();

        var po = JSON.parse( getStorage('product_order') );
        var nmc = JSON.parse( getStorage('none-modifiable-item') );
        var quantity = $('#m-product-qty');

            if( quantity.val().trim() == 0){
                showWarning('','Quantity is invalid!', function(){
                });
                return;
            }

        po.qty=quantity.val();
        po.total = po.qty * po.price;
        setStorage('product_order', JSON.stringify(po));
        logicDisplay();
        po.none_modifiable_component = nmc
        po.table_no = '';
        po.guest_no = '';

            post('/orderslip',po, function(response){

                if(response.success == false){
                    showWarning('',response.message, function(){
                    });
                    return;
                }
                //    modal.modal('toggle');
                   Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Successfully added to cart',
                    showConfirmButton: false,
                    timer: 1000
                    }).then(function() {
                        if(getStorage('selected-category') == null){
                            redirectTo("/category");
                        }else{
                            redirectTo("/category/" + getStorage('selected-category') + "/products");
                        }
                    });
            });

    }
</script>
@endsection
