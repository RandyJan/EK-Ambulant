<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderslipHeader;
use Carbon\Carbon;
use Helper;

class PagesController extends Controller
{
    //
    public function main(){
        // $os = OrderslipHeader::getByBranchAndOutlet( auth()->user()->outlet_id );

        $os = OrderSlipHeader::where('branch_id', getUserBranchId() )
            ->where('outlet_id', getUserOutletId() )
            ->orderby('encoded_date', 'desc')
            ->where('is_paid',0)
            ->whereDate('created_at', '=', Carbon::today()->toDateString())
            ->paginate(16);


        // dd($os);
        return view('main', compact('os'));
    }

    public function printPreview(){
        return view('/pages/orderslip/print_os');
    }
    public function subCategory(){
        return view('/pages/subCategory');
    }

    public function products(){
        return view('/pages/main-products');
    }
    public function category(){
        return view('/pages/category');
    }
    public function branch(){
        return view('/pages/admin/admin');
    }
    // public function orderSummary(){

    //     $os = OrderSlipHeader::where('branch_id', getUserBranchId() )
    //         ->where('outlet_id', getUserOutletId() )
    //         ->orderby('encoded_date', 'desc')
    //         ->whereDate('created_at', '=', Carbon::today()->toDateString())
    //         // ->where()
    //         // ->where('is_paid',0)

    //         ->paginate(10);

    //     // dd($os);

    //     return view('/pages/order-summary', compact('os'));
    // }

    public function orderSummaryPerDevice(){
        if(is_null( getDeviceId() )){
            return;
        }

        $paid_os = OrderSlipHeader::where('branch_id', getUserBranchId() )
            ->where('outlet_id', getUserOutletId() )
            ->orderby('encoded_date', 'desc')
            ->whereDate('created_at', '=', Carbon::today()->toDateString())
            ->where('device_no', getDeviceId())
            ->where('is_paid', 1)
            ->get();



        $os = OrderSlipHeader::where('branch_id', getUserBranchId() )
            ->where('outlet_id', getUserOutletId() )
            ->orderby('encoded_date', 'desc')
            ->whereDate('created_at', '=', Carbon::today()->toDateString())
            ->where('device_no',  getDeviceId());
            // ->where('is_paid',0)

        $os_status = [
            'paid_total_amount' => $paid_os->sum('total_amount'),
            'paid_count'        => $paid_os->count(),
            'unpaid_count'      => $os->get()->count()
        ];
        // $total_paid = $paid_os->sum('total_amount');
        // $paid_count = $paid_os->count();
        // $unpaid_count = $os->get()->count();
        $os = $os->paginate(10);
        return view('/pages/order-summary', compact('os', 'os_status'));
    }

    public function editProduct(){
        return view('/pages/edit-order');
    }
}
