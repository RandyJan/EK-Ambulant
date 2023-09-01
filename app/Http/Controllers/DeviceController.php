<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\RedeemOutlets;

class DeviceController extends Controller
{
    //
    public function list()
    {
        $devices = Device::getAllByBranch();
        return view('pages.devices', compact('devices'));
    }

    public function select($id)
    {

        // $user = auth()->user();
        // $user->device_no = $id;
        // $user->save();
        // return redirect()->route('home');

        $user = auth()->user();
        $device_chosen = Device::getDevice($id);

        if ($device_chosen->status == 1) {
            return;
        }


        $device_previous = Device::getDevice($user->device_no);
        // set the previous device to available
        if(!is_null($device_previous)){
            $device_previous->status = 0;
            $device_previous->save();
        }

        // set the chosen device to used
        $device_chosen->status = 1;
        $device_chosen->save();

        // modify the assigned device
        $user->device_no = $id;
        $user->save();
        return redirect()->route('home');
    }





    /**
     *
     */
    public function showFormForDeviceId(){
        return view('pages.device.form');
    }

    public function setDevice(){


        if( !request()->device_id ){
            return back()->with('error','Device ID is required!');
        }
        if( !request()->barcode_type ){
            return back()->with('error','Barcode type is required!');
        }

        // if( !request()->security_key ){
        //     return back()->with('error','Security Key is required inorder to set Device Id for this Device!');
        // }

        $device = Device::where('_id', request()->device_id)
            ->first();

        if(is_null($device)){
            //  return response()->json([
            //     'success'   => false,
            //     'status'    => 200,
            //     'message'   => 'Your device might not in the list.'
            // ]);
            return back()->with('error','Your device id is not in the list.');
        }
        // check if there is at least one record of item that is redeemable in the branch and outlet of the device
        $accept_mealstub = RedeemOutlets::where('branch_id', $device->branch_id)
                        ->where('outlet_id', $device->outlet_id)
                        ->first();

        $show_mealstub = false;
        if(!is_null($accept_mealstub)){
            $show_mealstub = true;
        }

            return redirect()
                ->route('home')
                ->withCookie(
                    cookie()->forever('device_id', strtoupper(request()->device_id))
                )
                ->withCookie(
                    cookie()->forever('accept_mealstub', $show_mealstub)
                )
                ->withCookie(
                    cookie()->forever('barcode_type', request()->barcode_type)
                )
                ->withCookie(
                    cookie()->forever('device_type', request()->device_type)
                );

    }

    public function resetDeviceId(){
        \Cookie::queue(\Cookie::forget('device_id'));
        \Cookie::queue(\Cookie::forget('accept_mealstub'));
        \Cookie::queue(\Cookie::forget('barcode_type'));
        \Cookie::queue(\Cookie::forget('device_type'));
        return redirect()->route('home');
    }
}
