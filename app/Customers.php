<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class Customers extends Model
{
    //
    use Eloquence, Mappable, Mutable;

    protected $table        = 'Customers';
    public $timestamps      = false;
    protected $fillable = ['MOBILE_NUMBER'];

    protected $maps = [
        'branch_id'             => 'BRANCHID',
        'customer_id'           => 'CUSTOMERID',
        'name'                  => 'NAME',
        'mobile'                => 'MOBILE_NUMBER',
        'birthdate'             => 'BIRTHDATE'
        
    
    ];

    public function osheader(){
        return $this->belongsTo('App\OrderSlipHeader','CELLULARNUMBER','mobile');
    }
    public function getNewCustomerId(){
        $result = static::orderBy('CUSTOMERID','desc')
                    ->first();

        if( is_null($result)){
            return 1;
        }			
        return $result->customer_id + 1;
    }

}
