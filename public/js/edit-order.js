$(document).ready(function(){
    getOrders();
});
function getOrders(){
    let eos = JSON.parse(getStorage('edit-order-slip'));
    let data = {
        header_id : eos.header_id,
        branch_id : eos.branch_id,
        outlet_id : eos.outlet_id,
        device_id : eos.device_id,
        main_product_id : eos.main_product_id,
        sequence        : eos.sequence,
    };

    getWithHeader(routes.orderSlipDetail.getSingleOrder, data, function(response){
        if(response.success == false){
            showError('',response.message, function(){
            });
            return;
        }

        //console.log(response.result.data);

        //
        getProduct(response.result.data);
    });
}
