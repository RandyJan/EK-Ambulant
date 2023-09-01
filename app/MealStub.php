<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class MealStub extends Model
{
    // 
    use Eloquence, Mappable, Mutable;

    protected $table    = 'InParkCurrencyDetails';
    public $timestamps  = false;

    /**
     * Model Mapping
     */
    protected $maps = [
        'branch_id'         => 'BRANCHID',
        'validity_date'     => 'DATEVALIDITY',
        'serial_number'     => 'SERIALNUMBER',
        'status'            => 'STATUS',        // 0 = not use | 1 is used
        'reference_id'      => 'REFERENCE_ID',   //  
        'used'              => 'USED',   //  
        'balance'           => 'BALANCE',   //  
        'type'              => 'TYPE' // MS = mealstub 
    ];

    public static function findBySerial($val){
        return static::where('SERIALNUMBER', $val)->first();
    }


    /**
     * Relationship
     */
    public function components(){
        return $this->hasMany('App\MealstubComponents', 'REFERENCEID', 'REFERENCE_ID');
    }
}
