<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class PartLocation extends Model
{
    // 

    use Eloquence, Mappable, Mutable;
    //
    protected $table    = 'PartsLocation';
    public $timestamps  = false;

    /**
     * Model Mapping
     */
    protected $maps = [
        'product_id'        => 'PRODUCT_ID',
        'outlet_id'         => 'OUTLETID',
        'description'       => 'DESCRIPTION',
        'group_id'          => 'GROUP',
        'category_id'       => 'CATEGORY',
        'short_code'        => 'SHORTCODE',
        'retail'            => 'RETAIL',
        'postmix'           => 'POSTMIX',
        'prepartno'         => 'PREPARTNO',
        'ssbuffer'          => 'SSBUFFER',
        'is_food'           => 'MSGROUP',
        'qty'               => 'QUANTITY',
        'kitchen_location'  => 'PRODGRP',
        'part_number'       => 'PARTNO',
        'branch_id'         => 'BRANCHID',
        'bus_unit'          => 'BUSUNIT', 
        'master_code'       => 'MASTERCODE'
    ];

    protected $getterMutators = [
        'description'   => 'trim',
        'group_id'      => 'trim',
        'category'      => 'trim',
        'short_code'    => 'trim',
        'part_number'   => 'trim'
    ];

    /**
     * RELATIONSHIT
     */
     public function partLocationByBranch(){
        return $this->belongsTo('App\User', 'AMBULANT_BRANCH_ID', 'branch');
    }
    public function group(){
        return $this->belongsTo('App\Group', 'group_id');
    }

    public function part(){
        return $this->belongsTo('App\Part','product_id');
    }
    
    public function postmixModifiableComponents(){
        return $this->hasMany('App\Postmix','product_id','product_id')
            ->where('modifiable',1);
    }

    public function postmixNoneModifiableComponents(){ 
       return $this->hasMany('App\Postmix','product_id','product_id')
            ->where('modifiable',0)
            ->where('display',1);
    }

    public function postmixComponents(){ 
        return $this->hasMany('App\Postmix','product_id','product_id')
             ->where('display',1);
    }

    // public function mealstubComponents(){
    //     return $this->hasMany('App\MealstubComponents','PRODUCTID','PRODUCT_ID');
             
    // }

    public function category(){
        
    }

     /**
      * LOGIC
      */
    public static function getByOutletAndGroupAndCategory($outlet_id, $gid, $cid, $limit = 15){
        return static::where('outlet_id', $outlet_id)
                ->where('group_id', $gid)
                ->where('category_id', $cid)
                ->simplePaginate($limit);
    }

    public static function byProductAndOutlet($product_id,$outlet_id){
        return static::where('outlet_id',  $outlet_id )
            ->where('product_id', $product_id)
            ->first(); 
    }

    public static function byBranchProductAndOutlet($product_id,$outlet_id, $branch_id){
        return static::where('outlet_id',  $outlet_id )
            ->where('branch_id', $branch_id)
            ->where('product_id', $product_id)
            ->first(); 
    }

    public static function byCategoryOfProductPerOutlet($category_id,$product_id,$outlet_id, $branch_id=null){
        return static::where('category_id', $category_id)
            ->where('product_id','!=',$product_id)
            ->where('outlet_id', $outlet_id)
            ->where('branch_id', $branch_id)
            ->get();
    }

}
