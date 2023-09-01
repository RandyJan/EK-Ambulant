<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class Holiday extends Model{

    protected $table    = 'Holidays';
    public $timestamps  = false;
    protected $primaryKey   = 'DATE';

    protected $maps = [
        'date'              => 'DATE',
        'description'       => 'DESCRIPTION',
        'type'              => 'TYPE',
    ];
}
