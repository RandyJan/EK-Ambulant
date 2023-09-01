<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\MealStub;
use App\SitePart;
use DB, Auth;
use App\OrderSlipHeader;
use App\OrderSlipDetail;
use App\Postmix;
use App\MealstubComponents;
use App\Http\Resources\MealstubComponentsResource;
use App\Partlocation;
use Illuminate\Support\Collection;


use App\Services\UserServices;


class MealstubController extends Controller
{
    //
    public function showForm(){
        return view('pages.mealstub.mealstub');
    }

    public function mealProduct(){
        return view('pages.mealstub.mealstub-product');
    }

    public function checkCode(Request $request){

        // logic
        // \Log::debug('CODE: '.$request->code);

        $mealstub = MealStub::where('branch_id', getUserBranchId())
            ->where('serial_number', $request->code)
            ->where('type', 'MS')
            ->first();

        if( !$mealstub ){
            return response()->json([
                'success' => false,
                // 'message' => 'Mealstub not found!'
                'message' => 'Claim stub not found!'
            ]);
        }

        if( $mealstub->status == 1){
            return response()->json([
                'success' => false,
                'message' => 'Claim stub is already used!'
            ]);
        }

        if( $mealstub->validity_date < getClarionDate(now()) ){
            return response()->json([
                'success' => false,
                'message' => 'Claim stub is expired!'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data'  => [
                'reference_id'  => $mealstub->reference_id,
                'serial_number' => $mealstub->serial_number
            ]
        ]);
    }

    /*
    public function claim(Request $request){

        try{
            // \Log::debug('claiming is starting...');
            DB::beginTransaction();

            $user       = Auth::user();
            $branch_id  = getUserBranchId();
            $outlet_id  = getUserOutletId();
            $device_id  = getDeviceId();

            $sitepart = SitePart::where('branch_id', $branch_id)
                ->where('sitepart_id', $request->reference)
                ->first();

            if( !$sitepart ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Item is not in the record'
                ]);
            }

            // check 1st if there is an existing orderslip
            $header = $user->activeOrder();

            if( !$header ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Please create orderslip 1st inorder to continue!'
                ]);
            }

            $osdStatus = 'T';
            // if($header->qdate != null){
            //     $osdStatus = 'X';
            // }

            // TODO: find if there is an existing mealstub
            // in the details to avoid double claiming

            $osd = OrderSlipDetail::where('branch_id', $header->branch_id)
                    ->where('outlet_id', $header->outlet_id)
                    ->where('device_number', $header->device_no)
                    ->where('mealstub_serialnumber', $request->serial_number)
                    ->where('mealstub_product_id', $request->reference)
                    ->where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
                    ->where('status', '!=', 'V')
                    ->first();

            if( $osd ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Mealstub was already added in your cart'
                ]);
            }


            // directly save mealstub main item in the orderslipdetail
            $osh = OrderSlipHeader::where('encoded_by', $header->encoded_by)
                ->where('branch_id', $header->branch_id)
                ->where('outlet_id', $header->outlet_id)
                ->where('device_no', $header->device_no)
                ->first();


            $mealstub_count = 0;
            if(!is_null($osh->mealstub_count)){
                $mealstub_count = $osh->mealstub_count;
            }

            $update = OrderSlipHeader::where('encoded_by', $header->encoded_by)
                ->where('branch_id', $header->branch_id)
                ->where('outlet_id', $header->outlet_id)
                ->where('device_no', $header->device_no)
                ->update([
                    'MEAL_STUB_COUNT' => $mealstub_count + 1,
                    'DISPLAYMONITOR' => 2
                ]);


            $line_number = OrderSlipDetail::getLastLineNumber($header->branch_id, $header->orderslip_header_id, $header->outlet_id, $header->device_no ) + 1;
            $dev_no_mod = null; // device id which took and modify the order
            if($header->device_no == $device_id ){
                $dev_no_mod = null;
            }else{
                $dev_no_mod = $device_id;
            }

            $osd = new OrderSlipDetail;
            $osd->orderslip_detail_id           = $osd->getNewId($header->branch_id, $header->outlet_id, $header->device_no); // ito ung orig david
            $osd->orderslip_header_id           = $header->orderslip_header_id;
            $osd->branch_id                     = $branch_id;
            $osd->remarks                       = '';
            $osd->order_type                    = $osd->getOrderTypeValue(1);
            $osd->product_id                    = $sitepart->sitepart_id;
            $osd->qty                           = 1;
            $osd->srp                           = 0; //$sitepart->srp;
            $osd->amount                        = $osd->qty * 0; //$sitepart->srp;
            $osd->net_amount                    = $osd->qty * 0; //$sitepart->srp;
            //$osd->is_modify                     = 1;
            $osd->line_number                   = $line_number;
            $osd->order_no                      = $osd->line_number;
            $osd->status                        = $osdStatus;
            $osd->postmix_id                    = $sitepart->postmix;
            $osd->main_product_id               = $sitepart->sitepart_id;
            $osd->main_product_comp_id          = null; //$request->main_product_component_id;
            $osd->old_comp_id                   = null; //$request->main_product_component_id;
            $osd->main_product_comp_qty         = null; //$request->main_product_component_qty;
            $osd->part_number                   = $sitepart->part_no;
            $osd->encoded_date                  = now();
            $osd->sequence                      = $osd->getNewSequence(
                                                    $header->branch_id,
                                                    $header->orderslip_header_id,
                                                    $request->reference,
                                                    $header->outlet_id,
                                                    $header->device_no
                                                );
            $osd->guest_no                      = null;
            $osd->guest_type                    = null;
            $osd->device_number                 = $header->device_no;
            $osd->outlet_id                     = $header->outlet_id;
            $osd->table_no                      = null;
            $osd->kitchen_loc                   = $this->getKitchenLocation($sitepart->sitepart_id, $branch_id);
            $osd->os_date                       = getClarionDate(now());
            $osd->display_kds                   = 1;
            $osd->dev_id_mod                    = $dev_no_mod;
            $osd->mealstub_product_id           = $request->reference;
            $osd->mealstub_serialnumber         = $request->serial_number;
            $osd->save();


                // if it is postmix, get first all the products in the postmix.
                if( $sitepart->postmix == 1){

                    // save each of the item in the orderslip detail under of main item
                    $postmixs = Postmix::where('parent_id', $sitepart->sitepart_id)->get();

                    foreach( $postmixs as $postmix){
                        // reading sitepart again and save to orderslip detail check if it is postmix
                        $sitepartLevel2 = SitePart::where('branch_id', $branch_id)
                            ->where('sitepart_id', $postmix->product_id)
                            ->first();
                        $line_number = $line_number + 1;

                        $osd2 = new OrderSlipDetail;
                        $osd2->orderslip_detail_id           = $osd2->getNewId($header->branch_id, $header->outlet_id, $header->device_no); // ito ung orig david
                        $osd2->orderslip_header_id           = $header->orderslip_header_id;
                        $osd2->branch_id                     = $branch_id;
                        $osd2->remarks                       = '';
                        $osd2->order_type                    = $osd2->getOrderTypeValue(1);
                        $osd2->product_id                    = $sitepartLevel2->sitepart_id;
                        // dd('osd2', 'b');
                        $osd2->qty                           = $postmix->quantity;
                        $osd2->srp                           = 0; //$sitepartLevel2->srp;
                        $osd2->amount                        = $osd2->qty * 0; //$sitepartLevel2->srp;
                        $osd2->net_amount                    = $osd2->qty * 0; //$sitepartLevel2->srp;
                        //$osd->is_modify                     = 1;
                        $osd2->line_number                   = $line_number;
                        $osd2->order_no                      = $osd2->line_number;
                        $osd2->status                        = $osdStatus;
                        $osd2->postmix_id                    = $sitepartLevel2->postmix;
                        $osd2->main_product_id               = $osd2->product_id;
                        $osd2->main_product_comp_id          = null; //$request->main_product_component_id;
                        $osd2->old_comp_id                   = null; //$request->main_product_component_id;
                        $osd2->main_product_comp_qty         = null; //$request->main_product_component_qty;
                        $osd2->part_number                   = $sitepartLevel2->part_no;
                        $osd2->encoded_date                  = now();
                        $osd2->sequence                      = $osd->sequence;
                        $osd2->guest_no                      = null;
                        $osd2->guest_type                    = null;
                        $osd2->device_number                 = $header->device_no;
                        $osd2->outlet_id                     = $header->outlet_id;
                        $osd2->table_no                      = null;
                        $osd2->kitchen_loc                   = $this->getKitchenLocation($osd2->product_id, $branch_id);
                        $osd2->os_date                       = getClarionDate(now());
                        $osd2->display_kds                   = 1;
                        $osd2->dev_id_mod                    = $dev_no_mod;
                        $osd2->mealstub_product_id           = $request->reference;
                        $osd2->mealstub_serialnumber         = $request->serial_number;
                        $osd2->save();


                        // \Log::debug('success saving all components of mealstub');
                        $postmixs2 = Postmix::where('parent_id', $sitepartLevel2->sitepart_id)->get();
                        foreach( $postmixs2 as $postmix2){
                            $sitepartLevel3 = SitePart::where('branch_id', $branch_id)
                                ->where('sitepart_id', $postmix2->product_id)
                                ->first();

                            $line_number = $line_number + 1;
                            $osd3 = new OrderSlipDetail;
                            $osd3->orderslip_detail_id           = $osd3->getNewId($header->branch_id, $header->outlet_id, $header->device_no); // ito ung orig david
                            $osd3->orderslip_header_id           = $header->orderslip_header_id;
                            $osd3->branch_id                     = $branch_id;
                            $osd3->remarks                       = '';
                            $osd3->order_type                    = $osd3->getOrderTypeValue(1);
                            $osd3->product_id                    = $sitepartLevel3->sitepart_id;

                            $osd3->qty                           = $postmix2->quantity;
                            $osd3->srp                           = 0; //$sitepartLevel3->srp;
                            $osd3->amount                        = $osd3->qty * 0; //$osd3->srp;
                            $osd3->net_amount                    = $osd3->qty * 0; //$osd3->srp;
                            //$osd->is_modify                     = 1;
                            $osd3->line_number                   = $line_number;
                            $osd3->order_no                      = $osd3->line_number;
                            $osd3->status                        = $osdStatus;
                            $osd3->postmix_id                    = $sitepartLevel3->postmix;
                            $osd3->main_product_id               = $osd2->product_id;
                            $osd3->main_product_comp_id          = $osd3->product_id; //$request->main_product_component_id;
                            $osd3->old_comp_id                   = $osd3->product_id; //$request->main_product_component_id;
                            $osd3->main_product_comp_qty         = $postmix2->quantity; //$request->main_product_component_qty;
                            $osd3->part_number                   = $sitepartLevel3->part_no;
                            $osd3->encoded_date                  = now();
                            $osd3->sequence                      = $osd->sequence;
                            $osd3->guest_no                      = null;
                            $osd3->guest_type                    = null;
                            $osd3->device_number                 = $header->device_no;
                            $osd3->outlet_id                     = $header->outlet_id;
                            $osd3->table_no                      = null;
                            $osd3->kitchen_loc                   = $this->getKitchenLocation($osd3->product_id, $branch_id);
                            $osd3->os_date                       = getClarionDate(now());
                            $osd3->display_kds                   = 1;
                            $osd3->dev_id_mod                    = $dev_no_mod;
                            $osd3->mealstub_product_id           = $request->reference;
                            $osd3->mealstub_serialnumber         = $request->serial_number;
                            $osd3->save();
                        }

                    }

                }

                // $update = OrderSlipHeader::where('encoded_by', $header->encoded_by)
                // ->where('branch_id', $header->branch_id)
                // ->where('outlet_id', $header->outlet_id)
                // ->where('device_no', $header->device_no)
                // ->update([
                //     'ISACTIVE' => 0
                // ]);

                            // check

            if($header->qdate == null){
                // \Log::debug('TIMEOUT IS NOT SET YET');

                OrderSlipHeader::where('encoded_by', $header->encoded_by)
                    ->where('branch_id', $header->branch_id)
                    ->where('outlet_id', $header->outlet_id)
                    ->where('device_no', $header->device_no)
                    ->update([
                        'QDATE' => now(),
                    ]);



            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Success'
            ]);
        }catch(\Exception $e){
            DB::rollback();
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }


    }
    */



    public function verify(Request $request){

        try{
            // \Log::debug('claiming is starting...');
            DB::beginTransaction();

            $user       = Auth::user();
            $branch_id  = getUserBranchId();
            $outlet_id  = getUserOutletId();
            $device_id  = getDeviceId();

            // check 1st if there is an existing orderslip
            $header = $user->activeOrder();

            if( !$header ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Please create orderslip first in order to continue!'
                ]);
            }

            $osdStatus = 'T';

            // if it doesn't accept multiple mealstub
            // only store an item that is a mealstub, nothing else
            if(config('ambulant.multiple_mealstub') == 0){

                // 'accept only one mealstub/item in cart'
                $osd = OrderSlipDetail::where('branch_id', $header->branch_id)
                    ->where('outlet_id', $header->outlet_id)
                    ->where('device_number', $header->device_no)
                    ->where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
                    ->where('status', '!=', 'V')
                    ->first();

                if($osd){
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'You have an item in your cart. Mealstub will be added if you have no item/s in your cart'
                    ]);
                }

            }

            // find if there is an existing mealstub
            // in the osd to avoid double claiming

            $osd = OrderSlipDetail::where('branch_id', $header->branch_id)
                    ->where('outlet_id', $header->outlet_id)
                    ->where('device_number', $header->device_no)
                    ->where('mealstub_serialnumber', $request->serial_number)
                    ->where('mealstub_product_id', $request->reference)
                    ->where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
                    ->where('status', '!=', 'V')
                    ->first();

            if( $osd ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Mealstub was already added in your cart'
                ]);
            }

            // $components = MealStubComponents::where('reference_id', $request->reference)
            // ->get();
            // return redirect()->route('mealstub-product', ['ref_id'=>$request->reference]);
            // return \Redirect::route('mealstub-product',  ['ref_id'=>$request->reference]);
            // return view('pages.mealstub.mealstub-product', compact($components));

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => [
                    'reference_id' => $request->reference,
                    'branch_id' => $branch_id,
                    'outlet_id' => $outlet_id
                ]
            ]);

        }catch(\Exception $e){
            DB::rollback();
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }

    }



    public function claim(Request $request){

        try{
            // \Log::debug('claiming is starting...');
            DB::beginTransaction();

            $user       = Auth::user();
            $branch_id  = getUserBranchId();
            $outlet_id  = getUserOutletId();
            $device_id  = getDeviceId();

            // check 1st if there is an existing orderslip
            $header = $user->activeOrder();

            if( !$header ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Please create orderslip first in order to continue!'
                ]);
            }

            // find if there is an existing mealstub
            // in the details to avoid double claiming

            $osd = OrderSlipDetail::where('branch_id', $header->branch_id)
                    ->where('outlet_id', $header->outlet_id)
                    ->where('device_number', $header->device_no)
                    ->where('mealstub_serialnumber', $request->serial_number)
                    ->where('mealstub_product_id', $request->reference)
                    ->where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
                    ->where('status', '!=', 'V')
                    ->first();

            if( $osd ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Mealstub was already added in your cart'
                ]);
            }


            // directly save mealstub main item in the orderslipdetail
            // $osh = OrderSlipHeader::where('encoded_by', $header->encoded_by)
            //     ->where('branch_id', $header->branch_id)
            //     ->where('outlet_id', $header->outlet_id)
            //     ->where('device_no', $header->device_no)
            //     ->first();


            $mealstub_count = 0;
            if(!is_null($header->mealstub_count)){
                $mealstub_count = $header->mealstub_count;
            }
            // \Log::debug('osh '.$osh->orderslip_header_id);
            $osdStatus = 'T';
            $net_amount = 0;

            $os_details = array(); // to test

            $line_number = OrderSlipDetail::getLastLineNumber($header->branch_id, $header->orderslip_header_id, $header->outlet_id, $header->device_no ) + 1;
            $dev_no_mod = null; // device id which took and modify the order
            if($header->device_no == $device_id ){
                $dev_no_mod = null;
            }else{
                $dev_no_mod = $device_id;
            }

            // $osd = new OrderSlipDetail;
            // $osd->orderslip_header_id           = $osh->orderslip_header_id;
            // $osd->branch_id                     = $branch_id;
            // $osd->remarks                       = $request->instruction;
            // $osd->order_type                    = $osd->getOrderTypeValue($request->is_take_out);
            // $osd->product_id                    = $request->product_id;
            // $osd->qty                           = $request->qty;
            // $osd->srp                           = $request->price;
            // $osd->amount                        = $request->qty * $request->price;
            // $osd->net_amount                    = $request->qty * $request->price;
            // //$osd->is_modify                     = 1;
            // $osd->line_number                   = $line_number;
            // $osd->order_no                      = $osd->line_number;
            // $osd->status                        = $osdStatus;
            // $osd->postmix_id                    = $postmix;
            // $osd->main_product_id               = $request->main_product_id;
            // $osd->main_product_comp_id          = $request->main_product_component_id;
            // $osd->old_comp_id                   = $request->main_product_component_id;
            // $osd->main_product_comp_qty         = $request->main_product_component_qty;
            // $osd->part_number                   = $request->part_number;
            // $osd->encoded_date                  = now();
            // $osd->sequence                      = $osd->getNewSequence(
            //                                         $branch_id,
            //                                         $osh->orderslip_header_id,
            //                                         $request->product_id,
            //                                         $user_outlet_id,
            //                                         $user_device_id
            //                                     );

            // $osd->guest_no                      = $request->guest_no;
            // $osd->guest_type                    = $request->guest_type;
            // $osd->device_number                 = $osh->device_no;
            // $osd->outlet_id                     = $user_outlet_id;
            // $osd->table_no                      = $request->table_no;
            // $osd->kitchen_loc                   = $this->getKitchenLocation($request->product_id, $branch_id);
            // $osd->os_date                       = getClarionDate(now());
            // $osd->display_kds                   = 1;
            // $osd->dev_id_mod                    = $dev_no_mod;


            // // $osd->branch_services_id            = $osh->os_number;



            // $osd->save();
            // dd($request->serial_number, $request->reference_id, $request->others);
            $net_amount = $request->qty * $request->price;

            $line_number = OrderSlipDetail::getLastLineNumber($branch_id,
                    $header->orderslip_header_id,
                    $header->outlet_id,
                    $header->device_no ) + 1;
            $main_line_number = $line_number;


            $sequence = OrderslipDetail::getMealstubNewSequence(
                                $branch_id,
                                $header->orderslip_header_id,
                                $request->product_id,
                                $header->outlet_id, $header->device_no
                            );
            // dd($sequence);
            // \Log::debug('line_number start' .$line_number);
            if (isset($request->others)) {
                foreach ($request->others as $other) {
                    $other = (object) $other;

                    if ($other->qty != 0) {
                        $osd2 = new OrderSlipDetail;
                        // $osd2->orderslip_detail_id           = $osd2->getNewId($branch_id, $user_outlet_id, $user_device_id);
                        $osd2->orderslip_detail_id           = $osd2->getNewId($branch_id, $header->outlet_id, $header->device_no);

                        $osd2->orderslip_header_id           = $header->orderslip_header_id;
                        $osd2->branch_id                     = $branch_id;
                        // $osd2->remarks                       = $request->instruction;
                        // $osd2->remarks                       = $other->instructions;
                        $osd2->order_type                    = $osd2->getOrderTypeValue($request->is_take_out);
                        $osd2->product_id                    = $other->product_id;
                        $osd2->qty                           = $other->qty;
                        $osd2->srp                           = $other->price;
                        $osd2->amount                        = $other->qty * $other->price;
                        $osd2->net_amount                    = $other->qty * $other->price;
                        $osd2->is_modify                     = 1;
                        $osd2->line_number                   = $line_number;
                        \Log::debug('line_number - osd2 ' .$line_number);
                        // $osd2->order_no                      = $osd->line_number; // TODO: di ko pa alam pano kunin
                        $osd2->order_no                      = $main_line_number; // TODO: di ko pa alam pano kunin
                        $osd2->status                        = $osdStatus;
                        $osd2->postmix_id                    = $other->postmix;
                        // $osd2->main_product_id               = $other->main_product_id;
                        $osd2->main_product_id               = null;
                        $osd2->main_product_comp_id          = $other->main_product_component_id;
                        $osd2->old_comp_id                   = $other->main_product_component_id;
                        $osd2->main_product_comp_qty         = $other->main_product_component_qty;
                        $osd2->part_number                   = $other->part_number;
                        $osd2->encoded_date                  = now();
                        $osd2->sequence                      = $sequence;
                        $osd2->guest_no                      = $request->guest_no;
                        $osd2->guest_type                    = $request->guest_type;

                        $osd2->device_number                 = $header->device_no;
                        $osd2->outlet_id                     = $header->outlet_id;
                        $osd2->table_no                      = $request->table_no;
                        $osd2->kitchen_loc                   = $this->getKitchenLocation($other->product_id, $branch_id);
                        $osd2->os_date                       = getClarionDate(now());
                        $osd2->display_kds                   = 1;
                        // $osd2->branch_services_id             = $osh->os_number;
                        // $osd2->display_kds                   = 1;

                        $osd2->dev_id_mod                    = $dev_no_mod;
                        $osd2->mealstub_product_id           = $request->reference_id;
                        $osd2->mealstub_serialnumber         = $request->serial_number;

                        $osd2->save();
                        $net_amount += $osd2->net_amount;
                        $line_number++;

                        array_push($os_details, $osd2);
                        \Log::debug('saved' .$osd2);
                    }
                    \Log::debug('net_amount - osd2 ' .$net_amount);
                    if (isset($other->others)) {
                        foreach ($other->others as $other2) {
                            $other2 = (object) $other2;
                            $osd3 = new OrderSlipDetail;
                            $osd3->orderslip_detail_id           = $osd3->getNewId($branch_id, $header->outlet_id, $header->device_no);

                            $osd3->orderslip_header_id           = $header->orderslip_header_id;
                            $osd3->branch_id                     = $branch_id;
                            // $osd3->remarks                       = $other2->instructions;
                            $osd3->order_type                    = $osd3->getOrderTypeValue($request->is_take_out);
                            $osd3->product_id                    = $other2->product_id;
                            $osd3->qty                           = $other2->qty;
                            $osd3->srp                           = $other2->price;
                            $osd3->amount                        = $other2->qty * $other2->price;
                            $osd3->net_amount                    = $other2->qty * $other2->price;
                            $osd3->is_modify                     = 1;
                            $osd3->line_number                   = $line_number;
                            // $osd3->order_no                      = $osd->line_number;
                            $osd3->order_no                      = $main_line_number;
                            $osd3->status                        = $osdStatus;
                            // $osd3->postmix_id                    = $other2->postmix;
                            $osd3->postmix_id                    = $other->postmix;
                            // $osd3->main_product_id               = $other2->main_product_id;
                            $osd3->main_product_id               = null;
                            $osd3->main_product_comp_id          = $other2->main_product_component_id;
                            $osd3->old_comp_id                   = $other2->main_product_component_id;
                            $osd3->main_product_comp_qty         = $other->main_product_component_qty;
                            $osd3->part_number                   = $other2->part_number;
                            $osd3->encoded_date                  = now();
                            $osd3->sequence                      = $sequence;
                            $osd3->guest_no                      = $request->guest_no;
                            $osd3->guest_type                    = $request->guest_type;

                            $osd3->device_number                 = $header->device_no;
                            $osd3->outlet_id                     = $header->outlet_id;
                            $osd3->table_no                      = $request->table_no;
                            $osd3->kitchen_loc                   = $this->getKitchenLocation($other2->product_id, $branch_id);
                            $osd3->os_date                       = getClarionDate(now());
                            $osd3->display_kds                   = 1;
                            // $osd3->branch_services_id            = $osh->os_number;
                            // $osd3->display_kds                   = 1;
                            $osd3->dev_id_mod                    = $dev_no_mod;
                            $osd3->mealstub_product_id           = $request->reference_id;
                            $osd3->mealstub_serialnumber         = $request->serial_number;
                            $osd3->save();
                            $net_amount += $osd3->net_amount;
                            $line_number++;
                            \Log::debug('line_number - osd2 ' .$line_number);
                            array_push($os_details, $osd3);
                        }
                    }
                }
            }


            // saving none modifiable component
            if (isset($request->none_modifiable_component)) {
                foreach ($request->none_modifiable_component as $nmc) {
                    // \Log::debug($nmc[0]->qty);
                    $nmc = (object) $nmc;
                    // dd($nmc->qty);

                    $_osd = new OrderSlipDetail;
                    // $_osd->orderslip_detail_id           = $_osd->getNewId($branch_id, $user_outlet_id, $user_device_id);;
                    $_osd->orderslip_detail_id           = $_osd->getNewId($branch_id, $header->outlet_id, $header->device_no);;

                    $_osd->orderslip_header_id           = $header->orderslip_header_id;
                    $_osd->branch_id                     = $branch_id;
                    // $_osd->remarks                       = $nmc->instruction;
                    $_osd->order_type                    = $_osd->getOrderTypeValue($request->is_take_out);
                    $_osd->product_id                    = $nmc->product_id;
                    $_osd->qty                           = ($nmc->qty * 1);
                    $_osd->srp                           = 0;
                    $_osd->amount                        = $_osd->qty * $_osd->srp;
                    $_osd->net_amount                    = $_osd->qty * $_osd->srp;
                    $_osd->is_modify                     = 0;
                    $_osd->line_number                   = $line_number;
                    // $_osd->order_no                      = $osd->line_number;
                    $_osd->order_no                      = $main_line_number;
                    $_osd->status                        = $osdStatus;
                    $_osd->postmix_id                    = $nmc->postmix;
                    // $_osd->main_product_id               = $osd->product_id;
                    $_osd->main_product_comp_id          = $_osd->product_id;
                    $_osd->old_comp_id                   = $_osd->product_id;
                    $_osd->main_product_comp_qty         = $_osd->qty;
                    $_osd->part_number                   = $nmc->part_number;
                    $_osd->encoded_date                  = now();
                    $_osd->sequence                      = $sequence;
                    $_osd->guest_no                      = $request->guest_no;
                    $_osd->guest_type                    = $request->guest_type;

                    $_osd->device_number                 = $header->device_no;
                    $_osd->outlet_id                     = $header->outlet_id;
                    $_osd->table_no                      = $request->table_no;
                    $_osd->kitchen_loc                   = $this->getKitchenLocation($nmc->product_id, $branch_id);
                    $_osd->os_date                       = getClarionDate(now());
                    $_osd->display_kds                   = 1;
                    // $_osd->branch_services_id            = $osh->os_number;
                    // $_osd->display_kds                    = 1;
                    $_osd->dev_id_mod                    = $dev_no_mod;
                    $_osd->mealstub_product_id           = $request->reference_id;
                    $_osd->mealstub_serialnumber         = $request->serial_number;
                    $_osd->save();
                    $line_number++;

                    array_push($os_details, $_osd);
                }
            }

            // return response()->json([
            //     'success' => false,
            //     'status' => 201,
            //     'data' => [
            //         'osd' =>$os_details,
            //         'netamount' => $net_amount
            //     ]
            // ]);
            // dd('sugoi');
            // //save the total into OrderSlipHeader
            OrderSlipHeader::where('orderslip_header_id', $header->orderslip_header_id)
                ->where('branch_id',$header->branch_id)
                ->where('outlet_id',$header->outlet_id)
                // ->where('device_no', $user_device_id)
                ->where('device_no', $header->device_no)
                ->update([
                    'TOTALAMOUNT' => ($header->total_amount + $net_amount),
                    'NETAMOUNT' => ($header->net_amount + $net_amount),
                    // 'TABLENO' => $request->table_no
                    'MEAL_STUB_COUNT' => $mealstub_count + 1,
                    'DISPLAYMONITOR' => 1
                ]);


            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Success'
            ]);
        }catch(\Exception $e){
            DB::rollback();
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }


    }


    // public function claim2(Request $request){
    //     try{

    //         DB::beginTransaction();

    //         $user       = Auth::user();
    //         $branch_id  = getUserBranchId();
    //         $outlet_id  = getUserOutletId();
    //         $device_id  = getDeviceId();

    //         // check 1st if there is an existing orderslip
    //         $header = $user->activeOrder();

    //         if( !$header ){
    //             DB::rollback();
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Please create orderslip 1st inorder to continue!'
    //             ]);
    //         }

    //         $osdStatus = 'T';

    //         // find if there is an existing mealstub
    //         // in the osd to avoid double claiming

    //         $osd = OrderSlipDetail::where('branch_id', $header->branch_id)
    //                 ->where('outlet_id', $header->outlet_id)
    //                 ->where('device_number', $header->device_no)
    //                 ->where('mealstub_serialnumber', $request->serial_number)
    //                 ->where('mealstub_product_id', $request->reference)
    //                 ->where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
    //                 ->where('status', '!=', 'V')
    //                 ->first();

    //         if( $osd ){
    //             DB::rollback();
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Mealstub was already added in your cart'
    //             ]);
    //         }

    //         $mealstub_count = 0;
    //         if(!is_null($header->mealstub_count)){
    //             $mealstub_count = $header->mealstub_count;
    //         }

    //         $line_number = OrderSlipDetail::getLastLineNumber(
    //                 $header->branch_id,
    //                 $header->orderslip_header_id,
    //                 $header->outlet_id,
    //                 $header->device_no ) + 1;

    //         $dev_no_mod = null; // device id which took and modify the order
    //         if($header->device_no == $device_id ){
    //             $dev_no_mod = null;
    //         }else{
    //             $dev_no_mod = $device_id;
    //         }

    //         // start here

    //         // get the info of the meal stub
    //         // a mealstub will alwaus be equivalent to 1
    //         // and theres no price since its paid in the ticketing outlet
    //         // and is no modifying in products

    //         $net_amount = 1 * 0;

    //         $mealstub = MealStub::where('branch_id', getUserBranchId())
    //         ->where('serial_number', $request->serial_number)
    //         ->where('type', 'MS')
    //         ->first();


    //         // dd($mealstub->components);

    //         $line_number = OrderSlipDetail::getLastLineNumber($branch_id,
    //                 $header->orderslip_header_id,
    //                 $header->outlet_id,
    //                 $header->device_no ) + 1;

    //         $main_line_number = $line_number;


    //         $sequence = OrderslipDetail::getMealstubNewSequence(
    //                             $branch_id,
    //                             $header->orderslip_header_id,
    //                             $request->reference,
    //                             $header->outlet_id, $header->device_no
    //                         );
    //         // dd($sequence);



    //         foreach($mealstub->components as $component){

    //         \Log::debug('component ' .$component);
    //             $osd2 = new OrderslipDetail;
    //             $osd2->orderslip_detail_id           = $osd2->getNewId($branch_id, $header->outlet_id, $header->device_no);
    //             $osd2->orderslip_header_id           = $header->orderslip_header_id;
    //             $osd2->branch_id                     = $header->branch_id;
    //             // $osd2->product_id                    = $component->default_product_id;
    //             $osd2->product_id                    = $component->product_id;
    //             $osd2->qty                           = $component->qty;
    //             $osd2->srp                           = 0;
    //             $osd2->amount                        = $component->qty * 0;
    //             $osd2->net_amount                    = $component->qty * 0;
    //             $osd2->is_modify                     = 0;
    //             $osd2->line_number                   = $line_number;
    //             $osd2->order_no                      = $main_line_number;
    //             $osd2->status                        = $osdStatus;
    //             $osd2->postmix_id                    = $component->postmix_id;
    //             $osd2->main_product_id               = null;
    //             $osd2->main_product_comp_id          = null;
    //             $osd2->old_comp_id                   = $component->default_product_id;
    //             $osd2->main_product_comp_qty         = null;
    //             $osd2->part_number                   = $component->partlocation->part_number;
    //             $osd2->encoded_date                  = now();
    //             $osd2->sequence                      = $sequence;
    //             $osd2->guest_no                      = null;
    //             $osd2->guest_type                    = null;
    //             $osd2->device_number                 = $header->device_no;
    //             $osd2->outlet_id                     = $header->outlet_id;
    //             $osd2->table_no                      = null;
    //             $osd2->kitchen_loc                   = $this->getKitchenLocation($component->product_id, $branch_id);
    //             $osd2->os_date                       = getClarionDate(now());
    //             $osd2->display_kds                   = 1;
    //             $osd2->dev_id_mod                    = $dev_no_mod;
    //             $osd2->mealstub_product_id           = $request->reference;
    //             $osd2->mealstub_serialnumber         = $request->serial_number;
    //             $osd2->pos_line_no                   = $line_number;

    //             $osd2->order_type                     = $request->is_take_out;

    //             $osd2->save();
    //             $net_amount += $osd2->net_amount;
    //             $line_number++;

    //             \Log::debug('saved' .$osd2);
    //         }

    //         // return response()->json([
    //         //     'success' => false,
    //         //     'status' => 201,
    //         //     'message' => 'Success'
    //         // ]);

    //         //save the total into OrderSlipHeader
    //         OrderSlipHeader::where('orderslip_header_id', $header->orderslip_header_id)
    //             ->where('branch_id',$header->branch_id)
    //             ->where('outlet_id',$header->outlet_id)
    //             // ->where('device_no', $user_device_id)
    //             ->where('device_no', $header->device_no)
    //             ->update([
    //                 'TOTALAMOUNT' => ($header->total_amount + $net_amount),
    //                 'NETAMOUNT' => ($header->net_amount + $net_amount),
    //                 // 'TABLENO' => $request->table_no
    //                 'MEAL_STUB_COUNT' => $mealstub_count + 1,
    //                 'DISPLAYMONITOR' => 1
    //             ]);


    //         DB::commit();
    //         return response()->json([
    //             'success' => true,
    //             'status' => 201,
    //             'message' => 'Success'
    //         ]);

    //     }catch(\Exception $e){
    //         DB::rollback();
    //         \Log::error($e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ],500);
    //     }
    // }


    public function claim2(Request $request){
        try{

            DB::beginTransaction();
            // DB::enableQueryLog();

            $user       = Auth::user();
            $branch_id  = getUserBranchId();
            $outlet_id  = getUserOutletId();
            $device_id  = getDeviceId();

            // check 1st if there is an existing orderslip
            $header = $user->activeOrder();

            if( !$header ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Please create orderslip first in order to continue!'
                ]);
            }

            $osdStatus = 'T';


            /**
             * if you want allow only one mealstub in an orderslip nothing else
             * set the env me
             */


            // only allow one mealstub in an orderslip nothing else in the cart
            // dd('ahay' . config('master_code'));
            // dd(config('ambulant.accept_multiple_mealstub'), config('ambulant.mealstub_mix_other_items'), config('ambulant.multiple_mealstub'));


            if(config('ambulant.accept_multiple_mealstub') == 0 && config('ambulant.mealstub_mix_other_items') == 0){
                \Log::debug( config('ambulant.accept_multiple_mealstub') . config('ambulant.mealstub_mix_other_items') );
                // check if theres an item in cart
                $osd = OrderSlipDetail::where('branch_id', $header->branch_id)
                    ->where('outlet_id', $header->outlet_id)
                    ->where('device_number', $header->device_no)
                    ->where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
                    ->where('status', '!=', 'V')
                    ->first();

                if($osd){
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'You have an item in your cart. Claim stub will be added if you have no item/s in your cart'
                    ]);
                }
            }else{
                 // don't add the mealstub if there are other items that are non-mealstub (food item)

                 if(config('ambulant.mealstub_mix_other_items') == 0){
                    $osd = OrderSlipDetail::where('branch_id', $header->branch_id)
                        ->where('outlet_id', $header->outlet_id)
                        ->where('device_number', $header->device_no)
                        ->where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
                        ->where('status', '!=', 'V')
                        ->whereNull('mealstub_serialnumber')
                        ->first();
                        \Log::debug( DB::getQueryLog() );

                        if($osd){
                            return response()->json([
                                'success' => false,
                                'status'  => 200,
                                'message' => 'You have an item in your cart. Claim stub cannot be mixed with non-mealstub items.'
                            ]);
                        }
                }


                // only one mealstub in orderslip
                if(config('ambulant.accept_multiple_mealstub') == 0){

                    if(!is_null($header->mealstub_count)){
                        // check if there's a mealstub already in cart
                        if($header->mealstub_count > 0){
                            return response()->json([
                                'success' => false,
                                'status'  => 200,
                                'message' => 'Only one claim stub per Order Slip'
                            ]);
                        }
                    }


                }
            }

            // \Log::debug( DB::getQueryLog() );

            // find if there is an existing mealstub
            // in the osd to avoid double claiming
            // per orderslipheader

            $osd = OrderSlipDetail::where('branch_id', $header->branch_id)
                    ->where('outlet_id', $header->outlet_id)
                    ->where('device_number', $header->device_no)
                    ->where('mealstub_serialnumber', $request->serial_number)
                    ->where('mealstub_product_id', $request->reference)
                    ->where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
                    ->where('status', '!=', 'V')
                    ->first();

            if( $osd ){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Claim stub was already added in your cart'
                ]);
            }

            $mealstub_count = 0;
            if(!is_null($header->mealstub_count)){
                $mealstub_count = $header->mealstub_count;
            }

            $line_number = OrderSlipDetail::getLastLineNumber(
                    $header->branch_id,
                    $header->orderslip_header_id,
                    $header->outlet_id,
                    $header->device_no ) + 1;

            $dev_no_mod = null; // device id which took and modify the order
            if($header->device_no == $device_id ){
                $dev_no_mod = null;
            }else{
                $dev_no_mod = $device_id;
            }

            // start here

            // get the info of the meal stub
            // a mealstub will alwaus be equivalent to 1
            // and theres no price since its paid in the ticketing outlet
            // and is no modifying in products

            $net_amount = 1 * 0;

            $mealstub = MealStub::where('branch_id', getUserBranchId())
            ->where('serial_number', $request->serial_number)
            ->where('type', 'MS')
            ->first();


            // dd($mealstub->components);

            $line_number = OrderSlipDetail::getLastLineNumber($branch_id,
                    $header->orderslip_header_id,
                    $header->outlet_id,
                    $header->device_no ) + 1;

            $main_line_number = $line_number;


            $sequence = OrderslipDetail::getMealstubNewSequence(
                                $branch_id,
                                $header->orderslip_header_id,
                                $request->reference,
                                $header->outlet_id, $header->device_no
                            );
            // dd($sequence);



            foreach($mealstub->components as $component){

            // \Log::debug('component ' .$component);
                $osd2 = new OrderslipDetail;
                $osd2->orderslip_detail_id           = $osd2->getNewId($branch_id, $header->outlet_id, $header->device_no);
                $osd2->orderslip_header_id           = $header->orderslip_header_id;
                $osd2->branch_id                     = $header->branch_id;
                // $osd2->product_id                    = $component->default_product_id;
                $osd2->product_id                    = $component->product_id;
                $osd2->qty                           = $component->qty;
                $osd2->srp                           = 0;
                $osd2->amount                        = $component->qty * 0;
                $osd2->net_amount                    = $component->qty * 0;
                $osd2->is_modify                     = 0;
                $osd2->line_number                   = $line_number;
                $osd2->order_no                      = $main_line_number;
                $osd2->status                        = $osdStatus;
                $osd2->postmix_id                    = $component->postmix_id;
                $osd2->main_product_id               = null;
                $osd2->main_product_comp_id          = null;
                $osd2->old_comp_id                   = $component->default_product_id;
                $osd2->main_product_comp_qty         = null;
                $osd2->part_number                   = $component->partlocation->part_number;
                $osd2->encoded_date                  = now();
                $osd2->sequence                      = $sequence;
                $osd2->guest_no                      = null;
                $osd2->guest_type                    = null;
                $osd2->device_number                 = $header->device_no;
                $osd2->outlet_id                     = $header->outlet_id;
                $osd2->table_no                      = null;
                $osd2->kitchen_loc                   = $this->getKitchenLocation($component->product_id, $branch_id);
                $osd2->os_date                       = getClarionDate(now());
                $osd2->display_kds                   = 1;
                $osd2->dev_id_mod                    = $dev_no_mod;
                $osd2->mealstub_product_id           = $request->reference;
                $osd2->mealstub_serialnumber         = $request->serial_number;
                $osd2->pos_line_no                   = $line_number;

                $osd2->order_type                     = $request->is_take_out;

                $osd2->save();
                $net_amount += $osd2->net_amount;
                $line_number++;

                // \Log::debug('saved' .$osd2);
            }

            // return response()->json([
            //     'success' => false,
            //     'status' => 201,
            //     'message' => 'Success'
            // ]);

            //save the total into OrderSlipHeader
            OrderSlipHeader::where('orderslip_header_id', $header->orderslip_header_id)
                ->where('branch_id',$header->branch_id)
                ->where('outlet_id',$header->outlet_id)
                // ->where('device_no', $user_device_id)
                ->where('device_no', $header->device_no)
                ->update([
                    'TOTALAMOUNT' => ($header->total_amount + $net_amount),
                    'NETAMOUNT' => ($header->net_amount + $net_amount),
                    // 'TABLENO' => $request->table_no
                    'MEAL_STUB_COUNT' => $mealstub_count + 1,
                    'DISPLAYMONITOR' => 1
                ]);


            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Success'
            ]);

        }catch(\Exception $e){
            DB::rollback();
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }

    // components
    public function mealComponents(Request $request){
        $components = null;
        if($request->group_by == 'mc'){
            $components = MealStubComponents::where('reference_id', $request->reference_id)
            ->where('is_modifiable', 1)
            ->get();

        }else if ($request->group_by == 'nmc') { // non modifiable component
            // $pl = $pl->postmixNoneModifiableComponents()->paginate();
            $components = MealStubComponents::where('reference_id', $request->reference_id)
            ->where('is_modifiable', 0)
            ->get();

        } else {
            return response()->json([
                'success'   => false,
                'status'    => 400,
                'message'   => 'No Group has been set'
            ]);
        }
        // dd();
        return response()->json([
            'success'   => true,
            'status'    => 200,
            'result'    => new MealstubComponentsResource($components),
            // 'base_url'  => url('/')
        ]);

    }

    public function mealMainItem(Request $request){

        $mealstub = MealStub::where('branch_id', getUserBranchId())
        ->where('serial_number', $request->srn)
        ->where('type', 'MS')
        ->first();

        if( !$mealstub ){
            return response()->json([
                'success' => false,
                'message' => 'Mealstub not found!'
            ]);
        }

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'base_url'  => url('/'),
            'result'    => new Collection(
                [
                    'mealstub_product_id' => $mealstub->reference_id,
                    // 'name'        => 'Claim stub ' .$request->srn,
                    'description' => 'Claim stub ' .$request->srn,
                    'short_code'  =>'Claim stub ' .$request->srn,
                    'price'       => 0,
                    'outlet_id'   => getUserOutletId(),
                    'postmix'     => 1,
                ])

        ]);


    }

    private function getKitchenLocation($product_id, $branch_id)
    {
        return SitePart::where('sitepart_id', $product_id)
            ->where('branch_id', $branch_id)
            ->first()->kitchen_loc;
    }


    /**
     *
     */
    public function modifyForm($ref){
        return view('pages.mealstub.modify', compact('ref'));
    }

    public function getInfo(Request $request){

        $mealstub = MealStub::findBySerial($request->ref);

        return response()->json([
            'success' => true,
            'data'  => [
                'mealstub'      => $mealstub,
                'components'    => $mealstub->components
            ]
        ]);
    }


    public function update(Request $request){
        try{

            DB::beginTransaction();
            $osh = OrderSlipHeader::orderIsPaid($request->header_id, $request->branch_id, $request->outlet_id, $request->device_id);
            if(!isset($osh)){
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'status'    => 404,
                    'message'   => 'Order Slip not found'
                ]);
            }

            if($osh->is_paid == 1){
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'status'    => 200,
                    'message'   => 'Ooops, you cannot modify the item when the order is already paid'
                ]);
            }

            OrderSlipDetail::where('orderslip_header_id', $request->header_id)
            ->where('branch_id', $request->branch_id)
            ->where('outlet_id', $request->outlet_id)
            ->where('device_number', $request->device_id)
            ->where('mealstub_serialnumber', $request->mealstub_number)
            ->update([
                'OSTYPE' => $request->ordertype
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Success'
            ]);

        }catch(\Exception $e){
            DB::rollback();
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }
}
