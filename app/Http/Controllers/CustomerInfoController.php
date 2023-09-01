<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customers;
use Auth;
use DB;
use Log;
class CustomerInfoController extends Controller
{
    //
    public function saveInfo(Request $request){

        try {
        
        $branch_id  = getUserBranchId();
        $info = new Customers;
        $customer_id = 

        DB::beginTransaction();
        $info->BRANCHID = $branch_id;
        $info->CUSTOMERID =$info->getNewCustomerId();
        $info->MOBILE_NUMBER = $request->phone_number;
        $info->NAME =$request->customer_name;
        $info->BIRTHDATE = $request->bdate;

        $info->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' =>$info
        ]);
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

}
