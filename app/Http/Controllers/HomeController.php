<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartLocation;
use App\Category;
use App\Http\Resources\PartLocation as PartLocationResource;
use App\Http\Resources\PartLocationCollection;

use Auth;

class HomeController extends Controller
{
    //
    public function home(Request $request){

        $user = Auth::user();  

        /**
         * GET PRODUCT BELONGS TO OUTLET
         */ 
        $pl = PartLocation::with('group')
                ->where('outlet_id', 
                $user->duty()->outlet_id
            )->get(); 
        // $val = config('settings.group_not_to_display');
        $val = '';
        $val = explode(',',$val);

        $groups = $pl->unique('group')
            ->whereNotIn('group.group_id',$val)
            ->transform(function ($value) {  
                return [
                    'group_id'      => $value->group_id,
                    'description'   => $value->group->description
                ];  
            }); 
        // $groups = [];

        
        
        $group_id = $request->get('group_id') ? $request->get('group_id') : 'All';
        $category_id = $request->get('sub_category') ? $request->get('sub_category') : 'All';  
        $name = $request->get('name') ? $request->get('name') : '';  
        $products = PartLocation::where('outlet_id',  $user->duty()->outlet_id )
                    ->where(function ($query) use ($group_id) {
                        if ( $group_id != 'All') {
                            $query->where('group_id', $group_id);
                        }
                    })
                    ->where(function ($query) use ($category_id) {
                        if ( $category_id != 'All') {
                            $query->where('category_id', $category_id);
                        }
                    })
                    ->where('description','LIKE', '%'.$name.'%')
                    ->get(); 

        // dd($products, $group_id, $category_id);
        $product = new PartLocationCollection($products); 

        return view('home', compact('groups','products') );


    }

}
