$(document).ready(function () {
    getOrders();
});

function getOrders() {
    let eos = JSON.parse(getStorage('edit-order-slip'));
    let data = {
        header_id: eos.header_id,
        branch_id: eos.branch_id,
        outlet_id: eos.outlet_id,
        device_id: eos.device_id,
        main_product_id: eos.main_product_id,
        sequence: eos.sequence,
    };

    // console.log(typeof(data.main_product_id);v

    getWithHeader('/get-single-order', data, function (response) {
        if (response.success == false) {
            showError('', response.message, function () {
                if(response.status == 404){
                    if(getStorage('edit-order-slip') != null){
                        localStorage.removeItem('edit-order-slip');
                        localStorage.removeItem('nmc');

                    }
                    redirectTo('/');
                }
            });
            return;
        }
        // console.log('getOrders', response)
        // console.log('order', response.result.data);


        getProduct(response.result.data);
    });

}

function getProduct(order) {
    //parse
    let outlet = JSON.parse(getStorage('outlet'));
    let eos = JSON.parse(getStorage('edit-order-slip'));
    let data = {
        product_id: eos.main_product_id,
        outlet_id: eos.outlet_id,
    };

    post('/product', data, function (response) {
        if (response.success == false) {
            showError('', response.message, function () {});
            return;
        }

        // console.log(response);

        displayProduct(response.result, order, response.base_url);
        getComponentsOfProduct(order);
        getComponentsNonModifiableOfProduct();
    });

}

function displayProduct(data, order, base_url) {

    var current_qty = 1;
    var is_take_out = false;


    $.each(order, function (k, v) {
        // console.log(v);
        if (v.product_id == v.main_product_id) {
            //console.log(k,v);
            current_qty = v.qty;

            // order type
            if (v.order_type == 1) {
                is_take_out = false;
            } else if (v.order_type == 2) {
                is_take_out = true;
                $('#is_takeout').attr('checked', 'checked');
            }

        }
    });

    /**
     * Setting up the value's
     */

    $('#product_name').text(data.description);
    $('#product_price').text('₱' + numberWithCommas(data.price));
    $('#product-image').attr('src', base_url + data.img_path);

    var eos = JSON.parse(getStorage('edit-order-slip'));

    eos.data = {
        product_id: parseInt(data.product_id),
        name: data.short_code,
        price: data.price,
        qty: current_qty,
        main_product_id: parseInt(data.product_id),
        main_product_component_id: null,
        main_product_component_qty: null,
        total: (current_qty * data.price),
        instruction: '',
        is_take_out: is_take_out,
        part_number: data.part_number,
        others: []
    };

    setStorage('edit-order-slip', JSON.stringify(eos));
    logicDisplay();
}


function getComponentsOfProduct(order) {
    // console.log('getcomponents of product order', order);

    var eos = JSON.parse(getStorage('edit-order-slip'));
    let data = {
        product_id: eos.data.main_product_id,
        outlet_id: eos.outlet_id,
        group_by: 'mc'
    };
    post('/product/components', data, function (response) {
        if (response.success == false) {
            showError('', response.message, function () {});
            return;
        }
        // console.log('mc', response.result.data);
        componentsDisplayer(response.result.data, order);
    });
}


function getComponentsNonModifiableOfProduct() {
    var eos = JSON.parse(getStorage('edit-order-slip'));
    let data = {
        product_id: eos.data.main_product_id,
        outlet_id: eos.outlet_id,
        group_by: 'nmc'
    };
    post('/product/components', data, function (response) {
        if (response.success == false) {
            showError('', response.message, function () {});
            return;
        }
        // console.log(response.result.data);
        var container = $('.nmc');
        container.empty();
        $.each(response.result.data, function (k, v) {
            container.append(
                // '<li> '+ v.description+' | ' + parseInt(v.quantity, 10) + '</li>'
                '<li>' +
                '<div class="panel panel-default">' +
                '<div class="panel-heading" role="tab">' +
                '<div class="panel-title">' +
                '<a class="collapsed pt-0 pb-2 px-0" >' +
                '<span class="text-info" id="nmc-' + v.parent_id + '-' + v.product_id + '">' + parseInt(v.quantity) + '</span>' +
                '<span> - </span>' +
                '<span class="text-muted"> ' + v.description + '</span>' +
                '</a>' +
                '</div>' +
                '</div>' +
                '</div> ' +
                '</li>'
            );
        });

        setStorage('nmc', JSON.stringify(response.result.data));

    });
}



function componentsDisplayer(data, order) {
    // console.log('componentsDisplayer', order);
    var cc = $('.components-container');
    cc.empty();

    var eos = JSON.parse(getStorage('edit-order-slip'));
    $.each(data, function (k, v) {
        // console.log('componentsDisplayer', v);
        v.quantity = parseInt(v.quantity, 10);

        //
        var n_qty = 0;
        let ctr_qty = 0;
        let temp_product_id = null;

        $.each(order, function (kk, vv) {
            if (
                v.product_id == vv.main_product_comp_id &&
                vv.product_id == vv.main_product_comp_id
            ) {
                // n_qty = v.quantity * vv.qty;
                if (v.product_id != temp_product_id) {
                    temp_product_id = v.product_id;
                    ctr_qty = vv.qty;
                } else {
                    ctr_qty += vv.qty;
                }
                // n_qty = v.quantity * ctr_qty;
                n_qty = ctr_qty;
            }
        });
        //


        eos.data.others.push({
            product_id: parseInt(v.product_id),
            name: v.description,
            price: 0,
            qty: n_qty,
            main_product_id: parseInt(eos.data.product_id),
            main_product_component_id: parseInt(v.product_id),
            main_product_component_qty: v.quantity,
            total: (n_qty * 0),
            part_number: v.product_partno,
            no_amount : "{!! config('ambulant.no_price_diff') !!}" == 0 ? null : v.no_amount,
            others: []
        });

        var _id = eos.data.product_id + '-' + v.product_id;
        cc.append(
            '<div class="mrg-top-0">' +
            '<div id="accordion-cc-' + k + '" class="accordion border-less" role="tablist" aria-multiselectable="true">' +
            '<div class="panel panel-default">' +
            '<div class="panel-heading" role="tab">' +
            '<h4 class="panel-title">' +
            '<a class="collapsed" data-toggle="collapse" data-parent="#accordion-cc-' + k + '" href="#collapse-cc-' + k + '" aria-expanded="false">' +
            '<span>' + v.description + ' | <i class="text-success" id="' + _id + '">' + v.quantity + '</i></span>' +
            '<i class="icon ti-arrow-circle-down"></i> ' +
            '</a>' +
            '</h4>' +
            '</div>' +
            '<div id="collapse-cc-' + k + '" class="panel-collapse collapse" style="">' +
            '<div class="panel-body" id="' + _id + '-categories' + '"> ' +
            '</div>' +
            '</div>' +
            '</div> ' +
            '</div>' +
            '</div>'
        );

        logicDisplay();
        getComponentCategories(v.product_id, _id + '-categories', order, (v.no_amount != null ? v.no_amount : null));
    });
    setStorage('edit-order-slip', JSON.stringify(eos));
}

function getComponentCategories(product_id, container, order, setItemsToZeroAmount) {
    let outlet = JSON.parse(getStorage('edit-order-slip')).outlet_id;
    let data = {
        product_id: product_id,
        outlet_id: outlet
    };
    post('/product/component/categories', data, function (response) {
        if (response.success == false) {
            showError('', response.message, function () {});
            return;
        }

        componentCategoriesDisplayer(
            response.result.product,
            response.result.categories.data,
            container,
            order,
            setItemsToZeroAmount)
    });
}

/**
 *
 * @param {*} product
 * @param {*} data
 * @param {*} container
 * @param {*} order
 */


function componentCategoriesDisplayer(product, data, container, order, setItemsToZeroAmount) {
    var eos = JSON.parse(getStorage('edit-order-slip'));
    var c = $('#' + container);
    c.empty();
    // console.log('componentCategoriesDisplayer', data);
    $.each(data, function (k, v) {

        v.qty = 0;
        if (v.price <= product.price) {
            v.price = 0;
        } else {
            v.price = (v.price - product.price);
        }

        if(setItemsToZeroAmount == 1){
            v.price = 0;
        }

        // initialize product order
        var eos = JSON.parse(getStorage('edit-order-slip'));
        // console.log('componentCategoriesDisplayer initialize', order);

        $.each(order, function (kk, vv) {
            if (
                product.product_id == vv.main_product_comp_id &&
                v.product_id == vv.product_id
            ) {

                // console.log('others a', eos.data.others);
                $.each(eos.data.others, function (kkk, vvv) {
                    if (product.product_id == vvv.main_product_component_id) {
                        // console.log('others b', vv.name);
                        //console.log(vv);
                        vvv.others.push({
                            product_id: parseInt(vv.product_id),
                            name: vv.name,
                            price: vv.srp,
                            qty: vv.qty,
                            main_product_id: parseInt(eos.data.product_id),
                            main_product_component_qty: vv.main_product_comp_qty,
                            main_product_component_id: parseInt(vv.main_product_comp_id),

                            total: (vv.srp * vv.qty),
                            part_number: vv.part_number,
                        });
                    }
                });
            }

        });
        //
        setStorage('edit-order-slip', JSON.stringify(eos));



        var _id = container + '-' + v.product_id;

        c.append(
            '<div class="row justify-content-between border bottom my-2">' +
            '<div class="">' +
            '<span class="font-size-14 text-dark ">' + v.short_code + ' (₱ ' + numberWithCommas(v.price) + ')</span>' +
            '</div>' +
            '<div class="">' +
            '<div class="input-group input-group-sm ">' +
            '<input type="text" class="form-control" id="' + _id + '-qty" placeholder="" aria-label="" aria-describedby="button-addon4" value="0" disabled>' +
            '<div class="input-group-append" id="button-addon4">' +
            '<button ' +
            'id="' + _id + '-minus"' +
            'data-main_product_component_id="' + product.product_id + '" ' +
            'data-main_product_id="{{ $product_id }}" ' +
            'data-name="' + v.short_code + '" ' +
            'data-price="' + v.price + '" ' +
            'data-product_id="' + v.product_id + '" ' +
            'class="btn btn-danger btn-inverse" type="button" ' +
            '>' +
            '<i class="ti-minus"></i>' +
            '</button>' +
            '<button ' +
            'id="' + _id + '-plus" ' +
            'data-main_product_component_id="' + product.product_id + '" ' +
            'data-main_product_id="{{ $product_id }}" ' +
            'data-name="' + v.short_code + '" ' +
            'data-price="' + v.price + '" ' +
            'data-product_id="' + v.product_id + '" ' +
            'class="btn btn-success btn-inverse" type="button"' +
            '>' +
            '<i class="fa fa-plus"></i>' +
            '</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'

        );
        btnComponentCategoryMinus(_id + '-minus');
        btnComponentCategoryPlus(_id + '-plus');

        logicDisplay();
    });
}

function logicDisplay() {
    var grand_total = 0;
    var eos = JSON.parse(getStorage('edit-order-slip'));
    var nmc = JSON.parse(getStorage('nmc'));

    /**
     * MAIN PRODUCT SECTION
     */
    grand_total += eos.data.total;
    $('#m-product-qty').val(eos.data.qty);
    $('#instruction').val(eos.data.instruction);

    /**
     * COMPONENTS SECTION
     */
    $.each(eos.data.others, function (k, v) {
        $('#' + eos.data.product_id + '-' + v.product_id).text(v.qty);
    });

    /**
     * SUB COMPONENTS SECTION - ORIG
     */
    // $.each(eos.data.others, function (k, v) {
    //     $.each(v.others, function (kk, vv) {

    //         var _id = '#' + eos.data.product_id + '-' + v.product_id + '-categories-' + vv.product_id + '-qty';
    //         $(_id).text(vv.qty);
    //         grand_total += vv.total;
    //     });
    // });

    /**
     * SUB COMPONENTS SECTION
     */

    $.each(eos.data.others, function (k, v) {
        let temp_prod = null;
        let temp_qty = 0;
        for (var i = v.others.length - 1; i >= 0; i--) {
            //     console.log(i, v.others[i]);

            if (temp_prod != v.others[i].product_id && v.others[i].main_product_component_id != null) {
                temp_qty = v.others[i].qty;
                temp_prod = v.others[i].product_id;
            } else {
                temp_qty += v.others[i].qty;
                // console.log('to_delete item', v.others[i]);
                v.others.splice(i, 1);
                // console.log('current_item', v.others[i]);

                v.others[i].qty = temp_qty;
                // console.log("subcomponent details ", v.others[i], v.others[i].qty, v.others[i].qty * v.others[i].total)
                v.others[i].total = v.others[i].qty * v.others[i].total;
            }

            var _id = '#' + eos.data.product_id + '-' + v.product_id + '-categories-' + v.others[i].product_id + '-qty';
            $(_id).val(v.others[i].qty);
            setStorage('edit-order-slip', JSON.stringify(eos));
            grand_total += v.others[i].total;

        }

    });



    $.each(nmc, function (k, v) {
        $('#nmc-' + v.parent_id + '-' + v.product_id).text(parseInt(v.quantity * eos.data.qty));
    });


    // console.log('grand_total', grand_total);
    setStorage('edit-order-slip', JSON.stringify(eos));

    $('#grand-total').text('TOTAL : ' + numberWithCommas(grand_total));
}


function btnComponentCategoryMinus(id) {
    $('#' + id).on('click', function () {
        var data = {
            main_product_component_id: $(this).data('main_product_component_id'),
            main_product_id: $(this).data('main_product_id'),
            name: $(this).data('name'),
            price: $(this).data('price'),
            product_id: $(this).data('product_id')
        };

        // initialize product order
        var eos = JSON.parse(getStorage('edit-order-slip'));
        $.each(eos.data.others, function (k, v) {
            if (data.main_product_component_id == v.main_product_component_id) {
                var _index_to_remove = -1;
                $.each(v.others, function (kk, vv) {
                    if (data.product_id == vv.product_id) {
                        if (vv.qty > 0) {
                            v.qty++;
                            vv.qty--;
                            vv.total = vv.price * vv.qty;
                            if (vv.qty == 0) {
                                var _id = '#' + eos.data.product_id + '-' + v.product_id + '-categories-' + vv.product_id + '-qty';
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
        setStorage('edit-order-slip', JSON.stringify(eos));
        logicDisplay();
    });
}

function btnComponentCategoryPlus(id) {
    $('#' + id).on('click', function () {

        var data = {
            main_product_component_id: $(this).data('main_product_component_id'),
            main_product_id: $(this).data('main_product_id'),
            name: $(this).data('name'),
            price: $(this).data('price'),
            product_id: $(this).data('product_id')
        };

        // initialize product order
        var eos = JSON.parse(getStorage('edit-order-slip'));

        // check if the selected sub component category is exist in sub component
        $.each(eos.data.others, function (k, v) {
            if (data.main_product_component_id == v.main_product_component_id) {

                if (v.qty > 0) {
                    // adding to sub component if not exist
                    var if_exist = false;
                    $.each(v.others, function (kk, vv) {
                        if (data.product_id == vv.product_id) {
                            if_exist = true;
                            vv.qty++;
                            // console.log('btncomponentplus', vv)
                            vv.total = vv.price * vv.qty;
                            v.qty--;
                        }
                    });

                    if (if_exist == false) {
                        v.others.push({
                            product_id: parseInt(data.product_id),
                            name: data.name,
                            price: data.price,
                            qty: 1,
                            main_product_id: parseInt(eos.data.product_id),
                            main_product_component_qty: v.quantity,
                            main_product_component_id: parseInt(v.product_id),

                            total: (data.price * 1),
                            part_number: v.part_number,
                        });
                        v.qty -= 1;
                    }

                } else {
                    cl(['No Available Qty']);
                    showWarning('', 'No Available Quantity', function () {});
                }
            }
        });

        //
        setStorage('edit-order-slip', JSON.stringify(eos));

        logicDisplay();
    });
}

$('.btn-info.add-to-order').on('click', function () {




    // $(this).attr('disabled', 'disabled');

    let eos = JSON.parse(getStorage('edit-order-slip'));
    var nmc = JSON.parse(getStorage('nmc'));
    eos.nmc = nmc;

    // var data = {
    //     _method: 'PATCH',
    //     data: JSON.stringify(eos)
    // };

    // post('/orderslip', data, function (response) {
    //     if (response.success == false) {
    //         showWarning('', response.message, function () {

    //         });
    //         return;
    //     }

    //     showSuccess('', 'Success', function () {

    //     });

    // });



    var modal = $('#modal-order-review');
    var totalAmount = eos.data.total;
    // console.log('sub nmc',nmc);
    // console.log('sub eos', eos.data);

    modal.find('#osd_container').empty();
    modal.find('#osd_container').append(`
        <tr>
            <td>${ eos.data.name }</td>
            <td class="text-right">${ eos.data.qty }</td>
            <td class="text-right">₱${ numberWithCommas(eos.data.price) }</td>
            <td class="text-right">₱${ numberWithCommas(eos.data.price*eos.data.qty)}</td>
        </tr>
    `);


    $.each(eos.data.others, function (k, v) {
        if (v.qty > 0) {
            modal.find('#osd_container').append(`
                <tr aria-controls="group-of-rows-3">
                    <td>&emsp;${ v.name }</td>
                    <td class="text-right">${ v.qty }</td>
                    <td class="text-right">₱${ numberWithCommas(v.price) }</td>
                    <td class="text-right">₱${numberWithCommas( v.price *v.qty) }</td>
                </tr>
            `);
        }
        $.each(v.others, function (kk, vv) {
            modal.find('#osd_container').append(`
                <tr aria-controls="group-of-rows-3">
                    <td>&emsp;${ vv.name }</td>
                    <td class="text-right">${ vv.qty }</td>
                    <td class="text-right">₱${ numberWithCommas(vv.price)}</td>
                    <td class="text-right">₱${numberWithCommas(vv.price * vv.qty) }</td>
                </tr>
            `);

            totalAmount += vv.price * vv.qty;
        });
    });

    modal.find('#total_amount').text('Total: ₱' + numberWithCommas(totalAmount));
    $.each(nmc, function (k, v) {

        modal.find('#osd_container').append(`
            <tr aria-controls="row">
                <td>&emsp;${ v.description }</td>
                <td class="text-right">${ parseInt(""+v.quantity *eos.data.qty) }</td>
                <td class="text-right">₱${ numberWithCommas(0) }</td>
                <td class="text-right">₱${ numberWithCommas(0) }</td>
            </tr>
        `);
    });


    /** =========== */
    modal.find('#confirm').on('click', function (event) {

        modal.find('#confirm').attr('disabled', true);
        event.preventDefault();

        var data = {
            _method: 'PATCH',
            data: JSON.stringify(eos)
        };

        post('/orderslip', data, function (response) {
            if (response.success == false) {
                showWarning('', response.message, function () {

                });
                return;
            }
            modal.modal('toggle');

            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Successfully updated item in cart',
                showConfirmButton: false,
                timer: 1000
            }).then(function () {

                if(getStorage('selected-category') == null){
                    redirectTo("/category");
                }else{
                    redirectTo("/category/" + getStorage('selected-category') + "/products");
                }

            });

        });


    });

    modal.modal('toggle');
});

$('#btn-m-minus').on('click', function () {
    var eos = JSON.parse(getStorage('edit-order-slip'));
    if (eos.data.qty > 1) {
        eos.data.qty--;
        eos.data.total = eos.data.qty * eos.data.price;
        $.each(eos.data.others, function (k, v) {
            //v.qty = v.main_product_component_qty * po.qty;
            var qty_to_be_deduct = 1 * v.main_product_component_qty;

            if ((v.others).length > 0) {
                for (var i = 0; i < (v.others).length; i++) {
                    if (qty_to_be_deduct > 0) { // to check if there is qty to be deduct
                        if (v.others[i].qty > 0) {

                            if (qty_to_be_deduct <= v.others[i].qty) {
                                v.others[i].qty = v.others[i].qty - qty_to_be_deduct;
                                qty_to_be_deduct = 0;
                            }



                            if (v.others[i].qty == 0) { // should be removed if zero
                                var _id = '#' + eos.data.product_id + '-' + v.product_id + '-categories-' + v.others[i].product_id + '-qty';
                                $(_id).val(0);
                                v.others.splice(i, 1);
                            }
                        }
                    }
                }
            }


            // deduct component
            if (qty_to_be_deduct >= 0) {
                v.qty = v.qty - qty_to_be_deduct;
            }
        });

    }
    setStorage('edit-order-slip', JSON.stringify(eos));
    logicDisplay();
});

$('#btn-m-plus').on('click', function () {
    var eos = JSON.parse(getStorage('edit-order-slip'));
    eos.data.qty++;
    eos.data.total = eos.data.qty * eos.data.price;
    $.each(eos.data.others, function (k, v) {
        v.qty += v.main_product_component_qty * 1;
    });
    setStorage('edit-order-slip', JSON.stringify(eos));
    logicDisplay();
});

$("#m-product-qty").on("input", function () {
    var eos = JSON.parse(getStorage('edit-order-slip'));
    var pq = $('#m-product-qty');
    eos.data.qty = pq.val();
    eos.data.total = eos.data.qty * eos.data.price;
    $.each(eos.data.others, function (k, v) {
        v.qty = eos.data.qty;
        v.qty *= v.main_product_component_qty * 1;
    });

    setStorage('edit-order-slip', JSON.stringify(eos));
    logicDisplay();

});
$('#is_takeout').change('change', function(){
    console.log('she');
    var _this = $(this);
    var eos = JSON.parse( getStorage('edit-order-slip') );
    eos.data.is_take_out = _this.is(':checked');
    setStorage('edit-order-slip', JSON.stringify(eos));
});

