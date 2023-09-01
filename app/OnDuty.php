<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class OnDuty extends Model
{
    //
    use Eloquence, Mappable, Mutable;

    protected $table        = 'CCEOnDuty';
    public $timestamps      = false;

    /**
     * Model Mapping
     */
    protected $maps = [
        'branch_id' => 'BRANCHID',
        'date'      => 'DATE',
        'name'      => 'CCENAME',
        'number'    => 'CCENUMBER',
        'outlet_id' => 'ASSIGNEDTO',
        'device_no' => 'DEVICENO'
    ];
 
    protected $getterMutators = [
        // 'password' => 'trim'
    ];

    /*
     * Relationship
     */
    public function storeOutlet(){
        return $this->belongsTo('App\Outlet','outlet_id');
    }

}
