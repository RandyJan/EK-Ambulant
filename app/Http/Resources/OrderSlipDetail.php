<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Model\KitchenOrder;
use App\SitePart;

class OrderSlipDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // $ko = KitchenOrder::where('branch_id',$this->branch_id)
        //     ->where('origin',2)
        //     ->where('header_id', $this->orderslip_header_id)
        //     ->where('detail_id', $this->orderslip_detail_id)
        //     ->where('part_id', $this->product_id)
        //     ->where('status', 'A')
        //     ->first();

        // $ko_status = null;

        // if($ko){
        //     $ko_status = 'FOR PICKUP';
        // }

        // sitepart
        $sp = SitePart::where('branch_id', $this->branch_id)
                ->where('sitepart_id', $this->product_id)
                ->first();

        // showing discount if pwd or senior
        $amount = 0;
        $discount = 0;
        $net_amount = 0;
        $original_price = 0;
        $less_vat = 0;
        $original_price = (double)$this->qty * $this->srp;

        // if( $this->guest_type == 2 || $this->guest_type == 3 ){
        //     $this->srp = (double)$this->srp;

        //     $less_vat =  $this->srp - ($this->srp / 1.12);
        //     $this->srp = $this->srp / 1.12;
        //     //if( $this->guest_type == 2 || $this->guest_type == 3){
        //         // $this->srp = $this->srp / 1.12;
        //     // }

        // }

        $amount = (double)$this->qty * $this->srp;
        $less_vat = $less_vat * $this->qty;

        // if( $this->guest_type == 2 || $this->guest_type == 3 ){

        //     $discount = $amount * .20;
        //     $net_amount = $amount - $discount;

        // }else{
            $net_amount = $amount - $discount;
        // }



        //return parent::toArray($request);
        return [
            'branch_id' 			=> $this->branch_id,
            'orderslip_detail_id' 	=> $this->orderslip_detail_id,
            'orderslip_header_id' 	=> $this->orderslip_header_id,
            'product_id' 			=> $this->product_id,
            'name'                  => $this->sitePart->product_description, //$this->part->description,
            'part_number'			=> $this->part_number,
            'product_group_id'		=> $this->product_group_id,
            'qty' 					=> (double)$this->qty,
            'srp' 					=> (double)$this->srp,
            'amount' 				=> $amount,
            'discount'              => $discount,
            'net_amount'            => $net_amount,
            'remarks'				=> $this->remarks,
            'order_type'			=> $this->order_type,
            'status'				=> $this->status,
            'postmix_id' 			=> $this->postmix_id,
            'is_modify'				=> $this->is_modify,
            'line_number'           => $this->line_number,
            'old_comp_id'           => $this->old_comp_id,
            'sequence' 			    => $this->sequence,
            'customer_id'			=> $this->customer_id,
            'encoded_date'          => $this->encoded_date,
            'main_product_id'       => $this->main_product_id,
            'main_product_comp_id'  => $this->main_product_comp_id,
            'main_product_comp_qty' => $this->main_product_comp_qty,
            'guest_no'              => $this->guest_no,
            'guest_type'            => $this->guest_type,
            // 'kitchen_status'        => $ko_status,
            'kitchen_status'        => $this->status,
            'is_vatable'            => $sp->is_vat,
            'is_food'               => $sp->is_food,
            'is_admission'          => $sp->pre_part_no,
            'is_unli'               => $sp->is_unli,
            'orig_subtotal'         => $original_price,
            'less_vat'              => (double)$less_vat,
            'device_number'         => $this->device_number,
            'outlet_id'             => $this->outlet_id,
            'kitchen_loc'           => $this->kitchen_loc,
            'os_date'               => $this->os_date,
            'display_kds'           => $this->display_kds,
            'dev_id_mod'            => $this->dev_id_mod,
            'mealstub_product_id'   => $this->mealstub_product_id,
            'mealstub_serialnumber' => $this->mealstub_serialnumber,
            'pos_line_no'           => $this->pos_line_no,
        ];
    }
}
