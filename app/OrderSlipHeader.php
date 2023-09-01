<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class OrderSlipHeader extends Model
{
    //
    use Eloquence, Mappable, Mutable;
    //
    protected $table 		= 'OrderSlipHeader';
    public $incrementing    = false;
    public $timestamps 		= false;

    //model mapping
    protected $maps = [
        // simple alias
          'orderslip_header_id'	    => 'ORDERSLIPNO',
          'branch_id' 			        => 'BRANCHID',
          'branch_services_id'      => 'BRANCHSERVICESID',
          'transaction_type_id'	    => 'TRANSACTTYPEID',
          'total_amount'			      => 'TOTALAMOUNT',
          'discount_amount'		      => 'DISCOUNT',
          'net_amount' 			        => 'NETAMOUNT',
          'status' 				          => 'STATUS',    // [ 'P' => 'Pending', 'C' => 'Completed'. 'B' => 'CurrentSelected' ]
          'customer_id' 			      => 'CUSTOMERCODE',
          'mobile_number' 		      => 'CELLULARNUMBER',
          'customer_name' 		      => 'CUSTOMERNAME',
          'created_at' 			        => 'OSDATE',
          'orig_invoice_date'       => 'ORIGINALINVOICEDATE',
          'encoded_date'            => 'ENCODEDDATE',   // this can be use as start of duration process
          'encoded_by'              => 'ENCODEDBY',
          'prepared_by'             => 'PREPAREDBY',
          'cce_name'                => 'CCENAME',
          'total_hc'                => 'TOTALHEADCOUNT',
          'outlet_id'               => 'OUTLETID',
          'table_id'                => 'TABLENO',
          'is_active'               => 'ISACTIVE',
          'device_no'               => 'DEVICENO',
          'clarion_date'            => 'DATE',
          'os_no'                   => 'OSNUMBER',
          'is_paid'                 => 'PAID',
          'print'                   => 'DRNUMBER',
          'osnumber'                => 'OSNUMBER',
          'display_kds'             => 'DISPLAYMONITOR',
          'mealstub_count'          => 'MEAL_STUB_COUNT',
          'qdate'                   => 'QDATE', // this is use to get the time out duration process
          'remarks'                 => 'REMARKS',
          'mealstub_product_id'     => 'MEAL_STUB_PRODUCT_ID' // mealstub//
      ];

      protected $getterMutators = [
        'prepared_by'       => 'trim',
        'cce_name'          => 'trim',
        'customer_name'     => 'trim',
        'customer_id'       => 'trim',
        'mobile_number'     => 'trim'
    ];

    /**
     * Relationship
     */
     public function oshByBranch(){
        return $this->belongsTo('App\User', 'AMBULANT_BRANCH_ID', 'branch');
    }
    public function tableHistory(){
      return $this->hasMany('App\TableHistory','ORDERLIPNO','orderslip_header_id');
    }

    public function sitePart(){
      return $this->belongsTo('App\SitePart','product_id');
    }

    public function details(){
      return $this->hasMany('App\OrderSlipDetail','ORDERSLIPNO','orderslip_header_id');
    }

    public function components(){
      return $this->hasMany('App\OrderSlipDetail','ORDERSLIPNO','orderslip_header_id')
      // ->whereColumn('product_id', 'main_product_id')
      // // ->where('device_number', $this->device_no)
      // // ->where('outlet_id', $this->outlet_id)
      // // ->where('branch_id', $this->branch_id)
      // ->orWhereNotNull('main_product_comp_id',$this->main_product_comp_id)
      ->get();
    }
    public function withComponent(){
      return static::where('App\SitePart','product_id')
      ->where('display',1);
    }


    public function itemOnCart(){
      return $this->hasMany('App\OrderSlipDetail','ORDERSLIPNO','orderslip_header_id')
          ->whereColumn('PRODUCT_ID', 'MAIN_PRODUCT_ID')
          ->where(function($query){
              return $query->where('MEAL_STUB_PRODUCT_ID', null)
                ->orWhere(function($subQuery){
                  return $subQuery->whereColumn('MEAL_STUB_PRODUCT_ID', 'PRODUCT_ID')
                    ->whereColumn('MEAL_STUB_PRODUCT_ID', 'MAIN_PRODUCT_ID');
                });
          })
          ->where('outlet_id', $this->outlet_id)
          ->where('branch_id', $this->branch_id)
          ->where('device_number', $this->device_no)
          ->where('status', '!=', 'V')
          ->get();
    }



    public function printComponents(){
      return $this->hasMany('App\OrderSlipDetail','ORDERSLIPNO','orderslip_header_id')
          ->where('device_number', $this->device_no)
          ->where('outlet_id', $this->outlet_id)
          ->where('branch_id', $this->branch_id)
          ->where('status', '!=', 'V')
          ->get();
    }


    public function tables(){
      return $this->hasMany('App\OrderslipTable','orderslip_id','orderslip_header_id')
          ->whereColumn('branch_id', 'branch_id')
          ->whereColumn('table_id', 'table_id')
          ->get();
    }
    /** IF MEALSTUB */


    /**
     * Logic
     */


    public function currentTables(){

      // $details =  $this->details->map(function($item,$key){
      //   return [
      //     'table_no' => $item['TABLENO']
      //   ];
      // });


      // // \Log::debug($item);

      // $col = collect( $details );

      // \Log::debug($this->orderslip_header_id);
      // \Log::debug($details);
      // \Log::debug( $this->details );
      // \Log::debug($col);

      // $tables = $col->unique('table_no');

      // $branch_id  = $this->branch_id;
      // $outlet_id  = $this->outlet_id;
      // $os_id      = $this->orderslip_header_id;

      // $tables = $tables->map(
      //     function($item, $key) use( $branch_id, $outlet_id, $os_id ){
      //   /**
      //    * branch_id,
      //    * outlet_id,
      //    * orderslip_no
      //    * table_no
      //    */
      //   $guests = \App\GuestFile::where('branch_id', $branch_id)
      //               ->where('outlet_id', $outlet_id)
      //               ->where('orderslip_no', $os_id)
      //               ->where('table_no', $item['table_no'])
      //               ->get();

      //   \Log::debug([
      //     'table_no' => $item['table_no']
      //   ]);

      //   return [
      //     'table_no' => $item['table_no'],
      //     'guests' => $guests
      //   ];
      // });

      return $tables;
    }

    public function cartCount(){
      return $this->hasMany('App\OrderSlipDetail','ORDERSLIPNO','orderslip_header_id')
          ->whereColumn('product_id', 'main_product_id')
          ->count();
      // return $this->itemOnCart->count();
    }

    public function getNewId($branch_id=null, $outlet_id=null, $device_no=null){
      $result = static::where('branch_id', $branch_id)
            ->where('outlet_id', $outlet_id)
            ->where('device_no', $device_no)
    				->orderBy('orderslip_header_id','desc')
                    ->first();

        if( is_null($result)){
            return 1;
        }
    	return $result->orderslip_header_id + 1;
    }

    public static function getByBranchAndOutlet($outlet_id){
      return static::whereColumn('BRANCHID', 'BRANCHID')
        ->where('outlet_id', $outlet_id)
        ->orderby('encoded_date', 'desc')
        ->get();
    }

    public static function orderIsPaid($header_id, $branch_id, $outlet_id, $device_id){
        return static::where('orderslip_header_id', $header_id)
        ->where('branch_id', $branch_id)
        ->where('outlet_id', $outlet_id)
        ->where('device_no', $device_id)
        ->select('is_paid')
        ->first();
    }
}
