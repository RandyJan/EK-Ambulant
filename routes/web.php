<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Helpers\Helper;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderslipController;


Route::get('/device-id',    'DeviceController@showFormForDeviceId')->name('device-id-form.show');
Route::post('/device-id',    'DeviceController@setDevice')->name('device-id-form.store');
Route::get('/reset/device', 'DeviceController@resetDeviceId')->name('device-id.reset');

Route::middleware('device.id.checkpoint')->group(function () {


    Route::get('/login', 'LoginController@showLogin')->name('login');
    Route::post('/login', 'LoginController@login')->name('login');
    Route::get('/login',    'LoginController@details');

    Route::middleware('auth')->group(function () {

        Route::get('/outlets',              'OutletController@list')->name('outlets');
        Route::get('/outlet/{id}/select',   'OutletController@select')->name('outlet.select');
        Route::get('/devices',              'DeviceController@list')->name('devices');
        Route::get('/device/{id}/select',   'DeviceController@select')->name('device.select');

        // Route::middleware('is.on.duty')->group(function () {
        // Route::middleware('has.outlet')->group( function(){
        //     Route::middleware('has.device')->group( function(){

            Route::get('/',             'PagesController@main')->name('home');
            Route::get('/products',     'PartLocationController@products');
            // Route::get('/home',         'HomeController@home');
            Route::get('/logout',       'LoginController@logout');

            Route::get('/part-location/category/{cat_id}/sub-category', 'PartLocationController@category');


            Route::get('/orderslip/create',                             'OrderslipController@showForm')->name('orderslip.create');
            Route::post('/orderslip/create',                            'OrderslipController@create')->name('orderslip.create-empty-record');
            Route::get('/orderslip/{os_id}/table/{t_id}/create',        'OrderslipController@createTable');
            Route::post('/orderslip',                                   'OrderslipController@store');
            Route::patch('/orderslip',                                  'OrderslipController@update');
            Route::post('/orderslip/is_paid',                           'OrderslipController@checkOsPaid');
            Route::post('/orderslip/print',                             'OrderslipController@printRequest');
            Route::delete('/orderslip/remove-selected-item',            'OrderslipController@removeSelectedItem');


            Route::get('/tables',                                       'TableController@index');
            Route::get('/orderslip/tables',                             'OrderslipController@getTable');


            Route::get('/outlet/{outlet_id}/product/{product_id}',      'PartLocationController@product')->name('product-selected');
            // product
            Route::post('/product',                                     'PartLocationController@productByOutlet');
            Route::post('/product/components',                          'PartLocationController@productComponents');
            Route::post('/product/component/categories',                'PartLocationController@productByCategory');


            Route::post('/guestfile/{id}',                              'GuestFileController@findByTableno');
            Route::post('/updateGuest',                                 'GuestFileController@updateGuestCred');



            Route::get('/change-os/{id}',                               'OrderslipController@changeOs');
            Route::get('/orderslip/{id}/{device_id}/change',            'OrderslipController@changeOs')->name('orderslip.change');

            Route::get('/orderslip/print-preview',                      'PagesController@printPreview');
            Route::get('/subCategory',                                  'PagesController@subCategory');
            Route::get('/main-products',                                'PagesController@products');
            Route::get('/category',                                     'PartLocationController@categories')->name('categories');
            Route::get('/category/{group}/products',                    'PartLocationController@productsOfCategory')->name('categories.products');

                // ======================
                Route::get('/part-location/category',                         'PartLocationController@groups');
                // Route::get('/part-location/bsunit',                          'PartLocationController@unit');
                // Route::get('/part-location/{unit_id}/groups',                'PartLocationController@groups');

                //===================== ADMIN SIDE =====================//
                Route::get('/pages/admin/admin',   'PagesController@branch');

            //suggested items//

            /**
             * ajax requests
             */
            Route::post('/orderslip-info', 'OrderslipController@information');
            Route::patch('/orderslip/headcount', 'OrderslipController@headcount');
            Route::patch('/orderslip/set-duration', 'OrderslipController@setDuration');

    //     });
    // });
    // });

        /**
         * MEALSTUB
         */
        Route::get('/mealstub', 'MealstubController@showForm')->name('mealstub');
        Route::post('/mealstub/checker', 'MealstubController@checkCode')->name('mealstub-checker');
        Route::post('/mealstub/verify', 'MealstubController@verify')->name('mealstub-verify');
        // Route::post('/mealstub/claim', 'MealstubController@claim')->name('mealstub-claim');
        Route::post('/mealstub/claim2', 'MealstubController@claim2')->name('mealstub-claim2');
        Route::get('/mealstub/input',   'MealstubController@inputMealStub')->name('mealstubInput');

        Route::get('/mealstub/{ref_id}', 'MealstubController@mealProduct')->name('mealstub-product');
        Route::post('/mealstub/components', 'MealstubController@mealComponents')->name('mealstub-components');
        Route::post('/mealstub/main_item', 'MealstubController@mealMainItem');


        Route::get('/mealstub/{ref}/modify',    'MealstubController@modifyForm');
        Route::post('/mealstub/get-info',       'MealstubController@getInfo');
        Route::patch('/mealstub/update_os_type', 'MealstubController@update');

        /**
         * INSTRUCTION
         */
        Route::post('/orderslip/get-instruction', 'OrderslipInstructionController@getInstruction')->name('orderslip.instruction');
        Route::patch('/orderslip/update-instruction', 'OrderslipInstructionController@update')->name('orderslip.instruction');

        /**
         * Order Summary
         */
        Route::get('/order-summary',                 'PagesController@orderSummary')->name('summary');
        Route::get('/order-summary',      'PagesController@orderSummaryPerDevice')->name('summary_device');

        // Customer's Info/
        Route::post('/customer-info',                'CustomerInfoController@saveInfo');

        /**
         * deactivate active orderslip when it is not created today
         */
        Route::post('/orderslip/resetActiveOrder',    'OrderslipController@resetActiveOrder');

        Route::get('/edit-order',       'PagesController@editProduct');
        Route::get('/get-single-order', 'OrderslipDetailController@getSingleOrder')->name('orderslip.getSingleOrder');
        // Route::patch('/edit-order', 'PagesController@editProduct');

    });

});




/**
 * DEBUGGING SECTION
 */
Route::prefix('/browser')->name('browser.')->group( function(){
    Route::get('/', 'BrowserController@userDevices')->name('user-devices');
    Route::get('/user-devices', 'BrowserController@userDevices')->name('user-devices');
    Route::get('/mealstubs', 'BrowserController@mealstub')->name('mealstub');
    Route::get('/terminals', 'BrowserController@terminals')->name('terminals');
});
