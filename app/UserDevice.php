<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait


class UserDevice extends Model
{
    use Eloquence, Mappable, Mutable;

    protected $table        = 'UserDevices';
    protected $primaryKey   = 'ID';
    public $timestamps      = false;

    /**
     * Model Mapping
     */
    protected $maps = [  
        '_id'               => 'ID',
        'name'              => 'NAME',
        'number'            => 'NUMBER',
        'password'          => 'PW', 
        'device_number'     => 'DEVICENO',
        'device_id'         => 'DEVICEID',
        'outlet_id'         => 'OUTLETID',
        'branch_id'         => 'BRANCHID',
        'active_status'     => 'INACTIVE'   // 0 = active , 1 = inactive
    ];

    protected $getterMutators = [
        'name'          => 'trim',
        'number'        => 'trim'
    ];

    public function pos_id(){
            return $this->hasMany('App\Device', 'ID', 'DEVICEID');
    }
    public function outlet(){
        return $this->belongsTo('App\Outlet','outlet_id');
                    

    }
    public function outletByBranch(){
        return $this->belongsTo('App\Outlet','branch_id');
    }
    // public function branch(){
    //     return $this->belongsTp('App\Branches','branch_id');
    // }

    public function device(){
        return $this->belongsTo('App\Device', 'DEVICEID');
    }
}
