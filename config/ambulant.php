<?php

return [
    // 'branch_id' => APP\User::where('AMBULANT_BRANCH_ID'),
    'pos_no'                => env('POS_NUMBER'),
    'group_not_to_display'  => env('GROUP_NOT_TO_DISPLAY'),
    'items_to_display'      => env('ITEMS_TO_DISPLAY'),
    'master_code'           => env('MASTER'),
    'unit_code'             =>env('BSUNIT'),
    'customer_info'         => env('REQUIRED_TO_GET_CUSTOMER_INFO',0),
    // 'multiple_mealstub'     => env('MULTIPLE_MEALSTUB'),
    // mealstub
    'accept_multiple_mealstub'  => env('MEALSTUB_IS_MULTIPLE'),
    //'print_os_on_mealstub_order'=> env('MEALSTUB_ISSUE_PRINT_OS'),
    'mealstub_mix_other_items'  => env('MEALSTUB_CAN_MIX_WITH_OTHER_ITEMS'),
    'auto_paid_mealstub'        => env('MEALSTUB_SETPAID'),
    'ek_card_web_url'           => env('APP_EK_CARD_URL'),
    'no_price_diff'             => env('HIDE_PRICE_DIFFERENCE'),
    'enable_kiosk_function'    => env('ENABLE_KIOSK_FUNC'),
    'process_flow'              => env('FLOW')
];
