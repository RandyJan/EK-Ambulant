<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Table;
use App\OnDuty;
use App\Postmix;
use App\Mealstub;
use App\SitePart;
use App\GuestFile;
use Carbon\Carbon;
use App\TableHistory;
use App\Helpers\Helper;
use App\OrderslipTable;
use App\OrderSlipDetail;
use App\OrderSlipHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\TableCollection;
use App\Http\Resources\OSDEtailCollection;
use App\Http\Resources\Table as TableResource;

use App\Http\Resources\Postmix as PostmixResource;
use App\Services\BranchLastIssuedNumberServices as BLIN;
use App\Http\Resources\OrderSlipHeader as OrderSlipHeaderResource;

class OrderslipController extends Controller
{
    //

    public function showForm()
    {
        return view('pages.orderslip.create');
    }

    public function create(Request $request)
    {
        // dd( auth()->user()->activeOrder() );
        try {

            DB::beginTransaction();

            $user       = Auth::user();
            $osh        = new OrderSlipHeader;
            //$blin       = new BLIN($user->duty()->branch_id);
            $helper     = new Helper;
            $branch_id = getUserBranchId();
            $outlet_id = getUserOutletId();
            $device_id = getDeviceId();

            $active_order = $user->activeOrder();

            // $userOnduty = $user->duty();

            if ( $active_order ) {
                OrderSlipHeader::where('orderslip_header_id', $active_order->orderslip_header_id)
                    ->where('branch_id', getUserBranchId())
                    ->where('outlet_id', getUserOutletId())
                    // ->where('device_no', $user->device_no)
                    ->where('is_active', 1)
                    ->update([
                        'ISACTIVE' => 0
                    ]);
            }


            $osh->orderslip_header_id       = $osh->getNewId( $branch_id, $outlet_id, $device_id );
            $osh->branch_id                 = $branch_id;
            $osh->transaction_type_id       = 1;
            $osh->status                    = 'X'; //Pending

            $osh->created_at                = now();
            $osh->orig_invoice_date         = getClarionDate(now());
            $osh->encoded_date              = now();
            $osh->encoded_by                = $user->username;
            $osh->prepared_by               = $user->name;
            $osh->cce_name                  = $user->name;
            // $osh->total_hc                  = $request->headcount;
            $osh->outlet_id                 = $outlet_id;
            $osh->device_no                 = $device_id;
            $osh->is_active                 = 1;
            $osh->clarion_date              = getClarionDate(now());
            // $osh->os_no                     = $request->os_number;
            // $osh->branch_services_id        = $request->os_number;

            // $osh->os_no                     =  '000' . $user->device_no . '-' . $osh->orderslip_header_id;

            $osh->os_no                     = osNumberGenerator( $device_id, $osh->orderslip_header_id, $outlet_id);
            $osh->is_paid                   = 0;
            $osh->display_kds               = 1;
            $osh->save();

            DB::commit();
            return redirect()
                ->route('categories')
                ->with('success', 'You have successfully created a new Order Slip '.$osh->os_no );

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function createTable($os_id, $t_id)
    {
        try {

            DB::beginTransaction();

            $user = Auth::user();
            $helper = new Helper;

            $userOnDuty   = $user->duty();

            // find table by id
            $table = Table::where('id', $t_id)->first();
            $_table = new TableResource($table);
            // return $_table->guests;

            // save to guest file
            for ($i = 1; $i <= $table->guests; $i++) {
                $gf = new GuestFile;
                $gf->branch_id      = $userOnDuty->branch_id;
                $gf->outlet_id      = $userOnDuty->storeOutlet->outlet_id;
                $gf->device_no      = $userOnDuty->device_no;
                $gf->pos_number     = $user->branch;
                $gf->orderslip_no   = $user->activeOrder()->orderslip_header_id;
                $gf->table_no       = $t_id;
                $gf->guest_no       = $i;
                $gf->discount_id    = null;
                $gf->guest_name     = null;
                $gf->guest_type     = 'Regular';
                $gf->guest_address  = null;
                $gf->guest_tin      = null;
                $gf->with_order     = 0;
                $gf->clarion_date   = $helper->getClarionDate(now());
                $gf->clarion_time   = $helper->getClarionTime(now());
                $gf->save();
            }

            // update the tablefile
            Table::where('id', $t_id)
                ->update([
                    'STATUS2' => 1
                ]);

            // create ordersliptable record
            $ot = new OrderslipTable;
            $ot->branch_id      = $userOnDuty->branch_id;
            $ot->orderslip_id   = $user->activeOrder()->orderslip_header_id;
            $ot->table_id       = $t_id;
            $ot->table_number   = $t_id;
            $ot->created_at      = now();
            $ot->save();

            // add to table history
            $th = new TableHistory;
            $th->branch_id          = $userOnDuty->branch_id;
            $th->outlet_id          = $userOnDuty->storeOutlet->outlet_id;
            $th->device_no          = $userOnDuty->device_no;
            $th->orderslip_no       = $user->activeOrder()->orderslip_header_id;
            $th->table_no           = $t_id;
            $th->created            = now();
            $th->c_date             = $helper->getClarionDate(now());
            $th->c_time             = $helper->getClarionTime(now());
            $th->bus_datetime       = now();
            $th->actual_heads       = $_table->guests;
            $th->total_heads        = $user->activeOrder()->total_hc;
            $th->from_table_no      = 0;
            $th->status             = 1;
            $th->merged             = 0;
            $th->merge_id           = 0;
            $th->pos_number         = config('ambulant.pos_no');
            $th->save();

            DB::commit();
            return redirect('/')->with('success', 'You have successfully added a table');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function store(Request $request)
    {
        try {
            // init
            $helper     = new Helper;
            $osh        = new OrderSlipHeader;
            $user       = Auth::user();
            $isOnDuty   = $user->isOnDuty($helper->getClarionDate(now()));
            $branch_id  = getUserBranchId();
            $user_outlet_id = getUserOutletId();
            $user_device_id = getDeviceId();

            // $blin       = new BLIN($branch_id);

            $userOnduty = $user->duty();
            // begin transaction
            DB::beginTransaction();

            // check if this ambulant has an active sales order
            $aso = $user->activeOrder();

            $line_number = 0;
            if (is_null($aso)) {

                return response()->json([
                    'success'   => false,
                    'status'    => 500,
                    'message'   => 'NO ORDERSLIP'
                ]);
            } else {
                $osh = $aso;

                $line_number = OrderSlipDetail::getLastLineNumber($branch_id, $osh->orderslip_header_id, $user_outlet_id, $osh->device_no ) + 1;

                OrderSlipHeader::where('osnumber', $osh->osnumber)
                ->update([
                    'DISPLAYMONITOR' => 1,
                ]);

                if($osh->is_paid == 1){
                    DB::rollBack();
                    return response()->json([
                        'success'   => false,
                        'status'    => 200,
                        'message'   => 'Cannot add item to cart. Orderslip is already paid/completed'
                    ]);
                }
            }

            // kung config ay di nakamix ung non-mealstub sa mealstub items
            // check kng mealstub na item kung meron show an error na di pwede

            if(config('ambulant.mealstub_mix_other_items') == 0){
                $hasMealstub = OrderSlipDetail::where('branch_id', $branch_id)
                    ->where('outlet_id', $user_outlet_id)
                    ->where('device_number', $user_device_id)
                    ->where('orderslip_header_id', $osh->orderslip_header_id)
                    ->where('status', '!=', 'V')
                    ->where('mealstub_serialnumber', '!=', null)
                    ->first();

                    if($hasMealstub){
                        DB::rollBack();
                        return response()->json([
                            'success'   => false,
                            'status'    => 200,
                            'message'   => 'Cannot add item to cart. Claim stubs cannot be mixed with non-mealstub items.'
                        ]);
                    }

            }



            // postmix identifier
            $sp = SitePart::findByIdAndBranch($request->product_id, $branch_id);
            if ($sp->postmix == 1) {
                $postmix = $request->product_id;
            } else {
                $postmix = null;
            }

            $osdStatus = 'T';


            $net_amount = 0;

            $osd = new OrderSlipDetail;


            $dev_no_mod = null;

            if($osh->device_no == $user_device_id ){
                $dev_no_mod = null;
            }else{
                $dev_no_mod = $user_device_id;

            }
            // dd($osh->device_no);
            // DB::rollback();
            // dd($osd->getNewId($branch_id, $user_outlet_id, $osh->device_no));
            // $osd->orderslip_detail_id           = $osd->getNewId($branch_id, $user_outlet_id, $user_device_id); // ito ung orig david
            $osd->orderslip_detail_id           = $osd->getNewId($branch_id, $user_outlet_id, $osh->device_no); // ito ung orig david

            $osd->orderslip_header_id           = $osh->orderslip_header_id;
            $osd->branch_id                     = $branch_id;
            $osd->remarks                       = $request->instruction;
            $osd->order_type                    = $osd->getOrderTypeValue($request->is_take_out);
            $osd->product_id                    = $request->product_id;
            $osd->qty                           = $request->qty;
            $osd->srp                           = $request->price;
            $osd->amount                        = $request->qty * $request->price;
            $osd->net_amount                    = $request->qty * $request->price;
            //$osd->is_modify                     = 1;
            $osd->line_number                   = $line_number;
            $osd->order_no                      = $osd->line_number;
            $osd->status                        = $osdStatus;
            $osd->postmix_id                    = $postmix;
            $osd->main_product_id               = $request->main_product_id;
            $osd->main_product_comp_id          = $request->main_product_component_id;
            $osd->old_comp_id                   = $request->main_product_component_id;
            $osd->main_product_comp_qty         = $request->main_product_component_qty;
            $osd->part_number                   = $request->part_number;
            $osd->encoded_date                  = now();
            $osd->sequence                      = $osd->getNewSequence(
                                                    $branch_id,
                                                    $osh->orderslip_header_id,
                                                    $request->product_id,
                                                    $user_outlet_id,
                                                    $user_device_id
                                                );

            $osd->guest_no                      = $request->guest_no;
            $osd->guest_type                    = $request->guest_type;
            $osd->device_number                 = $osh->device_no;
            $osd->outlet_id                     = $user_outlet_id;
            $osd->table_no                      = $request->table_no;
            $osd->kitchen_loc                   = $this->getKitchenLocation($request->product_id, $branch_id);
            $osd->os_date                       = getClarionDate(now());
            $osd->display_kds                   = 1;
            $osd->dev_id_mod                    = $dev_no_mod;
            $osd->pos_line_no                   = $line_number;





            $osd->save();

            $net_amount += $osd->net_amount;
            $line_number++;


            if (isset($request->others)) {

                foreach ($request->others as $other) {
                    $other = (object) $other;

                    if ($other->qty != 0) {

                        for ($i=0; $i < $other->qty; $i++) {
                            $osd2 = new OrderSlipDetail;
                            // $osd2->orderslip_detail_id           = $osd2->getNewId($branch_id, $user_outlet_id, $user_device_id);
                            $osd2->orderslip_detail_id           = $osd2->getNewId($branch_id, $user_outlet_id, $osh->device_no);

                            $osd2->orderslip_header_id           = $osh->orderslip_header_id;
                            $osd2->branch_id                     = $branch_id;
                            // $osd2->remarks                       = $request->instruction;
                            // $osd2->remarks                       = $other->instructions;
                            $osd2->order_type                    = $osd2->getOrderTypeValue($request->is_take_out);
                            $osd2->product_id                    = $other->product_id;
                            $osd2->srp                           = $other->price;
                            // $osd2->qty                           = $other->qty;
                            // $osd2->amount                        = $other->qty * $other->price;
                            // $osd2->net_amount                    = $other->qty * $other->price;
                            $osd2->qty                           = 1;
                            $osd2->amount                        = 1 * $other->price;
                            $osd2->net_amount                    = 1 * $other->price;
                            $osd2->is_modify                     = 1;
                            $osd2->line_number                   = $line_number;
                            $osd2->order_no                      = $osd->line_number;
                            $osd2->status                        = $osdStatus;
                            $osd2->postmix_id                    = $postmix;
                            $osd2->main_product_id               = $other->main_product_id;
                            $osd2->main_product_comp_id          = $other->main_product_component_id;
                            $osd2->old_comp_id                   = $other->main_product_component_id;
                            $osd2->main_product_comp_qty         = 1;
                            $osd2->part_number                   = $other->part_number;
                            $osd2->encoded_date                  = now();
                            $osd2->sequence                      = $osd->sequence;
                            $osd2->guest_no                      = $request->guest_no;
                            $osd2->guest_type                    = $request->guest_type;

                            $osd2->device_number                 = $osh->device_no;
                            $osd2->outlet_id                     = $user_outlet_id;
                            $osd2->table_no                      = $request->table_no;
                            $osd2->kitchen_loc                   = $this->getKitchenLocation($other->product_id, $branch_id);
                            $osd2->os_date                       = getClarionDate(now());
                            $osd2->display_kds                   = 1;
                            // $osd2->branch_services_id             = $osh->os_number;
                            // $osd2->display_kds                   = 1;

                            $osd2->dev_id_mod                     = $dev_no_mod;
                            $osd2->pos_line_no                    = $line_number;

                            $osd2->save();
                            $net_amount += $osd2->net_amount;
                            $line_number++;


                        }

                    }

                    if (isset($other->others)) {
                        foreach ($other->others as $other2) {
                            $other2 = (object) $other2;
                            for ($i=0; $i < $other2->qty; $i++) {

                                $osd3 = new OrderSlipDetail;
                                //$osd3->orderslip_detail_id           = $osd3->getNewId($branch_id, $user_outlet_id, $user_device_id);
                                $osd3->orderslip_detail_id           = $osd3->getNewId($branch_id, $user_outlet_id, $osh->device_no);

                                $osd3->orderslip_header_id           = $osh->orderslip_header_id;
                                $osd3->branch_id                     = $branch_id;
                                // $osd3->remarks                       = $other2->instructions;
                                $osd3->order_type                    = $osd3->getOrderTypeValue($request->is_take_out);
                                $osd3->product_id                    = $other2->product_id;
                                $osd3->srp                           = $other2->price;
                                // $osd3->qty                           = $other2->qty;
                                // $osd3->amount                        = $other2->qty * $other2->price;
                                // $osd3->net_amount                    = $other2->qty * $other2->price;
                                $osd3->qty                           = 1;
                                $osd3->amount                        = 1 * $other2->price;
                                $osd3->net_amount                    = 1 * $other2->price;
                                $osd3->is_modify                     = 1;
                                $osd3->line_number                   = $line_number;
                                $osd3->order_no                      = $osd->line_number;
                                $osd3->status                        = $osdStatus;
                                $osd3->postmix_id                    = $postmix;
                                $osd3->main_product_id               = $other2->main_product_id;
                                $osd3->main_product_comp_id          = $other2->main_product_component_id;
                                $osd3->old_comp_id                   = $other2->main_product_component_id;
                                $osd3->main_product_comp_qty         = 1;
                                $osd3->part_number                   = $other2->part_number;
                                $osd3->encoded_date                  = now();
                                $osd3->sequence                      = $osd->sequence;
                                $osd3->guest_no                      = $request->guest_no;
                                $osd3->guest_type                    = $request->guest_type;

                                $osd3->device_number                 = $osh->device_no;
                                $osd3->outlet_id                     = $user_outlet_id;
                                $osd3->table_no                      = $request->table_no;
                                $osd3->kitchen_loc                   = $this->getKitchenLocation($other2->product_id, $branch_id);
                                $osd3->os_date                       = getClarionDate(now());
                                $osd3->display_kds                   = 1;
                                // $osd3->branch_services_id            = $osh->os_number;
                                // $osd3->display_kds                   = 1;
                                $osd3->dev_id_mod                    = $dev_no_mod;
                                $osd3->pos_line_no                   = $line_number;
                                $osd3->save();
                                $net_amount += $osd3->net_amount;
                                $line_number++;
                            }

                        }
                    }
                }
            }


            // dd('deym');
            // saving none modifiable component
            if (isset($request->none_modifiable_component)) {
                foreach ($request->none_modifiable_component as $nmc) {
                    $nmc = (object) $nmc;
                    $_osd = new OrderSlipDetail;
                    // $_osd->orderslip_detail_id           = $_osd->getNewId($branch_id, $user_outlet_id, $user_device_id);;
                    $_osd->orderslip_detail_id           = $_osd->getNewId($branch_id, $user_outlet_id, $osh->device_no);;

                    $_osd->orderslip_header_id           = $osh->orderslip_header_id;
                    $_osd->branch_id                     = $branch_id;
                    // $_osd->remarks                       = $nmc->instruction;
                    $_osd->order_type                    = $_osd->getOrderTypeValue($request->is_take_out);
                    $_osd->product_id                    = $nmc->product_id;
                    $_osd->qty                           = ($nmc->quantity * $osd->qty);
                    $_osd->srp                           = 0;
                    $_osd->amount                        = $_osd->qty * $_osd->srp;
                    $_osd->net_amount                    = $_osd->qty * $_osd->srp;
                    $_osd->is_modify                     = 0;
                    $_osd->line_number                   = $line_number;
                    $_osd->order_no                      = $osd->line_number;
                    $_osd->status                        = $osdStatus;
                    $_osd->postmix_id                    = $postmix;
                    $_osd->main_product_id               = $osd->product_id;
                    $_osd->main_product_comp_id          = $_osd->product_id;
                    $_osd->old_comp_id                   = $_osd->product_id;
                    $_osd->main_product_comp_qty         = $_osd->qty;
                    $_osd->part_number                   = $nmc->product_partno;
                    $_osd->encoded_date                  = now();
                    $_osd->sequence                      = $osd->sequence;
                    $_osd->guest_no                      = $request->guest_no;
                    $_osd->guest_type                    = $request->guest_type;

                    $_osd->device_number                 = $osh->device_no;
                    $_osd->outlet_id                     = $user_outlet_id;
                    $_osd->table_no                      = $request->table_no;
                    $_osd->kitchen_loc                   = $this->getKitchenLocation($nmc->product_id, $branch_id);
                    $_osd->os_date                       = getClarionDate(now());
                    $_osd->display_kds                   = 1;
                    // $_osd->branch_services_id            = $osh->os_number;
                    // $_osd->display_kds                    = 1;
                    $_osd->dev_id_mod                    = $dev_no_mod;
                    $_osd->pos_line_no                   = $line_number;
                    $_osd->save();
                    $line_number++;
                }
            }

            // dd('sugoi');
            //save the total into OrderSlipHeader
            OrderSlipHeader::where('orderslip_header_id', $osh->orderslip_header_id)
                ->where('branch_id', $branch_id)
                ->where('outlet_id', $user_outlet_id)
                // ->where('device_no', $user_device_id)
                ->where('device_no', $osh->device_no)
                ->update([
                    'TOTALAMOUNT' => ($osh->total_amount + $net_amount),
                    'NETAMOUNT' => ($osh->net_amount + $net_amount)
                    // 'TABLENO' => $request->table_no
                ]);

            // commit all changes
            DB::commit();

            // \Session::flush('success','Successfully added item on cart.');
            return response()->json([
                'success'   => true,
                'status'    => 201,
                'message'   => 'Success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success'   => false,
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request){

        try{

            // begin transaction
            DB::beginTransaction();

            $jsonOjb    = json_decode($request->data);
            $user_branch_id  = getUserBranchId();
            $user_outlet_id = getUserOutletId();
            $user_device_id = getDeviceId();

            $osh        = OrderSlipHeader::where('branch_id', $user_branch_id)
                                ->where('outlet_id', $user_outlet_id)
                                ->where('orderslip_header_id', $jsonOjb->header_id)
                                ->where('device_no', $jsonOjb->device_id)
                                ->first();

            if(!$osh){
                return response()->json([
                    'success'   => false,
                    'status'    => 500,
                    'message'   => 'Order slip not found'
                ]);
            }

            if($osh->is_paid == 1){
                return response()->json([
                    'success'   => false,
                    'status'    => 500,
                    'message'   => 'Ooops, you cannot modify the item when the order is already paid'
                ]);
            }



            OrderSlipHeader::where('osnumber', $osh->osnumber)
            ->update([
                'DISPLAYMONITOR' => 1,
            ]);

            $old_osd = OrderSlipDetail::where('orderslip_header_id', $jsonOjb->header_id)
            ->where('branch_id',$user_branch_id)
            ->where('outlet_id',$jsonOjb->outlet_id)
            ->where('device_number', $jsonOjb->device_id)
            ->where('main_product_id', $jsonOjb->main_product_id)
            ->where('sequence', $jsonOjb->sequence);

            // if( $old_osd->get() );
            if(!$old_osd->get()){
                return response()->json([
                    'success'   => false,
                    'status'    => 404,
                    'message'   => 'Item on cart does not exist'
                ]);
            }

            $prev_amount_to_deduct =0;
            $prev_netamount_to_deduct = 0;

            foreach($old_osd->get() as $item){
                $prev_amount_to_deduct += $item->amount;
                $prev_netamount_to_deduct += $item->net_amount;
            }

            // remove all items in detail
            $old_osd->delete();

            // $line_number = 1;
            $line_number = OrderSlipDetail::getLastLineNumber($user_branch_id,
                $osh->orderslip_header_id,
                $jsonOjb->outlet_id,
                $jsonOjb->device_id ) + 1;

            // postmix identifier
            $sp = SitePart::findByIdAndBranch($jsonOjb->data->product_id, $user_branch_id);
            if($sp->postmix == 1){
                $postmix = $request->product_id;
            }else{
                $postmix = null;
            }
            // end of postmix identifier

            // // save new items detail
            $orders = $jsonOjb->data;
            $net_amount = 0;

            // dd('a');

            $dev_no_mod = null; // device id which took and modify the order

            if($osh->device_no == $user_device_id ){
                $dev_no_mod = null;
            }else{
                $dev_no_mod = $user_device_id;
            }

            // save each of item in slipdetails
            $osd = new OrderSlipDetail;
            $osd->orderslip_detail_id           = $osd->getNewId($user_branch_id, $user_outlet_id, $osh->device_no);            ;
            $osd->orderslip_header_id           = $jsonOjb->header_id;
            $osd->branch_id                     = $user_branch_id;
            $osd->remarks                       = $orders->instruction;
            $osd->order_type                    = $osd->getOrderTypeValue($orders->is_take_out);
            $osd->product_id                    = $orders->product_id;
            $osd->qty                           = $orders->qty;
            $osd->srp                           = $orders->price;
            $osd->amount                        = $orders->qty * $orders->price;
            $osd->net_amount                    = $orders->qty * $orders->price;
            $osd->line_number                   = $line_number;
            $osd->order_no                      = $osd->line_number;
            $osd->postmix_id                    = $postmix;
            $osd->status                        = 'T';
            $osd->postmix_id                    = $orders->main_product_id;
            $osd->main_product_id               = $orders->main_product_id;
            $osd->main_product_comp_id          = $orders->main_product_component_id;
            $osd->main_product_comp_qty         = $orders->main_product_component_qty;
            $osd->part_number                   = $orders->part_number;
            $osd->encoded_date                  = now();
            $osd->sequence                      = $osd->getNewSequence(
                                                    $user_branch_id,
                                                    $jsonOjb->header_id,
                                                    $orders->product_id,
                                                    $user_outlet_id,
                                                    $osh->device_no
                                                 );
            // $osd->guest_no                      = $orders->guest_no;
            // $osd->guest_type                    = $orders->guest_type;
            $osd->device_number                 = $osh->device_no;
            $osd->outlet_id                     = $user_outlet_id;
            $osd->kitchen_loc                   = $this->getKitchenLocation($orders->product_id, $user_branch_id);
            $osd->os_date                       = getClarionDate(now());
            $osd->display_kds                   = 1;
            $osd->dev_id_mod                    = $dev_no_mod;

            $osd->save();
            $net_amount += $osd->net_amount;
            $line_number++;

            if( isset($orders->others) ){
                foreach( $orders->others as $other){

                    $other = (object)$other;

                    if($other->qty != 0){
                        for ($i=0; $i < $other->qty; $i++) {
                            $osd2 = new OrderSlipDetail;
                            $osd2->orderslip_detail_id           = $osd2->getNewId($user_branch_id, $user_outlet_id, $osh->device_no);                            ;
                            $osd2->orderslip_header_id           = $jsonOjb->header_id;
                            $osd2->branch_id                     = $user_branch_id;
                            $osd2->remarks                       = $orders->instruction;
                            $osd2->order_type                    = $osd2->getOrderTypeValue($orders->is_take_out);
                            $osd2->product_id                    = $other->product_id;
                            $osd2->srp                           = $other->price;
                            $osd2->qty                           = 1;
                            // $osd2->qty                           = $other->qty;
                            // $osd2->amount                        = $other->qty * $other->price;
                            // $osd2->net_amount                    = $other->qty * $other->price;
                            $osd2->amount                        = 1 * $other->price;
                            $osd2->net_amount                    = 1 * $other->price;
                            $osd2->is_modify                     = 1;
                            $osd2->line_number                   = $line_number;
                            $osd2->order_no                      = $osd->line_number;
                            $osd2->postmix_id                    = $postmix;
                            $osd2->status                        = 'T';
                            $osd2->postmix_id                    = $other->main_product_id;
                            $osd2->main_product_id               = $other->main_product_id;
                            $osd2->main_product_comp_id          = $other->main_product_component_id;
                            $osd2->main_product_comp_qty         = 1;
                            $osd2->part_number                   = $other->part_number;
                            $osd2->encoded_date                  = now();
                            $osd2->sequence                      = $osd->sequence;
                            // $osd2->guest_no                      = $orders->guest_no;
                            // $osd2->guest_type                    = $orders->guest_type;
                            $osd2->device_number                 = $osh->device_no;
                            $osd2->outlet_id                     = $user_outlet_id;
                            $osd2->kitchen_loc                   = $this->getKitchenLocation($orders->product_id, $user_branch_id);
                            $osd2->os_date                       = getClarionDate(now());
                            $osd2->display_kds                   = 1;
                            $osd2->dev_id_mod                    = $dev_no_mod;

                            $osd2->save();
                            $net_amount += $osd2->net_amount;
                            $line_number++;
                        }

                    }

                    if( isset($other->others) ){
                        foreach( $other->others as $other2){
                            $other2 = (object)$other2;

                            for ($i=0; $i < $other2->qty; $i++) {
                                $osd3 = new OrderSlipDetail;
                                $osd3->orderslip_detail_id           = $osd3->getNewId($user_branch_id, $user_outlet_id, $osh->device_no);                            ;
                                $osd3->orderslip_header_id           = $jsonOjb->header_id;
                                $osd3->branch_id                     = $user_branch_id;
                                $osd3->remarks                       = $request->instruction;
                                $osd3->order_type                    = $osd3->getOrderTypeValue($orders->is_take_out);
                                $osd3->product_id                    = $other2->product_id;
                                $osd3->srp                           = $other2->price;
                                // $osd3->qty                           = $other2->qty;
                                // $osd3->amount                        = $other2->qty * $other2->price;
                                // $osd3->net_amount                    = $other2->qty * $other2->price;
                                $osd3->qty                           = 1;
                                $osd3->amount                        = 1 * $other2->price;
                                $osd3->net_amount                    = 1 * $other2->price;
                                $osd3->is_modify                     = 1;
                                $osd3->line_number                   = $line_number;
                                $osd3->order_no                      = $osd->line_number;
                                $osd3->postmix_id                    = $postmix;
                                $osd3->status                        = 'T';
                                $osd3->postmix_id                    = $other2->main_product_id;
                                $osd3->main_product_id               = $other2->main_product_id;
                                $osd3->main_product_comp_id          = $other2->main_product_component_id;
                                $osd3->main_product_comp_qty         = 1;
                                $osd3->part_number                   = $other2->part_number;
                                $osd3->encoded_date                  = now();
                                $osd3->sequence                      = $osd->sequence;
                                // $osd3->guest_no                      = $orders->guest_no;
                                // $osd3->guest_type                    = $orders->guest_type;
                                $osd3->device_number                 = $osh->device_no;
                                $osd3->outlet_id                     = $user_outlet_id;
                                $osd3->kitchen_loc                   = $this->getKitchenLocation($orders->product_id, $user_branch_id);
                                $osd3->os_date                       = getClarionDate(now());
                                $osd3->display_kds                   = 1;
                                $osd3->dev_id_mod                    = $dev_no_mod;

                                $osd3->save();
                                $net_amount += $osd3->net_amount;
                                $line_number++;
                            }
                        }
                    }

                }
            }

            // saving none modifiable component
            if( isset($jsonOjb->nmc) ){
                foreach( $jsonOjb->nmc as $nmc){
                    $_osd = new OrderSlipDetail;
                    $_osd->orderslip_detail_id           = $_osd->getNewId($user_branch_id, $user_outlet_id, $osh->device_no);
                    $_osd->orderslip_header_id           = $jsonOjb->header_id;
                    $_osd->branch_id                     = $user_branch_id;
                    // $_osd->remarks                       = $osd->remarks;
                    $_osd->order_type                    = $_osd->getOrderTypeValue($orders->is_take_out);
                    $_osd->product_id                    = $nmc->product_id;
                    $_osd->qty                           = ($nmc->quantity * $osd->qty);
                    $_osd->srp                           = 0;
                    $_osd->amount                        = $_osd->qty * $_osd->srp;
                    $_osd->net_amount                    = $_osd->qty * $_osd->srp;
                    $_osd->is_modify                     = 0;
                    // $_osd->line_number                   = $line_number;
                    $_osd->order_no                      = $osd->line_number;
                    $_osd->postmix_id                    = $postmix;
                    $_osd->status                        = 'T';
                    $_osd->postmix_id                    = $osd->product_id;
                    $_osd->main_product_id               = $osd->product_id;
                    $_osd->main_product_comp_id          = $_osd->product_id;
                    $_osd->main_product_comp_qty         = $_osd->qty;
                    $_osd->part_number                   = $nmc->product_partno;
                    $_osd->encoded_date                  = now();
                    $_osd->sequence                      = $osd->sequence;
                    // $_osd->guest_no                      = $orders->guest_no;
                    // $_osd->guest_type                    = $orders->guest_type;
                    $_osd->device_number                 = $osh->device_no;
                    $_osd->outlet_id                     = $user_outlet_id;
                    $_osd->kitchen_loc                   = $this->getKitchenLocation($orders->product_id, $user_branch_id);
                    $_osd->os_date                       = getClarionDate(now());
                    $_osd->display_kds                   = 1;
                    $_osd->dev_id_mod                    = $dev_no_mod;

                    $_osd->save();
                }
            }


            /*
            DB::statement(DB::raw('set @row:=0'));

            OrderSlipDetail::where('orderslip_header_id', $jsonOjb->header_id)
            ->where('branch_id',$user_branch_id)
            ->where('outlet_id',$jsonOjb->outlet_id)
            ->where('device_number', $jsonOjb->device_id)
            ->update([
                'LINE_NO'     => DB::raw('@row:=@row+1 as rowNumber')
            ]);
            */


            // dd($osh->total_amount,$net_amount, ($osh->total_amount - $prev_amount_to_deduct) +$net_amount);

            //save the total into OrderSlipHeader
            OrderSlipHeader::where('orderslip_header_id', $jsonOjb->header_id)
                ->where('branch_id', $user_branch_id)
                ->where('outlet_id', $user_outlet_id)
                ->where('device_no', $osh->device_no)
                ->update([
                    'TOTALAMOUNT' => ($osh->total_amount - $prev_amount_to_deduct) + $net_amount,
                    'NETAMOUNT'   => ($osh->net_amount - $prev_amount_to_deduct) + $net_amount,

                ]);


            $osds = OrderSlipDetail::where('orderslip_header_id', $jsonOjb->header_id)
            ->where('branch_id',$user_branch_id)
            ->where('outlet_id',$jsonOjb->outlet_id)
            ->where('device_number', $jsonOjb->device_id)
            ->get();

            $osd_line_no=0;
            foreach($osds as $osd){
                $osd_line_no += 1;
                OrderSlipDetail::where('orderslip_header_id', $jsonOjb->header_id)
                    ->where('branch_id',$user_branch_id)
                    ->where('outlet_id',$jsonOjb->outlet_id)
                    ->where('device_number', $jsonOjb->device_id)
                    ->where('orderslip_detail_id', $osd->orderslip_detail_id)
                    ->update([
                        'LINE_NO'     => $osd_line_no,
                        'POSLINENO'   => $osd_line_no
                    ]);

            }

            // commit all changes
            DB::commit();

            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => 'Success'
            ]);

        }catch( \Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success'   => false,
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
        }
    }

    private function getKitchenLocation($product_id, $branch_id)
    {
        return SitePart::where('sitepart_id', $product_id)
            ->where('branch_id', $branch_id)
            ->first()->kitchen_loc;
    }

    public function changeOs($id, $device_id, Request $request)
    {
        // dd($id, $device_id);
        try {

            $user           = Auth::user();
            $active_order   = $user->activeOrder();
            $user_branch_id = getUserBranchId();
            $user_outlet_id = getUserOutletId();
            $user_device_id = getDeviceId();
            // $prev_os     = $user->activeOrder()->orderslip_header_id;

            // $result = OrderSlipHeader::where('orderslip_header_id', $id)
            //     ->where('branch_id', $user_branch_id)
            //     ->where('outlet_id', $user_outlet_id)
            //     ->where('device_no', $device_id)
            //     ->first();

            // if (is_null($result)) {
            //     abort(404);
            // }

            if(!is_null($user->activeOrder())){
                // inactive previous
                OrderSlipHeader::where('orderslip_header_id', $active_order->orderslip_header_id)
                    ->where('branch_id', $user_branch_id)
                    ->where('outlet_id', $user_outlet_id)
                    ->where('device_no', $active_order->device_no)
                    ->update([
                        'ISACTIVE' => 0
                ]);
            }

            // set active selected os
            OrderSlipHeader::where('orderslip_header_id', $id)
                ->where('branch_id',$user_branch_id)
                ->where('outlet_id', $user_outlet_id)
                ->where('device_no', $device_id)
                ->update([
                    'ISACTIVE'  => 1,
                    // 'DEVICENO'  => $user_device_id,
                    'ENCODEDBY' => $user->username
                ]);


            return back()->with('success', 'You have successfully change Orderslip');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success'   => false,
                    'status'    => 500,
                    'message'   => $e->getMessage()
                ]);
            }
            abort(500);
        }
    }


    public function printRequest()
    {

        try {

            // begin transaction
            DB::beginTransaction();

            $user       = Auth::user();
            $osh        = new OrderSlipHeader;
            // $blin       = new BLIN($user->duty()->branch_id);
            $helper     = new Helper;



            if ($user->activeOrder()) {

                OrderSlipHeader::where('orderslip_header_id', $user->activeOrder()->orderslip_header_id)
                    ->where('branch_id', $user->branch)
                    ->where('outlet_id', $user->outlet_id)
                    ->where('device_no', $user->device_no)
                    ->update([
                        'DRNUMBER' => 1
                    ]);
            }else{
                return response()->json([
                    'success'   => false,
                    'status'    => 200,
                    'message'   => 'There is no Active order slip'
                ]);
            }

            // commit all changes
            DB::commit();

            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => 'Success! The bill was sent to assembler for printing'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'success'   => false,
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
            abort(500);
        }
    }

    public function getTable(){
        $user = Auth::user();

        if( is_null($user->activeOrder()) ){
            return response()->json([
                'success'   => false,
                'status'    => 400,
                'result'    => 'error'
            ]);
        }

        if( $user->activeOrder()->tables()->count() <= 0 ){
            return response()->json([
                'success'   => false,
                'status'    => 400,
                'result'    => 'error'
            ]);
        }

        $tables = $user->activeOrder()->tables();

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'result'    => 'success',
            'data' => $tables
        ]);
    }

    public function printComponents(){

        $user=Auth::user();
        $active = $user->activeOrder();

        $orderslipdetail= OrderSlipDetail::where('orderslip_header_id',$active->orderslip_header_id)
                        ->get();
        $main_prod = OrderSlipDetail::where('main_product_id',$active->main_product_id)
        ->get();
LOG::info($main_prod);
        return view('/pages/orderslip/print_os',compact('orderslipdetail','main_prod'));
    }





    public function checkOsPaid(Request $request){

        $osh = OrderSlipHeader::orderIsPaid($request->header_id, $request->branch_id, $request->outlet_id, $request->device_id);
        if(!isset($osh)){
            return response()->json([
                'success'   => false,
                'status'    => 404,
                'message'   => 'Order Slip not found'
            ]);
        }

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'data'      => $osh->is_paid
        ]);

    }
    public function removeSelectedItem(){

            try {
                DB::beginTransaction();


                $osh = OrderSlipHeader::orderIsPaid(request()->header_id, request()->branch_id, request()->outlet_id, request()->device_id);
                if(!isset($osh)){
                    DB::rollBack();
                    return response()->json([
                        'success'   => false,
                        'status'    => 404,
                        'message'   => 'Order Slip not found'
                    ]);
                }

                if($osh->is_paid == 1){
                    DB::rollBack();
                    return response()->json([
                        'success'   => false,
                        'status'    => 200,
                        'message'   => 'Ooops, you cannot modify the item when the order is already paid'
                    ]);
                }
                if(request()->mealstub_product_id == null){
                    $osd = OrderSlipDetail::where('BRANCHID', request()->branch_id)
                        ->where('ORDERSLIPNO', request()->header_id)

                        ->where('MAIN_PRODUCT_ID', request()->product_id)
                        ->where('DEVICENO', request()->device_id)
                        ->where('OUTLETID', request()->outlet_id)
                        ->where('SEQUENCE', request()->sequence)
                        ->get();

                    $discount_amount_to_be_deduct = 0;
                    $amount_to_be_deduct = 0;
                    $net_amount_to_be_deduct = 0;
                    foreach( $osd as $key => $d){
                        $amount_to_be_deduct += $d->AMOUNT;
                        $net_amount_to_be_deduct += $d->NETAMOUNT;
                    }

                    $os = OrderSlipHeader::where('branch_id', request()->branch_id)
                        ->where('orderslip_header_id', request()->header_id)
                        ->where('device_no', request()->device_id)
                        ->where('outlet_id', request()->outlet_id)
                        ->first();

                    OrderSlipHeader::where('branch_id', request()->branch_id)
                        ->where('orderslip_header_id', request()->header_id)
                        ->where('device_no', request()->device_id)
                        ->where('outlet_id', request()->outlet_id)
                        ->update([
                            'TOTALAMOUNT'       => $os->TOTALAMOUNT - $amount_to_be_deduct,
                            'NETAMOUNT'         => $os->NETAMOUNT - $net_amount_to_be_deduct,
                            'DISPLAYMONITOR'    => 2
                    ]);

                    OrderSlipDetail::where('branch_id', request()->branch_id)
                    ->where('orderslip_header_id', request()->header_id)

                    ->where('main_product_id', request()->product_id)
                    ->where('device_number', request()->device_id)
                    ->where('outlet_id', request()->outlet_id)
                    ->where('sequence', request()->sequence)
                    ->where('status', '!=', 'T')
                    ->update([
                        'STATUS' => 'V',
                        'DISPLAYMONITOR' => 1
                        ]);

                    $osd_delete = OrderSlipDetail::where('branch_id', request()->branch_id)
                    ->where('orderslip_header_id', request()->header_id)

                    ->where('main_product_id', request()->product_id)
                    ->where('device_number', request()->device_id)
                    ->where('outlet_id', request()->outlet_id)
                    ->where('sequence', request()->sequence)
                    ->where('status', 'T')
                    ->delete();

                    // if there are deleted rows then reset the line no and the pos line no
                    if( $osd_delete > 0){
                        // get the all the orderslip within the branch, outlet and device
                        $osds = OrderSlipDetail::where('branch_id', request()->branch_id)
                        ->where('orderslip_header_id', request()->header_id)
                        ->where('device_number', request()->device_id)
                        ->where('outlet_id', request()->outlet_id)
                        ->get();

                        // reset the line no
                        $line_number = 0;
                        foreach( $osds as $item){
                            $line_number +=1;
                            OrderSlipDetail::where('branch_id', request()->branch_id)
                            ->where('orderslip_header_id', request()->header_id)
                            ->where('device_number', request()->device_id)
                            ->where('outlet_id', request()->outlet_id)
                            ->where('orderslip_detail_id', $item->orderslip_detail_id)
                            ->update([
                                'LINE_NO' => $line_number,
                                'POSLINENO' =>$line_number,
                            ]);
                        }
                    }



                }elseif(request()->mealstub_product_id != null){
                    $osd = OrderSlipDetail::where('BRANCHID', request()->branch_id)
                        ->where('ORDERSLIPNO', request()->header_id)

                        ->where('MAIN_PRODUCT_ID', request()->product_id)
                        ->where('DEVICENO', request()->device_id)
                        ->where('OUTLETID', request()->outlet_id)
                        ->where('SEQUENCE', request()->sequence)
                        ->where('MEAL_STUB_PRODUCT_ID', request()->mealstub_product_id)
                        ->get();

                    $discount_amount_to_be_deduct = 0;
                    $amount_to_be_deduct = 0;
                    $net_amount_to_be_deduct = 0;
                    foreach( $osd as $key => $d){
                        $amount_to_be_deduct += $d->AMOUNT;
                        $net_amount_to_be_deduct += $d->NETAMOUNT;
                    }

                    $os = OrderSlipHeader::where('branch_id', request()->branch_id)
                        ->where('orderslip_header_id', request()->header_id)
                        ->where('device_no', request()->device_id)
                        ->where('outlet_id', request()->outlet_id)
                        ->first();

                    OrderSlipHeader::where('branch_id', request()->branch_id)
                        ->where('orderslip_header_id', request()->header_id)
                        ->where('device_no', request()->device_id)
                        ->where('outlet_id', request()->outlet_id)
                        ->update([
                            'TOTALAMOUNT'       => $os->TOTALAMOUNT - $amount_to_be_deduct,
                            'NETAMOUNT'         => $os->NETAMOUNT - $net_amount_to_be_deduct,
                            'DISPLAYMONITOR'    => 2,
                            'MEAL_STUB_COUNT'   => $os->MEAL_STUB_COUNT - 1
                    ]);


                    OrderSlipDetail::where('branch_id', request()->branch_id)
                        ->where('orderslip_header_id', request()->header_id)

                        ->where('device_number', request()->device_id)
                        ->where('outlet_id', request()->outlet_id)
                        ->where('sequence', request()->sequence)
                        ->where('MEAL_STUB_PRODUCT_ID', request()->mealstub_product_id)
                        ->where('status', '!=', 'T')
                        ->update([
                            'STATUS' => 'V',
                            'DISPLAYMONITOR' => 1
                    ]);

                    // if there are deleted rows then reset the line no and the pos line no
                    $osd_delete = OrderSlipDetail::where('branch_id', request()->branch_id)
                        ->where('orderslip_header_id', request()->header_id)

                        ->where('device_number', request()->device_id)
                        ->where('outlet_id', request()->outlet_id)
                        ->where('sequence', request()->sequence)
                        ->where('MEAL_STUB_PRODUCT_ID', request()->mealstub_product_id)
                        ->where('status',  'T')
                        ->delete();

                    if( $osd_delete > 0){
                        // get the all the orderslip within the branch, outlet and device
                        $osds = OrderSlipDetail::where('branch_id', request()->branch_id)
                        ->where('orderslip_header_id', request()->header_id)
                        ->where('device_number', request()->device_id)
                        ->where('outlet_id', request()->outlet_id)
                        ->get();

                        $line_number = 0;
                        foreach( $osds as $item){
                            $line_number +=1;
                            OrderSlipDetail::where('branch_id', request()->branch_id)
                            ->where('orderslip_header_id', request()->header_id)
                            ->where('device_number', request()->device_id)
                            ->where('outlet_id', request()->outlet_id)
                            ->where('orderslip_detail_id', $item->orderslip_detail_id)
                            ->update([
                                'LINE_NO' => $line_number,
                                'POSLINENO' =>$line_number,
                            ]);
                        }
                    }



                }

                DB::commit();
                return response()->json([
                    'success'   => true,
                    'status'    => 201,
                    'message'   => 'Success'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return response()->json([
                    'message'   => $e->getMessage()
                ], 500);
            }



            return response()->json([
                'message' => 'Remove Successfully'
            ]);
        }


    public function information(Request $request){

        $os = OrderSlipHeader::where('branch_id', $request->branch_id)
            ->where('orderslip_header_id', $request->os_id)
            ->where('outlet_id', $request->outlet_id)
            ->where('device_no', $request->device_id)
            ->first();

        if( !$os ){
            return response()->json([
                'message' => 'Resource Not Found!',
            ], 400);
        }

        $osd = OrderSlipDetail::where('branch_id', $request->branch_id)
            ->where('orderslip_header_id', $request->os_id)
            ->where('outlet_id', $request->outlet_id)
            ->where('device_number', $request->device_id)
            ->where('status', '!=', 'V')
            ->get();

        return response()->json([
            'message' => 'success',
            'header' => new OrderSlipHeaderResource($os),
            'details' => new OSDEtailCollection($osd)
        ]);
    }

    public function headcount(Request $request){

        $result = OrderSlipHeader::where('branch_id', $request->branch_id)
                ->where('orderslip_header_id', $request->os_id)
                ->where('device_no', $request->device_id)
                ->where('outlet_id', $request->outlet_id)
                ->update([
                    'TOTALHEADCOUNT' => $request->head_count
                ]);

        if($result){
            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated, You can now Print',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Update unsuccessful',
            ]);
        }


    }

    public function setDuration(Request $request){
        try {
            DB::beginTransaction();
            DB::enableQueryLog();


            $os = OrderSlipHeader::where('branch_id', $request->branch_id)
                    ->where('orderslip_header_id', $request->os_id)
                    ->where('device_no', $request->device_id)
                    ->where('outlet_id', $request->outlet_id)
                    ->first();

            if($os->qdate == null){


                OrderSlipHeader::where('branch_id', $request->branch_id)
                    ->where('orderslip_header_id', $request->os_id)
                    ->where('device_no', $request->device_id)
                    ->where('outlet_id', $request->outlet_id)
                    ->update([
                        'QDATE' => now(),
                    ]);

            }


            OrderSlipHeader::where('branch_id', $request->branch_id)
            ->where('orderslip_header_id', $request->os_id)
            ->where('device_no', $request->device_id)
            ->where('outlet_id', $request->outlet_id)
            ->update([
                'DISPLAYMONITOR' => 2,
            ]);

            // set status to X
            OrderSlipDetail::where('branch_id', $request->branch_id)
            ->where('orderslip_header_id', $request->os_id)
            ->where('device_number', $request->device_id)
            ->where('outlet_id', $request->outlet_id)
            ->where('status', 'T')
            ->update([
                'STATUS' => 'X'
            ]);
            Log::info('update successfull');

            // check kung gusto na mapapaid ung mealstub pagcheckout
            if(config('ambulant.auto_paid_mealstub') == 1){
                $this->setPaidMealStubs($request->branch_id, $request->outlet_id, $request->device_id, $os);
            }

            DB::commit();
            return response()->json([
                'success'   => true,
                'status'    => 201,
                'message'   => 'Success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' =>false,
                'status' => 500,
                'message'   => $e->getMessage()
            ], 500);
        }

    }


    public function hasOnlyMealstubs($branch, $outlet, $device, $osh){
        //get count of mealstub
        $mealstub_count = 0;
        $non_mealstub_count = 0;

        if(!is_null($osh->mealstub_count)){
            $mealstub_count = $osh->mealstub_count;
        }

        $non_mealstub_count = OrderSlipDetail::where('branch_id', $branch)
        ->where('outlet_id', $outlet)
        ->where('device_number', $device)
        ->where('orderslip_header_id', $osh->orderslip_header_id)
        ->where('status', '!=', 'V')
        ->whereNull('mealstub_serialnumber')
        ->count();

        //  dd($mealstub_count, $non_mealstub_count);

        if($mealstub_count > 0 && $non_mealstub_count == 0 ){
            return true;
        }else{
            return false;
        }

    }

    public function setPaidMealStubs($branch, $outlet, $device, $osh){
        // if mealstub lng ang laman
        if($this->hasOnlyMealstubs($branch, $outlet, $device, $osh)){
            // mealstub set status = 1 used = 1 meaning gamit na ung mealstub

            $mealstubs = OrderSlipDetail::distinct()
            ->where('branch_id', $branch)
            ->where('outlet_id', $outlet)
            ->where('device_number', $device)
            ->where('orderslip_header_id', $osh->orderslip_header_id)
            ->where('status', '!=', 'V')
            ->where('mealstub_serialnumber', '!=', null)
            ->groupby('MEAL_STUB_SERIAL_NUMBER')
            ->groupby('ORDERSLIPNO')
            ->groupby('DEVICENO')
            ->groupby('BRANCHID')
            ->groupby('OUTLETID')
            ->groupby('ORDERSLIPDETAILID')
            ->get(['MEAL_STUB_SERIAL_NUMBER']);

            Mealstub::where('type', 'MS')
            ->where('branch_id', $branch)
            ->whereIn('serial_number', $mealstubs)
            ->update([
                'STATUS' => 1,
                'USED'  => 1,
                'DATEUSED' => getClarionDate(now())
                ]);


            // ipaid ung status ng header
            $os = OrderSlipHeader::where('branch_id', $branch)
                    ->where('orderslip_header_id', $osh->orderslip_header_id)
                    ->where('device_no', $device)
                    ->where('outlet_id', $outlet)
                    ->update([
                        'PAID' => 1
                    ]);


        };
    }

    public function resetActiveOrder(){

        try {

            DB::beginTransaction();
            // DB::enableQueryLog();

            // check 1st if there is an existing orderslip
            $user       = Auth::user();
            $header = $user->activeOrder();

           // deactivate pending orderslip that is not created today
            if($header){

                $ambulant_created_date = Carbon::parse($header->created_at)->toDateString();
                $todays_date = Carbon::today()->toDateString();

                if( $ambulant_created_date != $todays_date ){
                    $count = OrderSlipHeader::where('branch_id', getUserBranchId() )
                    ->where('outlet_id', getUserOutletId() )
                    ->where('device_no', getDeviceId())
                    ->update([
                        'ISACTIVE' => 0
                    ]);

                    if($count > 0){
                        DB::commit();
                        return response()->json([
                            'success' => true,
                            'status'  => 200,
                            'message' => 'Deactivated an orderslip',
                            'data'    => $count
                        ]);
                    }
                }
            }


            // \Log::debug( DB::getQueryLog() );
            DB::commit();
            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => 'nothing updated',

            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 500);
        }


    }
}
