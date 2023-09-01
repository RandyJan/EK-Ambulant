@extends('layouts.master')

@section('title','Product')

@section('content')
{{-- <div class="container"> --}}
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
                        <!-- non modifiable -->
                            <div class="col-md-6">
                                    <div class="mrg-top-1 text-center">
                                        <div class="input-group">
                                                <input id="m-product-qty" type="text" class="form-control text-center" placeholder="Qty" value="" disabled>
                                                <div class="input-group-append" id="button-addon4" disabled>
                                                    <button class="btn btn-danger" type="button" id="btn-m-minus" disabled><i class="ti-minus"></i></button>
                                                    <button class="btn btn-success" type="button" id="btn-m-plus" disabled><i class="ti-plus"></i></button>
                                                </div>

                                            </div>
                                    </div>
                                    <ul class="list tick bullet-primary p-3 nmc accordion border-less" role="tablist" aria-multiselectable="true">
                                        {{-- <li>Lorem ipsum dolor sit amet</li>
                                        <li>Consectetur adipiscing elit</li>
                                        <li>Integer molestie lorem at massa</li>
                                        <li>Facilisis in pretium nisl aliquet</li>
                                        <li>Nulla volutpat aliquam velit </li> --}}
                                    </ul>
                            </div>
                            <!-- modifiable -->
                            <div class="col-md-6">
                                <div class="checkbox border bottom">
                                    <input id="is_takeout" type="checkbox" >
                                    <label for="is_takeout">Takeout</label>
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

                                <!-- <div class="mrg-top-0">
                                    <div id="accordion-ask-1" class="accordion border-less" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab">
                                                <h4 class="panel-title">
                                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-ask-1" href="#collapse-ask-1" aria-expanded="false">
                                                            <span>INSTRUCTIONS(Optional)</span>
                                                            <i class="icon ti-arrow-circle-down"></i>
                                                        </a>
                                                    </h4>
                                            </div>
                                            <div id="collapse-ask-1" class="panel-collapse collapse" style="">
                                                <div class="panel-body">
                                                        <textarea class="form-control" rows="3" id="instruction"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  -->

                            </div>
                        </div>
                    </div>
                    <div class="card-footer border top">
                        <ul class="list-unstyled list-inline text-right pdd-vertical-5">
                            <li class="list-inline-item" id="grand-total">
                                TOTAL : 0.00
                            </li>
                            <li class="list-inline-item">
                                <button class="btn btn-info add-to-order" {{--data-toggle="modal" data-target="#modal-order-review"--}}>Submit</button>
                            </li>
                            <!-- <li class="list-inline-item">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-lg">Change guest no.</button>
                            </li> -->
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
                    <div class="col-sm-12">
                        <table class="table table-sm  table-hover">
                            <tbody id="osd_container">
                                <tr aria-controls="group-of-rows-3">
                                    <td class="text-left">x1</td>
                                    <td>Chicken Ala King</td>
                                    <td class="text-right">$100</td>
                                </tr>
                                <tr aria-controls="group-of-rows-3">
                                    <td class="text-left">x1</td>
                                    <td>None Modifiable</td>
                                    <td class="text-right">$100</td>
                                </tr>
                                <tr aria-controls="group-of-rows-3">
                                    <td class="text-left">x1</td>
                                    <td>&emsp;Mashed Potato</td>
                                    <td class="text-right">$10</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table>
                            <tbody>
                                <tr aria-controls="group-of-rows-2">
                                <td>
                                </td>
                                    <td id="total_amount" class="col-sm-12 text-right" style="padding-right:4px;font-size:20px;" >
                                        &emsp;Total: 00.00
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </div>

            </div>
            <div class="modal-footer" style="padding:5px;">
                <button class="btn btn-primary" id="confirm" style="float: right;margin-top:1em;font-size:15px;" >Confirm</button>
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


function getProduct(){
    let storage = JSON.parse(getStorage('loc'));

    let data = {

            srn : storage.srn
    }

    post('/mealstub/main_item', data, function(response){
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }

            // console.log('he', response);
            displayProduct(response.result,response.base_url);
            getComponentsOfProduct();
            getComponentsNonModifiableOfProduct();
    });



}

function displayProduct(data, base_url){
    let storage = JSON.parse(getStorage('loc'));
    // console.log('displayProduct', storage.srn);
    $('#product_name').text(data.short_code);
    $('#product_price').text(data.price);
    // $('#product-image').attr('src', base_url + data.img_path);

    // var po = JSON.parse( getStorage('product_order') );
    // console.log(data.mealstub_product_id, typeof data.mealstub_product_id);
    po = {
        // product_id          : parseInt(data.product_id),
        product_id          : data.mealstub_product_id.toString().trim(),
        name                : data.short_code,
        price               : data.price,
        qty                 : 1,
        // main_product_id     : parseInt(data.product_id),
        main_product_component_id   : null,
        main_product_component_qty  : null,
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
        serial_number       : storage.srn.toString().trim(),
        reference_id        : storage.reference_id,
        table_no            : null,
        kitchen_loc         : 0
    };
    // console.log('displayProduct', po.serial_number, storage.reference_id);
    setStorage('product_order', JSON.stringify(po));
    logicDisplay();
}

function logicDisplay(){
    console.log('moew');
    var grand_total = 0;
    var po = JSON.parse( getStorage('product_order') );
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

            // console.log($('#'+po.product_id+'-'+v.product_id));


            });




        /**
         * SUB COMPONENTS SECTION
         */
        $.each(po.others, function(k,v){
            $.each(v.others, function(kk,vv){
                var _id= '#'+po.product_id+'-'+v.product_id+'-categories-'+vv.product_id+'-qty';
                $(_id).val(vv.qty);
                // $(_id).text(vv.qty);
                grand_total += vv.total;
            });
        });

    // // cmb-guests
    // var cmbGuest = $('#cmb-guests');
    // var guests = JSON.parse( getStorage('guests') );
    // var guest_type = 'Regular';
    // $.each(guests, function(k,v){
    //     if( cmbGuest.val() == v.guest_no){
    //         guest_type = v.guest_type;
    //     }
    // });

    // if( guest_type == 'Regular' ){
    //     po.guest_type = 1;
    // }

    // if( guest_type == 'Senior' ){
    //     po.guest_type = 2;
    // }

    // if( guest_type == 'PWD' ){
    //     po.guest_type = 3;
    // }

    setStorage('product_order', JSON.stringify(po));

    $('#grand-total').text('TOTAL : ' + numberWithCommas(grand_total));
}


function getComponentsNonModifiableOfProduct(){

    let storage = JSON.parse(getStorage('loc'));
    let data = {
            reference_id : storage.reference_id,
            group_by     : 'nmc'
    }

    post('/mealstub/components', data, function(response){
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }

        var container = $('.nmc');
        container.empty();
        // console.log('nmc', response);

        // $.each(response.result.data, function(k,v){
        $.each(response.result.data, function(k,v){
            container.append(
                // '<li> '+ v.description+' | ' + parseInt(v.quantity, 10) + '</li>'
                // '<li> '+ v.description+' </li>'

                '<li>'+
                    '<div class="panel panel-default">'+
                            '<div class="panel-heading" role="tab">'+
                                '<div class="panel-title pb-2 px-0">'+
                                    // '<a class="collapsed pt-0 pb-2 px-0" >'+
                                        // '<span class="text-muted">'+v.description+'</span>'+
                                        '<span class="text-muted">'+parseInt(v.qty, 10)+' - '+v.description+'</span>'+
                                        // '<i class="icon ti-arrow-circle-down" data-toggle="collapse" data-parent="#accordion-cc-'+k+'" href="#collapse-nmc-'+k+'" aria-expanded="false"></i> '+
                                    // '</a>'+
                                '</div>'+
                            '</div>'+
                            // '<div id="collapse-nmc-'+k+'" class="panel-collapse collapse" style="">'+
                            //     '<div class="panel-body px-0"> '+
                            //         '<textarea class="form-control " placeholder="Instructions"></textarea>'+
                            //     '</div>'+
                            // '</div>'+
                    '</div> '+
                '</li>'
            );
        });

        // setStorage('none-modifiable-item', JSON.stringify(response.result.data));
        setStorage('none-modifiable-item', JSON.stringify(response.result.data));

    });
}

function getComponentsOfProduct(){
    // let outlet = JSON.parse(getStorage('outlet'));
    let storage = JSON.parse(getStorage('loc'));
    let data = {
            reference_id : storage.reference_id,
            group_by     : 'mc'
    }
    post('/mealstub/components', data, function(response){

        // console.log('mc mealstub', response);
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }
        componentsDisplayer(response.result.data);

    });
}


function componentsDisplayer(data){
    var cc = $('.components-container');
    cc.empty();

    var po = JSON.parse( getStorage('product_order') );

    $.each(data, function(k,v){
        v.quantity = parseInt(v.quantity, 10);
        // console.log('components-displayer',v);
        po.others.push({
            // product_id : parseInt(v.product_id),
            product_id : parseInt(v.default_product_id),
            name : v.description,
            price : 0,
            // qty : v.quantity,
            qty : v.default_product_qty,
            // main_product_id : parseInt(po.product_id),
            main_product_id : parseInt(po.product_id),
            main_product_component_id : parseInt(v.product_id),
            // main_product_component_qty : v.quantity,
            main_product_component_qty : v.qty,
            total : (v.default_product_qty * 0),
            part_number : v.part_number,
            // instructions: '',
            others: [],
            postmix: v.postmix
        });

        // var _id = po.product_id+'-'+v.product_id;
        var _id = po.product_id+'-'+v.default_product_id;
        cc.append(
            '<div class="mrg-top-0">'+
                '<div id="accordion-cc-'+k+'" class="accordion border-less" role="tablist" aria-multiselectable="true">'+
                    '<div class="panel panel-default">'+
                        '<div class="panel-heading" role="tab">'+
                            '<h5 class="panel-title">'+
                                '<a class="collapsed" >'+
                                    '<span>'+v.description+' | <i class="text-success" id="'+_id+'">'+v.qty+'</i></span>'+
                                    '<i class="icon ti-arrow-circle-down" data-toggle="collapse" data-parent="#accordion-cc-'+k+'" href="#collapse-cc-'+k+'" aria-expanded="false"></i> '+
                                '</a>'+
                            '</h5>'+
                        '</div>'+
                        '<div id="collapse-cc-'+k+'" class="panel-collapse collapse" style="">'+
                            '<div class="panel-body" id="'+_id+'-categories'+'"> '+
                              '<span>container:'+_id+'-categories'+'<span>'+
                            '</div>'+
                        '</div>'+
                    '</div> '+
                '</div>'+
            '</div>'
        );
        // console.log('before', v.product_id, v.default_product_id, _id);
        // getComponentCategories(v.product_id, _id+'-categories');
        getComponentCategories(v.default_product_id, _id+'-categories');
    });
    setStorage('product_order', JSON.stringify(po));
}

function getComponentCategories(product_id,container){
    // console.log('getComponentCategories', 'container',container);
    let outlet = JSON.parse(getStorage('loc')).outlet;
    let data = {
        product_id  : product_id,
        outlet_id   : outlet
    };
    post('/product/component/categories', data, function(response){
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }
        // console.log('getComponentsCategories', product_id ,response, container);

        componentCategoriesDisplayer(response.result.product,response.result.categories.data,container);
    });
}

function componentCategoriesDisplayer(product,data,container){
    var c = $('#'+container);
    // console.log(c);
    c.empty();



    $.each(data, function(k,v){

        if(v.price <= product.price){
            v.price = 0;
        }else{
            v.price = v.price - product.price;
        }

        var _id = container+'-'+v.product_id;
        // console.log('componentCategoriesDisplayer', v);
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
                                'data-main_product_id="{{-- {{ $product_id }} --}}" '+
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
                                'data-main_product_id="{{--  {{ $product_id }} --}}" '+
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

        // console.log('btnComponentCategoryPlus', data);

        // initialize product order
        var po = JSON.parse( getStorage('product_order') );

        // check if the selected sub component category is exist in sub component
        $.each(po.others, function(k,v){
            // console.log(

            //     data.main_product_component_id,
            //     v.main_product_component_id
            // );
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
        // inputQuantityDisplay();
    });
}

$('.btn.btn-info.add-to-order').on('click', function(){
    //$(this).attr('disabled','disabled');

    var po = JSON.parse( getStorage('product_order') );
    var nmc = JSON.parse( getStorage('none-modifiable-item') );
    var modal = $('#modal-order-review');
    var totalAmount = po.total;
    // console.log(nmc);
    // console.log(po);
    modal.find('#osd_container').empty();
    modal.find('#total_amount').text( 'Total: ₱'+ totalAmount);

    modal.find('#osd_container').append(`
        <tr aria-controls="row">
            <td>${ po.qty }x<td>
            <td>${ po.name }</td>
            <td class="text-right">₱${ po.price*po.qty}<td>
        </tr>
    `);


    $.each(po.others, function(k,v){
        if(v.qty >0){
            modal.find('#osd_container').append(`
            <tr aria-controls="row">
                <td>${ v.qty }x<td>
                <td>&emsp;${ v.name }</td>
                <td class="text-right">₱${ v.price *v.qty }</td>
            </tr>

        `);
        }
            $.each(v.others, function(kk,vv){
            modal.find('#osd_container').append(`
            <tr aria-controls="row">
                <td>${ vv.qty }x<td>
                <td>&emsp;${ vv.name }</td>
                <td class="text-right">₱${ vv.price * vv.qty }</td>
            </tr>
        `);
        });
    });

    // console.log(nmc);
    $.each(nmc, function(k,v){
        // console.log('--');
        // console.log(v);
        modal.find('#osd_container').append(`
            <tr aria-controls="row">
                <td>${ parseInt(""+v.qty) }x<td>
                <td>&emsp;${ v.description }</td>
                <td class="text-right">₱0</td>
            </tr>
        `);
    });

    $.each(po.others, function(k,v){
            $.each(v.others, function(kk,vv){
                var _id= '#'+po.product_id+'-'+v.product_id+'-categories-'+vv.product_id+'-qty';
                $(_id).val(vv.qty);
                // $(_id).text(vv.qty);
                totalAmount += vv.total;
            });
            modal.find('#total_amount').text( 'Total: ₱'+ totalAmount);
    });

    modal.find('#confirm').on('click',function(){

        //  initialize product order
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
               // $.each(po.others, function(k,v){
               // v.qty += v.main_product_component_qty * 1;
               // });
               setStorage('product_order', JSON.stringify(po));
               logicDisplay();
               po.none_modifiable_component = nmc
               po.table_no = '';
               po.guest_no = '';

               let mealstub_details = JSON.parse( getStorage('loc') );
               po.serial_number = mealstub_details.srn;
               po.reference = mealstub_details.toString().trim();

            //   console.log('confirm modal po ', po);
               post('/mealstub/claim', po, function(response){
                    console.log(response);
                   // setStorage('selected_guest_no','');
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

                   // redirectTo('/category');

                });


    });

    modal.modal('toggle');

});

</script>
@endsection
