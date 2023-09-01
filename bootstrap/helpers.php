<?php 

function getClarionDate(\Carbon\Carbon $date){
    $start_date = '1801-01-01';
    $start_from = \Carbon\Carbon::parse($start_date);
    $diff = $date->diffInDays($start_from) + 4;
    return $diff;
}

function getUserOutletId(){
    return auth()->user()->userDevice( request()->cookie('device_id') )->outlet_id;
}

function getUserBranchId(){
    return auth()->user()->userDevice( request()->cookie('device_id') )->branch_id;
}

function getDeviceId(){
    return request()->cookie('device_id');
}

function getUserPosId($pos_id =0){
    return \App\Device::where('branch_id', getUserBranchId())
        ->where('outlet_id',getUserOutletId())
        ->where('_id',getDeviceId())
        ->first();
}
function getBranchName(){
    return \App\Branches::where('branch_id',getUserBranchId())
    ->first();
}
function getOutletName(){
    return \App\Outlet::where('outlet_id',getUserOutletId())->first();
}
function getDeviceName(){
    return \App\Device::where('_id',getDeviceId())->first();
}


function osNumberGenerator($deviceNo = 0, $header_id = 0, $outlet_id = 0){ 

    // outlet
    // $outlet = sprintf("%'.02d\n", $outlet_id);
    $outlet = '';
    $_outlet = \App\Outlet::where('branch_id', getUserBranchId())
        ->where('outlet_id', $outlet_id)
        ->first();
    if( $_outlet ){
        $outlet = $_outlet->code;
    }
    // $outlet = str_pad($outlet_id, 3, "0", STR_PAD_LEFT);

    // device
    // $device = sprintf("%'.02d\n", $deviceNo);
    $device = str_pad($deviceNo, 2, "0", STR_PAD_LEFT);

    // orderslip id
    // $orderslip = sprintf("%'.015d\n", $header_id); 
    $orderslip = str_pad($header_id, 10, "0", STR_PAD_LEFT);

    $output = "{$outlet}{$device}-{$orderslip}";

    // \Log::debug($outlet);
    // \Log::debug($device);
    // \Log::debug($orderslip);
    // \Log::debug($output);
    return $output;
}
function mealstubCount(){
    return \App\OrderSLipHeader::all();
}