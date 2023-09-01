<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class TableHistory extends Model
{
    //
    use Eloquence, Mappable, Mutable;

    protected $table        = 'tablehistory'; 
    public $timestamps      = false;

    /**
     * Model Mapping
     */
    protected $maps = [  
        'branch_id'             => 'BRANCHID',
        'outlet_id'             => 'OUTLETID',
        'device_no'             => 'DEVICENO',
        'orderslip_no'          => 'ORDERLIPNO',
        'table_no'              => 'TABLENO',
        'created'               => 'CREATED',
        'c_date'                => 'CDATE',
        'c_time'                => 'CTIME',
        'bus_datetime'          => 'BUSDATE',
        'actual_heads'          => 'ACTUALHEADS',
        'total_heads'           => 'TOTALHEADS',
        'from_table_no'         => 'FROMTABLENO',
        'status'                => 'STATUS',
        'merged'                => 'MERGED',
        'merge_id'              => 'MERGEID',
        'pos_number'            => 'POSNUMBER'
    ];
  
    protected $getterMutators = [
    ];
}
