<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class GuestFile extends Model
{
    //
    use Eloquence, Mappable, Mutable;
    //
    protected $table        = 'GuestFile'; 
    public $timestamps      = false;

    //mapping//
    protected $maps =[
        'branch_id'       =>'BRANCHID',
        'outlet_id'       =>'OUTLETID',
        'device_no'       =>'DEVICENO',
        'pos_number'      =>'POSNUMBER',
        'orderslip_no'    =>'ORDERSLIPNO',
        'table_no'        =>'TABLENO',
        'guest_no'        =>'GUESTNO',
        'discount_id'     =>'DISCID',
        'guest_name'      =>'GUESTNAME',
        'guest_type'      =>'GUESTTYPE',
        'guest_address'   =>'GUESTADDRESS',
        'guest_tin'       =>'GUESTTIN',
        'with_order'      =>'WITHORDER',
        'clarion_date'    =>'CDATE',
        'clarion_time'    =>'CTIME'
    ];
    protected $getterMutators = [
        // 'guest_name'        => 'trim',
        // 'guest_address'     => 'trim',
        // 'guest_tin'         => 'trim',
        // 'discount_id'       => 'trim',
        'guest_type'        => 'trim'
    ];
}