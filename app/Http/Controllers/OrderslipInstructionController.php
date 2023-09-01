<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderSlipHeader;

class OrderslipInstructionController extends Controller
{
    //
    public function getInstruction(Request $request){

        $os = OrderSlipHeader::where('orderslip_header_id', $request->os_id)
            ->where('branch_id', $request->branch_id)
            ->where('outlet_id', $request->outlet_id)
            ->where('device_no', $request->device_id)
            ->first();

        if( !$os ){
            return response()->json([
                'success' => false,
                'message' => 'Orderslip not found!'
            ]);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Success',
            'data'      => [
                'remarks' => trim($os->remarks)
            ] 
        ]);
    }

    public function update(Request $request){

        OrderSlipHeader::where('orderslip_header_id', $request->os_id)
            ->where('branch_id', $request->branch_id)
            ->where('outlet_id', $request->outlet_id)
            ->where('device_no', $request->device_id)
            ->update([
                'REMARKS' => $request->remarks
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated.'
        ]);
    }
}
