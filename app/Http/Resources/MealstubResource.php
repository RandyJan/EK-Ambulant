<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\SitePart;

class MealstubResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request); 
        // $sitepart = SitePart::where('branch_id', config('settings.branch_id'))
        //                     ->where('sitepart_id',$this->product_id )
        //                     ->first();
        // $url = Storage::url($sitepart->part->img_url); 

        return [
           
            'product_id'    => $this->reference_id,
            // 'description'   => $this->description,
            // 'short_code'    => $this->short_code,
            'price'         => (double)$this->retail,
            'is_food'       => 1,
            // 'prepartno'     => $this->prepartno,
            // 'ssbuffer'      => $this->ssbuffer,
            // 'part_number'   => $this->part_number,
            // 'kitchen_loc'   => $this->kitchen_location
            // 'img_path'      => $url
        ];
    }
}
