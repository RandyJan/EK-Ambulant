<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class Device extends Model
{
    //

    use Eloquence, Mappable, Mutable;

    // protected $table        = 'DeviceList';
    // protected $primaryKey   = 'DEVICELISTID';
    protected $table        = 'HOSiteTerminals';
    protected $primaryKey   = 'ID';
    public $timestamps      = false;

    /**
     * Model Mapping
     */
    // protected $maps = [
    //     '_id'                   => 'DEVICELISTID',
    //     'branch_id'             => 'BRANCHID',
    //     'name'                  => 'DEVICENAME',
    //     'device_no'             => 'DEVICENO'
    // ];

    protected $maps = [
        '_id'                   => 'ID',
        'pos_id'                => 'TERMNO', //pos_id or Terminal ID//
        'branch_id'             => 'STATIONCODE', // branch id
        'name'                  => 'DESC',
        'device_no'             => 'ID',
        'type'                  => 'TYPE',
        'status'                => 'STATUS',
        'outlet_id'             => 'OUTLETID'
 
    ];
    public function deviceByBranch(){
        return $this->belongsTo('App\User', 'AMBULANT_BRANCH_ID', 'branch');
    }
    public function posId(){
        return $this->belongsTo('App\UserDevice','DEVICEID','_id');
    }
    public static function getAllByBranch(){
        return static::where('branch_id','branch')
        ->where('type', 'DVN')
        ->get();
    }
  
    public static function getDevice($id){
        return static::where('branch_id', 'branch')
        ->where('type', 'DVN')
        ->where('_id', $id)
        ->first();
    }


    /**
     * RELATIONSHIT
     */
    public function branch(){
        return $this->belongsTo('App\Branches', 'STATIONCODE', 'BRANCHID');
    }

    public function outlets(){
        return $this->hasMany('App\Outlet', 'OUTLETID', 'OUTLETID');
    }

    /**
     * LOGIC
     */
    public function getOutletByBranchId($id=null){
        return $this->outlets()->where('BRANCHID', $id)
            ->first();
    }

}
