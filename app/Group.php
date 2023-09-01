<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class Group extends Model
{
    //
    use Eloquence, Mappable, Mutable;

    protected $table        = 'groups';
    protected $primaryKey   = 'GROUPCODE';
    public $timestamps      = false;
    protected $keyType      = 'string';

    /**
     * Model Mapping
     */
    protected $maps = [
        'group_id'      => 'GROUPCODE',
        'description'   => 'DESCRIPTION',
        'master_code'   => 'MASTERCODE',
        'unit_code'     => 'BSUNITCODE'
    ];

    protected $getterMutators = [
        'description' => 'trim',
        'unit_code'     =>'trim',
        'group_id'    => 'trim'
    ];
}