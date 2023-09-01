<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\Group;
use App\Device;
use App\Master;
use App\BusUnit;
use App\Holiday;
use App\Category;
use App\SitePart;
use App\PartLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use App\Http\Resources\PostmixCollection;

use App\Http\Resources\PartLocationCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\Postmix as PostmixResource;
use App\Http\Resources\PartLocation as PartLocationResource;


class PartLocationController extends Controller
{
    //
    public function category($cat_id, Request $request)
    {

        $group_id   = $cat_id;

        // if($group_id == null  ){
        //     return response()->json([
        //         'success'   => false,
        //         'status'    => 400,
        //         'message'      => 'Invalid ID'
        //     ]);
        // }

        $result     = Category::getByGroupId($group_id);


        $result->transform(function ($v) {
            return [
                'category_id'       => $v->category_id,
                'description'       => $v->description
            ];
        });



        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'status'    => 200,
                'data'      => $result
            ]);
        }
    }

    public function product($outlet_id, $product_id)
    {
        $user = Auth::user();

        if (is_null($user->activeOrder())) {
            return back()->with('error', 'Please create orderslip to continue.');
        }

        // if( $user->activeOrder()->tables()->isEmpty() ){
        //     return back()->with('error','Please Choose Table to continue.');
        // }

        return view('pages.product', compact('outlet_id', 'product_id'));
    }

    public function productByOutlet(Request $request)
    {


        $product_id = $request->product_id;
        $outlet_id  = $request->outlet_id;

        $pl = PartLocation::byProductAndOutlet($product_id, $outlet_id);

        $result = new PartLocationResource($pl);

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'result'    => $result,
            'base_url'  => url('/')
        ]);

    }

    public function productComponents(Request $request)
    {
        $product_id = $request->product_id;
        $outlet_id  = $request->outlet_id;

        // $pl = PartLocation::byProductAndOutlet($product_id, $outlet_id);
        $pl = PartLocation::byBranchProductAndOutlet($product_id, $outlet_id, getUserBranchId());

        // dd($pl);
        if ($request->group_by == 'mc') { // modifiable component
            $pl = $pl->postmixModifiableComponents()->paginate();

        } else if ($request->group_by == 'nmc') { // non modifiable component
            $pl = $pl->postmixNoneModifiableComponents()->paginate();
        } else {
            return response()->json([
                'success'   => false,
                'status'    => 400,
                'message'   => 'No Group has been set'
            ]);
        }

        $result = new PostmixCollection($pl);
        return response()->json([
            'success'   => true,
            'status'    => 200,
            'result'    => $result
        ]);
    }

    public function productByCategory(Request $request)
    {
        $product_id = $request->product_id;
        $outlet_id  = $request->outlet_id;
        $branch_id  = getUserBranchId();

        $pl     = PartLocation::where('product_id', $product_id)
            ->where('outlet_id', $outlet_id)
            ->first();
        // dd(new PartLocationResource($pl));
        $pls    = PartLocation::byCategoryOfProductPerOutlet(
            $pl->category_id,
            $pl->product_id,
            $outlet_id,
            $branch_id
        );

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'result'    => [
                'product'       => new PartLocationResource($pl),
                'categories'    => new PartLocationCollection($pls)
            ]
        ]);
    }

    public function products(Request $request)
    {
        $user = Auth::user();

        /**
         * GET PRODUCT BELONGS TO OUTLET
         */
        $pl = PartLocation::where('outlet_id', $user->outlet_id)
            ->where('branch_id', $user->branch)
            ->get();


        $val = config('ambulant.group_not_to_display');
        $val = explode(',', $val);


        // $groups = $pl->unique('group')
        //     // ->whereNotIn('group.group_id', $val)
        //     ->transform(function ($value) {
        //         return [
        //             'group_id'      => $value->group_id,
        //             'description'   => $value->group->description
        //         ];
        //     });

        // dd(
        //     $groups,
        //     $val,
        //     config('ambulant.group_not_to_display')
        // );



        /*
        $group_id       = $request->get('group_id') ? $request->get('group_id') : 'All';
        $category_id    = $request->get('sub_category') ? $request->get('sub_category') : 'All';
        $name           = $request->get('name') ? $request->get('name') : '';

        dd( $group_id , $category_id , $name);
        $products = PartLocation::where('outlet_id',  $user->outlet_id)
            ->where('branch_id', config('ambulant.branch_id'))
            ->where(function ($query) use ($group_id) {
                if ($group_id != 'All') {
                    $query->where('group_id', $group_id);
                }
            })
            ->where(function ($query) use ($category_id) {
                if ($category_id != 'All') {
                    $query->where('category_id', $category_id);
                }
            })
            ->where('description', 'LIKE', '%' . $name . '%')
            ->get();

        dd($products, $group_id, $category_id);
        $product = new PartLocationCollection($products);
        */


        $group_id       = $request->get('group_id') ? $request->get('group_id') : 'All';
        $category_id    = $request->get('sub_category') ? $request->get('sub_category') : 'All';
        $name           = $request->get('name') ? $request->get('name') : '';


        $products = PartLocation::where('outlet_id',  $user->outlet_id)
            ->where('branch_id', $user->branch)
            ->where(function ($query) use ($group_id) {
                if ($group_id != 'All') {
                    $query->where('group_id', trim($group_id));
                }
            })
            ->where(function ($query) use ($category_id) {
                if ($category_id != 'All') {
                    $query->where('category_id', trim($category_id));
                }
            })
            ->where('retail', '>', 0)
            ->where('description', 'LIKE', '%' . $name . '%')
            ->get();

        // dd($products, $group_id, $category_id);
        // $product = new PartLocationCollection($products);

        // return response()->json([
        //     'success'   => true,
        //     'status'    => 200,
        //     'result'    =>  $products

        // ]);

        return view('pages.products', compact('groups', 'products'));
    }

    // public function categories(Request $request)
    // {
    //     /**
    //      * Group not to be included in the display
    //      */
    //     /*
    //     $filter = explode(',', config('ambulant.group_not_to_display'));

    //     $groups = Group::whereNotIn(DB::raw("LTRIM(RTRIM(GROUPCODE))"), $filter)->get();

    //     return view('pages.order_sequence.step1_category_selection', compact('groups'));
    //     */
    //     $user = Auth::user();
    //     // dd( $user->userDevice( request()->cookie('device_id') )->outletByBranch );
    //     dd( $user->userDevice( request()->cookie('device_id') )->outlet  );


    //     // $items = PartLocation::distinct('group_id')
    //     $groups = PartLocation::distinct('group_id')
    //             ->join('groups', 'Partslocation.GROUP', '=', 'groups.GROUPCODE')
    //             ->where('branch_id', getUserBranchId())
    //             ->where('outlet_id', getUserOutletId())
    //             ->groupby('Partslocation.BRANCHID', 'PartsLocation.OUTLETID', 'PartsLocation.GROUP', 'groups.DESCRIPTION')
    //             ->orderby('groups.DESCRIPTION', 'asc')
    //             ->select('Partslocation.GROUP','groups.DESCRIPTION')
    //             ->get();

    //             // return response()->json([
    //             //     'success'   => false,
    //             //     'status'    => 200,
    //             //     'data'      => $groups
    //             // ]);

    //     return view('pages.order_sequence.step1_category_selection', compact('groups'));
    // }
    public function categories(Request $request)
    {
        $user = Auth::user();
        $outlet_type = $user->userDevice( request()->cookie('device_id') )->device->getOutletByBranchId(getUserBranchId())->outlet_type;
        $master_code = 0;
        if($outlet_type == 1){ // ticketing so mastercode = 2
            $master_code = 2;
        }else if($outlet_type == 2){
            $master_code = 1; // fnb
        }else if ($outlet_type == 3){
            $master_code = 3; // merchandise
        }


        $groups = PartLocation::distinct('group_id')
                ->join('groups', 'Partslocation.GROUP', '=', 'groups.GROUPCODE')
                ->where('branch_id', getUserBranchId())
                ->where('outlet_id', getUserOutletId())
                ->where('Partslocation.MASTERCODE', $master_code )
                ->where('Partslocation.retail', '>', 0)
                ->groupby('Partslocation.BRANCHID', 'PartsLocation.OUTLETID', 'PartsLocation.GROUP', 'groups.DESCRIPTION')
                ->orderby('groups.DESCRIPTION', 'asc')
                ->select('groups.DESCRIPTION','Partslocation.GROUP')
                ->paginate(24);




        return view('pages.order_sequence.step1_category_selection', compact('groups'));
    }

    public function productsOfCategory(Request $request, $group){
        /**
         * GET PRODUCT BELONGS TO OUTLET
         */

        $products = PartLocation::where('outlet_id',  getUserOutletId() )
            ->where('branch_id', getUserBranchId() )
            ->where(DB::raw("LTRIM(RTRIM([GROUP]))"), $group)
            ->where('retail', '>', 0)
            // ->orderby('description', 'asc')
            ->orderby('short_code', 'asc')
            ->get();

        // $items = new PartLocationCollection($products);

        // return response()->json([
        //     'success'   => false,
        //     'status'    => 200,
        //     'data'      => $items
        // ]);
        $items = array();
        foreach ($products as $product){
            $sitepart = SitePart::where('branch_id', getUserBranchId())
                                ->where('sitepart_id',$product->product_id )
                                ->first();

            if($sitepart->is_ticket == 1){
                $todaysDate = \Carbon\Carbon::now();
                $dayOfTheWeek = $todaysDate->dayOfWeek;
                // $dayOfTheWeek = 5;
                $weekdayTickets =  [1,2,3,4]; // mon, tue, wed, thu
                $isWeekDayTicket = in_array($dayOfTheWeek, $weekdayTickets);


                $ticket_in_holiday_date = Holiday::where('date', getClarionDate($todaysDate))->first();

                // weekend and holiday tickets
                if( !$isWeekDayTicket ||  ($ticket_in_holiday_date != null && $isWeekDayTicket) || ($ticket_in_holiday_date != null && !$isWeekDayTicket  ) ){

                    if(!is_null($sitepart->is_ticket_weekday) && $sitepart->is_ticket_weekday == 0){
                        array_push($items, $product);
                    }

                }else {
                    if(!is_null($sitepart->is_ticket_weekday) && $sitepart->is_ticket_weekday == 1){
                        array_push($items, $product);
                    }
                }
            }
            else{
                array_push($items, $product);
            }
        }
        // dd(url()->current());
        // $items = $this->paginate($items, 16);
        $items = $this->paginate($items, 16)->setPath(\Request::url());

        // // Get current page form url e.x. &page=1
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // // Create a new Laravel collection from the array data
        // $itemCollection = collect($items);

        // // Define how many items we want to be visible in each page
        // $perPage = 15;

        // // Slice the collection to get the items to display in current page
        // $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // // Create our paginator and pass it to the view
        // $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

        // // set url path for generted links
        // $paginatedItems->setPath($request->url());

        $group = Group::where('group_id', $group)->first();

        // dd($items);
        return view('pages.order_sequence.step2_products_list_selection', compact('items', 'group'));
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        // // Get current page form url e.x. &page=1
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        // // Create a new Laravel collection from the array data
        $items = $items instanceof Collection ? $items : Collection::make($items);
        // // Create our paginator and pass it to the view
        $paginatedItems =  new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        // $paginatedItems->setPath(\Request::url());
        return $paginatedItems;
    }

    // public function categories(Request $request)
    // {
        /**
         * COMMENTED on Jan 15, 2020
         */
        // $user = Auth::user();

        // /**
        //  * GET PRODUCT BELONGS TO OUTLET
        //  */
        // $pl = PartLocation::where('outlet_id', $user->outlet_id)
        //     ->where('branch_id',  $user->branch)
        //     ->get();

        // $val = config('ambulant.group_not_to_display');
        // // dd($val);
        // $val = explode(',', $val);
        /**
         * END OF COMMENT
         */



        // public function unit()
        // {

        //     $units = BusUnit::where('master_id', config('ambulant.items_to_display')) // show only food and beverage
        //         ->get();

        //     return view('pages.category', compact('units'));
        // }


        // public function groups($unit_id)
        // {

        //     if (is_null($unit_id)) {
        //         return response()->json([
        //             'success'   => false,
        //             'status'    => 200,
        //             'message'   => 'Provide a group'
        //         ]);
        //     }


        //     $groups = Group::where('master_code', config('ambulant.items_to_display') ) // show only food and beverage
        //         ->where('unit_id', $unit_id)
        //         ->get();

        //     // dd($groups);
        //     $groups =  $groups->transform(function ($value) {
        //         return [
        //             'group_id'      => $value->group_id,
        //             'description'  => $value->description,

        //         ];
        //     });

        //     return response()->json([
        //         'success'   => true,
        //         'status'    => 200,
        //         'data'    => $groups

        //     ]);
        // }


        // public function categories(Request $request)
        // {
        //     $user = Auth::user();

        //     /**
        //      * GET PRODUCT BELONGS TO OUTLET
        //      */
        //     $pl = PartLocation::where('outlet_id', $user->outlet_id)
        //         ->where('branch_id', config('ambulant.branch_id'))
        //         ->get();

        //     $val = config('ambulant.group_not_to_display');
        //     // dd($val);
        //     $val = explode(',', $val);


        //     $groups = $pl->unique('group')
        //         //
        //         ->transform(function ($value) {
        //             return [
        //                 'group_id'      => $value->group_id,
        //                 'description'   => $value->group->description,
        //                 'master_code'   => $value->group->master_code
        //             ];
        //         });

        //     // return response()->json([
        //     //     'success'   => true,
        //     //     'status'    => 200,
        //     //     'result'    => [
        //     //       $groups
        //     //     ]
        //     // ]);
        //     return view('pages.category', compact('groups'));
        // }

        // master > master
        // group  > busunit
        // categories > group
        // subcategories > category
        // products > products

    // }


    // the categories section
    public function groups()
    {
        // $master_code = config('ambulant.master_code');
        // $unit_code = config('ambulant.unit_code');
        // $unit_code = explode(',', $unit_code);
        // // dd( $master_code , $unit_code , $val);
        // $result = Group::where('master_code', $master_code )
        //     ->whereIn('unit_code', $unit_code)
        //     ->get();

        //     // return response()->json([
        //     //     'success'   => true,
        //     //     'status'    => 200,
        //     //     'result'    => [
        //     //         $result
        //     //     ]
        //     // ]);
        //     return view('pages.category', compact('result'));



        $user = Auth::user();

        /**
         * GET PRODUCT BELONGS TO OUTLET
         */
        $pl = PartLocation::where('outlet_id', $user->outlet_id)
            ->where('branch_id', $user->branch)
            ->get();

        $val = config('ambulant.group_not_to_display');

        $val = explode(',', $val);

        // dd($val);
        $groups = $pl->unique('group')
            ->whereNotIn('group.group_id', $val)
            ->transform(function ($value) {
                return [
                    'group_id'      => $value->group_id,
                    'description'   => $value->group->description,
                    'master_code'   => $value->group->master_code
                ];
            });

        // return response()->json([
        //     'success'   => true,
        //     'status'    => 200,
        //     'result'    => [
        //       $groups
        //     ]
        // ]);

        $result = $groups;

        return view('pages.category', compact('result'));
    }


}
