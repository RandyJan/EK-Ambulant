<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class RedeemOutlets extends Model
{
    use Eloquence, Mappable, Mutable;
    //
    protected $table 		= 'RedeemOutlets';  
    public $incrementing    = false;
    public $timestamps 		= false;

    protected $maps = [
        'branch_id'     => 'BRANCHID',
        'outlet_id'     => 'OUTLETID',
        'product_id'    => 'PRODUCTID' // redeemable product with the branch and outlet
    ];
}
