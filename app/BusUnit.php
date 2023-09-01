<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class BusUnit extends Model
{
    use Eloquence, Mappable, Mutable;

    protected $table        = 'BusUnit';
    public $timestamps      = false;

    /**
     * Model Mapping
     */
    protected $maps = [
        'unit_id'      => 'BSUNITCODE',
        'master_id'   =>  'MASTERCODE',
        'description'   => 'DESCRIPTION'
    ];
    protected $getterMutators = [
        'unit_id' => 'trim',
        'description' => 'trim',

    ];
}
