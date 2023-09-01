<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class OrderslipTable extends Model
{
    //
    use Eloquence, Mappable, Mutable;

    protected $table        = 'OrderslipTables'; 
    public $timestamps      = false;

    // [branch_id]
    // ,[orderslip_id]
    // ,[table_id]
    // ,[table_number]
    // ,[created_at]

    /**
     * Relationshit
     */
    public function guests(){
        return $this->hasMany(  'App\GuestFile','ORDERSLIPNO','orderslip_id' )
            ->where('BRANCHID',$this->branch_id)
            ->where('TABLENO',$this->table_id)
            ->get();
    }
}
