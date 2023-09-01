<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait 

class Branches extends Model
{
    use Eloquence, Mappable, Mutable;

    protected $table        = 'Branches';
    public $timestamps      = false;

    /**
     * Model Mapping
     */
    protected $maps = [
        'branch_id'      => 'BRANCHID',
        'branch_name'   =>  'BRANCHNAME'
    ];

    public function branches(){
        return $this->belongsTo('App\User','AMBULANT_BRANCH_ID','branch');
    }

    
    
}
