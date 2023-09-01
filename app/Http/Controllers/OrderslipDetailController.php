<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Log;
use App\OrderSlipDetail;
use App\Http\Resources\OrderSlipDetailCollection;

class OrderslipDetailController extends Controller
{
    public function destroy(Request $request)
    {

        try {

            $result = OrderSlipDetail::where('branch_id', $request->branch_id)
                ->where('orderslip_header_id', $request->header_id)
                ->where('main_product_id', $request->main_product_id)
                ->where('sequence', $request->sequence)
                ->where('outlet_id', $request->outlet_id)
                ->where('device_number', $request->device_no)
                ->where('status', 'X')
                ->delete();

            $msg = 'Removed Successfully';

            if($result == 0 ){
                $msg = 'Item cannot be removed.';
            }

            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => $msg,
                'data'      => $result
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

    public function destroyItems(Request $request){
        try{

            DB::beginTransaction();
            if (!isset($request->items)) {
                return response()->json([
                    'success'   => false,
                    'status'    => 200,
                    'message'   => "There are no item/s to delete"
                ]);
            }

            foreach ($request->items as $item) {
                $item = (object) $item;
                $result = OrderSlipDetail::where('branch_id', $item->branch_id)
                 ->where('orderslip_header_id', $item->header_id)
                 ->where('main_product_id', $item->main_product_id)
                 ->where('sequence', $item->sequence)
                 ->where('outlet_id', $item->outlet_id)
                 ->where('device_number', $item->device_no)
                 ->where('status', 'X')
                 ->delete();
             }
            DB::commit();
            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => 'Success'
            ]);

        }catch (\Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success'   => false,
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function getSingleOrder(Request $request){
        try{
            $osd = new OrderSlipDetail;
            $result = $osd->getSingleOrder(
                $request->header_id,
                $request->branch_id,
                $request->outlet_id,
                $request->device_id,
                $request->main_product_id,
                $request->sequence
            );

            if($result->isempty()){
                return response()->json([
                    'success'   => false,
                    'status'    => 404,
                    'message'   => 'Item not found'
                    // 'result'    => $result
                ]);
                // return redirect()->route()->with('error','Item not found');


            }


            return response()->json([
                'success'   => true,
                'status'    => 200,
                'result'    => new OrderSlipDetailCollection($result)
                // 'result'    => $result
            ]);

        }catch( \Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'success'   => false,
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
        }
    }
}
