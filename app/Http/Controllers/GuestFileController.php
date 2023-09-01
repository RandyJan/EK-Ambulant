<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\GuestFile;
use App\Http\Resources\GuestFile as GuestFileResource;
use App\Http\Resources\GuestFileCollection;
use Auth;
use DB;
use App\Customers;

class GuestFileController extends Controller
{
    //
    public function findByTableno($id)
    {
        // 'branch_id'       =>'BRANCHID',
        // 'outlet_id'       =>'OUTLETID',
        // 'device_no'       =>'DEVICENO',
        // 'pos_number'      =>'POSNUMBER',
        // 'orderslip_no'    =>'ORDERSLIPNO',
        // 'table_no'        =>'TABLENO',
        // 'guest_no'        =>'GUESTNO',

        $user = Auth::user();
        $guests = GuestFile::where('branch_id', $user->duty()->branch_id)
            ->where('outlet_id', $user->duty()->storeOutlet->outlet_id)
            ->where('orderslip_no', $user->activeOrder()->orderslip_header_id)
            ->where('table_no', $id)
            ->get();

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => new GuestFileCollection($guests)
        ]);
    }

    public function updateGuestCred(Request $request)
    {

        
        try {
            
            DB::beginTransaction();

            $user = Auth::user();
            // dd($request, $user->duty());
            $guest = GuestFile::where('branch_id', $user->duty()->branch_id)
                ->where('outlet_id', $user->duty()->storeOutlet->outlet_id)
                ->where('orderslip_no', $request->os_no)
                ->where('table_no', $request->table_no)
                ->where('guest_no', $request->guest_no)
                // ->get();
                ->update([
                    'GUESTTYPE'     => $request->guest_type,
                    'DISCID'        => $request->guest_id,
                    'GUESTNAME'     => $request->guest_name,
                    'GUESTADDRESS'  => $request->guest_address,
                    'GUESTTIN'      => $request->guest_tin
                ]);;
        
             // commit all changes
             DB::commit();

            return response()->json([
                'success' => true,
                'status' => 200,
                'data' =>$guest
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
