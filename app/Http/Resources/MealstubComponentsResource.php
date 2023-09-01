<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
// use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\MealstubComponents;
use DB, Auth;
use App\Partlocation;
use App\Http\Resources\PartLocation as PartLocationResource;



class MealstubComponentsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // return [
        //     'data' => $this->collection->transform(function($component){
        //         return [
        //             'reference_id'      => trim($component->reference_id),
        //             'line_no'           => $component->line_no,
        //             'product_id'        => $component->product_id,     
        //             'postmix_id'        => $component->postmix_id,  
        //             'default_product_id'=> $component->default_product_id,  
        //             'qty'               => $component->qty,           
        //             'is_modifiable'     => $component->is_modifiable
                    
        //         ];
        //     }),
        // ];
        
        $total = 0;

        $results = $this->collection->map(function($item, $key){

            
            $user       = Auth::user();
            $branch_id  = getUserBranchId();
            $outlet_id  = getUserOutletId();
            
            $pl = Partlocation::where('product_id', $item->default_product_id)
                ->where('branch_id', $branch_id)
                ->where('outlet_id', $outlet_id)
                ->first();
            
            // $result = null;
            if($item->default_product_id != $item->product_id && $item->is_modifiable){
               
                $pl2 = Partlocation::where('product_id', $item->product_id)
                ->where('branch_id', $branch_id)
                ->where('outlet_id', $outlet_id)
                ->first();
                                
                //TODO: fix kung asan ung main na item
                $default_product_qty = 0;
                
                return  [
                    'reference_id'          => trim($item->reference_id),
                    'line_no'               => $item->line_no,
                    'product_id'            => $item->product_id,     
                    'postmix'               => $item->postmix_id,  
                    'default_product_id'    => $item->default_product_id,              
                    'qty'                   => $default_product_qty,           
                    'is_modifiable'         => $item->is_modifiable,
                    'comp_cat_id'           => trim($pl->category_id),
                    'description'           => $pl->description ,
                    'kitchen_location'      => $pl->kitchen_location,
                    'part_number'           => $pl->part_number   
                     
                ];
            }else{
                // dd("a");
                \Log::debug('product_id');
                return  [
                    'reference_id'          => trim($item->reference_id),
                    'line_no'               => $item->line_no,
                    'product_id'            => $item->product_id,     
                    'postmix'               => $item->postmix_id,  
                    'default_product_id'    => $item->default_product_id,  
                    'default_product_qty'   => $item->qty,            
                    'qty'                   => $item->qty,           
                    'is_modifiable'         => $item->is_modifiable,
                    'comp_cat_id'           => trim($pl->category_id),
                    'main-description'      => $pl->description,
                    'description'           => $pl->description,   
                    'kitchen_location'      => $pl->kitchen_location,
                    'part_number'           => $pl->part_number,
                    // 'postmix'               =>  $pl->postmix
                ];



            }
        
            
        });


        return ['data' => $results];

        
    }
}