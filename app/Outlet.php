<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class Outlet extends Model
{
    //
    use Eloquence, Mappable, Mutable;
    //
    protected $table        = 'Outlets';
    protected $primaryKey   = 'OUTLETID';
    public $timestamps      = false;

    /** 
     * Model Mapping
     */
    protected $maps = [
        'branch_id'     => 'BRANCHID',
        'outlet_id'     => 'OUTLETID',
        'code'          => 'OUTLETCODE',
        'description'   => 'DESCRIPTION',
        'zone_id'       => 'ZONEID',
        'outlet_type'   => 'OUTLETTYPE'
    ];

    protected $getterMutators = [
        'code'          => 'trim',
        'description'   => 'trim'
    ];
    public function outletByBranch(){
        return $this->belongsTo('App\User', 'BRANCHID', 'branch');
    }
    public static function getAllByBranch(){
        return static::where('branch_id','branch')->get();
    }
    public function userBranch(){
        return $this->belongsTo('App\UserDevice','BRANCHID');
    }
 
}
