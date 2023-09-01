<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuestFile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'branch_id'         => $this->branch_id,
            'outlet_id'         => $this->outlet_id,
            'orderslip_no'      => $this->orderslip_no,
            'table_no'          => $this->table_no,
            'guest_no'          => $this->guest_no,
            'guest_type'        => $this->guest_type,
            'with_order'        => $this->with_order,
            'clarion_date'      => $this->clarion_date
        ];
    }
}
