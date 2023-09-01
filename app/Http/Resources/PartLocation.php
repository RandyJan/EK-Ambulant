<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\SitePart;

class PartLocation extends JsonResource
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
        $sitepart = SitePart::where('branch_id', getUserBranchId())
                                ->where('sitepart_id',$this->product_id )
                                ->first(); 

        return [
            'outlet_id'     => $this->outlet_id,
            'product_id'    => $this->product_id,
            'description'   => $this->description,
            'short_code'    => $this->short_code,
            'price'         => (double)$this->retail,
            'postmix'       => $this->postmix,
            'is_food'       => $this->is_food,
            'prepartno'     => $this->prepartno,
            'ssbuffer'      => $this->ssbuffer,
            'part_number'   => $this->part_number,
            'kitchen_loc'   => $this->kitchen_location,
            'category_id'   => $this->category_id,
            'is_ticket'     => $sitepart->is_ticket,
            $this->mergeWhen($sitepart->is_ticket == 1, [
                'is_weekday' => $sitepart->is_ticket_weekday, 
            ]),
            'img_path'      => $sitepart->part->img_url ? Storage::url('images/'.$sitepart->part->img_url):'/assets/images/default-product.png'
        ];
      
    }
}
