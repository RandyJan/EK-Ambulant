<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait


class MealstubComponents extends Model
{
  // 
  use Eloquence, Mappable, Mutable;

  protected $table    = 'MealstubComponents';
  public $timestamps  = false;

  /**
   * Model Mapping
   */
  protected $maps = [
      'reference_id'      => 'REFERENCEID',   // this is the product in sitepart
      'line_no'           => 'LINENO',
      'product_id'        => 'PRODUCTID',     // modified product
      'postmix_id'        => 'POSTMIXID',  
      'default_product_id' => 'DEFAULTPRODID',  // default product
      'qty'               => 'QTY',           
      'is_modifiable'     => 'ISMODIFIABLE'
  ];


  public function partLocation(){
    return $this->belongsTo('App\PartLocation','DEFAULTPRODID','PRODUCT_ID');
  }
  public function reference(){
    return $this->belongsTo('App\Mealstub','REFERENCEID','REFERENCE_ID');
  }

  
}
