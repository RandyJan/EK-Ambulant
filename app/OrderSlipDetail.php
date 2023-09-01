<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class OrderSlipDetail extends Model
{
    //
    use Eloquence, Mappable, Mutable;
    //
    protected $table 		= 'OrderSLipDetails';
    public $incrementing    = false;
    public $timestamps 		= false;

    //model mapping
    protected $maps = [
        'part'                  => ['part_id', 'description','shortcode'],
    	'branch_id' 			=> 'BRANCHID',
    	'orderslip_detail_id' 	=> 'ORDERSLIPDETAILID',
        'orderslip_header_id' 	=> 'ORDERSLIPNO',
    	'product_id' 			=> 'PRODUCT_ID',
    	'part_number'			=> 'PARTNO',
    	'product_group_id'		=> 'PRODUCTGROUP',
    	'qty' 					=> 'QUANTITY',
    	'srp' 					=> 'RETAILPRICE',
		'amount' 				=> 'AMOUNT',
        'net_amount'            => 'NETAMOUNT',
		'remarks'				=> 'REMARKS',
		'order_type'			=> 'OSTYPE',    // 1=dinein, 2=takeout, 3=bulk-order, 4=others
		'status'				=> 'STATUS', // X = to be able to see in the kitchen and process
		'postmix_id' 			=> 'POSTMIXID',
		'is_modify'				=> 'IS_MODIFY',
        'line_number'           => 'LINE_NO',
        'old_comp_id'           => 'OLD_COMP_ID',
        'order_no' 			    => 'ORNO',
        'sequence'              => 'SEQUENCE',
		'customer_id'			=> 'CUSTOMERCODE',
        'encoded_date'          => 'ENCODEDDATE',
        'main_product_id'       => 'MAIN_PRODUCT_ID',
        'main_product_comp_id'  => 'MAIN_PRODUCT_COMPONENT_ID',
        'main_product_comp_qty' => 'MAIN_PRODUCT_COMPONENT_QTY',
        'guest_no'              => 'GUESTNO',
        'guest_type'            => 'GUEST_TYPE',
        'device_number'         => 'DEVICENO', // device id of the original creator of the order
        'outlet_id'             => 'OUTLETID',
        'table_no'              => 'TABLENO',
        'kitchen_loc'           => 'LOCATIONID',
        'os_date'               => 'OSDATE',
        'display_kds'           => 'DISPLAYMONITOR',
        'dev_id_mod'            => 'DEV_MOD', // id of the device which modifies the order after taking over
        'mealstub_product_id'   => 'MEAL_STUB_PRODUCT_ID',
        'mealstub_serialnumber' => 'MEAL_STUB_SERIAL_NUMBER',
        'pos_line_no'           => 'POSLINENO',
    ];

    protected $getterMutators = [
        'part_number'   => 'trim',
        'order_type'    => 'trim',
        'part.shortcode'=> 'trim'
    ];

    /**
     * Relationship
     */
     public function osByBranch(){
        return $this->belongsTo('App\User', 'AMBULANT_BRANCH_ID', 'branch');
    }
    public function part(){
        return $this->belongsTo('App\Part','product_id');
    }

    public function sitePart(){
        return $this->belongsTo('App\SitePart','product_id')
            ->where('ARNOC', getUserBranchId());
    }
    public function sitePartComponents(){
        return $this->hasMany('App\SitePart');
    }



    /**
     * Logic
     */
    public function getNewId($branch_id=null, $outlet_id=null, $device_no=null){

        $result = static::where('branch_id', $branch_id)
                    ->where('outlet_id', $outlet_id)
                    ->where('device_number', $device_no)
    				->orderBy('orderslip_detail_id','desc')
                    ->first();

        if( is_null($result)){
            return 1;
        }
    	return $result->orderslip_detail_id + 1;
    }

    public function getOrderTypeValue($str, $bool = null){
        if($str == 'true'){
            return 2; // take out
        }else {
            return 1; // dine in
        }
    }

    public function getByOrderSlipHeaderId($id){
        return static::where('orderslip_header_id',$id)
            ->where('branch_id', config('settings.branch_id'))
            ->get();
    }

    public function getNewSequence($branch_id, $header_id, $product_id, $outlet_id=null, $device_id=null){
        $result = static::where('branch_id', $branch_id)
                    ->where('orderslip_header_id',$header_id)
                    ->where('product_id',$product_id)
                    ->when( $outlet_id != null, function($q) use($outlet_id){
                        return $q->where('outlet_id', $outlet_id);
                    })
                    ->when( $device_id != null, function($q) use($device_id){
                        return $q->where('device_number', $device_id);
                    })
                    ->orderBy('encoded_date','desc')
                    ->first();

        if(is_null($result)){
            return 1;
        }else{
            return $result->sequence+1;
        }
    }

    public function removeByHeaderIdAndBranchId( $header_id,
        $branch_id,
        $outlet_id,
        $device_id,
        $main_product_id,
        $sequence
        ){
        return static::where('orderslip_header_id',$header_id)
            ->where('branch_id',$branch_id)
            ->where('outlet_id',$outlet_id)
            ->where('device_number', $device_id)
            ->where('main_product_id', $main_product_id)
            ->where('sequence', $sequence)
            ->get();
            // ->delete();
    }

    public function getSingleOrder(
        $header_id,
        $branch_id,
        $outlet_id,
        $device_id,
        $main_product_id,
        $sequence
    ){
        return static::where('orderslip_header_id',$header_id)
            ->where('branch_id',$branch_id)
            ->where('outlet_id',$outlet_id)
            ->where('device_number', $device_id)
            ->where('main_product_id', $main_product_id)
            ->where('sequence', $sequence)
            ->get();
    }

    public static function getLastLineNumber($branch_id, $os_id, $outlet_id, $device_id){
        $result =  static::where('branch_id', $branch_id)
            ->where('orderslip_header_id',$os_id)
            ->where('outlet_id', $outlet_id)
            ->where('device_number', $device_id)
            ->orderby('line_number','desc')
            ->first();

        if($result == null){
            return 0;
        }

        return $result->line_number;
    }

    public static function getMainOrderCount($id){
        return static::where('orderslip_header_id',$id)
        ->where('branch_id', config('settings.branch_id'))
        ->where('main_product_id', 'product_id')
        ->count();

    }

    public static function getMealstubNewSequence($branch_id, $header_id, $product_id, $outlet_id=null, $device_id=null){
        $result = static::where('branch_id', $branch_id)
                    ->where('orderslip_header_id',$header_id)
                    ->where('mealstub_product_id',$product_id)
                    ->when( $outlet_id != null, function($q) use($outlet_id){
                        return $q->where('outlet_id', $outlet_id);
                    })
                    ->when( $device_id != null, function($q) use($device_id){
                        return $q->where('device_number', $device_id);
                    })
                    ->orderBy('encoded_date','desc')
                    ->first();

        if(is_null($result)){
            return 1;
        }else{
            return $result->sequence+1;
        }
    }

    // public function printComponents(){
    //     return $this->hasMany('App\OrderSlipDetail','ORDERSLIPNO','orderslip_header_id')
    //     ->where('outlet_id',$this->outlet_id)
    //     ->where('main_product_comp_id',$this->main_product_comp_id)
    //     ->where('main_product_comp_qty',$this->main_product_comp_qty)
    //     ->get();
    // }


}

