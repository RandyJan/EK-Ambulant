<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\OrderslipHeader;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class User extends Authenticatable
{
    use Notifiable, Eloquence, Mappable, Mutable;

    protected $table        = 'UserSite';
    protected $primaryKey   = 'ID';
    public $timestamps      = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Model Mapping
     */
    protected $maps = [  
        '_id'             => 'ID',
        'username'        => 'NUMBER',
        'password'        => 'PW',
        'api_token'       => 'TOKEN',
        'name'            => 'NAME',
        'outlet_id'       => 'OUTLETID',
        'device_no'       => 'DEVICENO',
        'branch'          => 'BRANCHID',
        'level'           => 'LEVEL'
    ];

    protected $getterMutators = [
        'password'  => 'trim',
        'name'      => 'trim'
    ];

    
    /**
     * Logic 
     */
      public function partLocation(){
      return $this->hasMany('App\PartLocation','BRANCHID','branch_id');
    }
      public function branch(){
        return $this->hasMany('App\Branches','BRANCHID','branch_id');
      }
      public function osDetail(){
      return $this->hasMany('App\OrderSlipDetail','BRANCHID','branch_id');
    }
      public function orderSlipHeader(){
      return $this->hasMany('App\OrderSlipHeader', 'BRANCHID','branch_id');
    }
      public function outlet(){
      return $this->hasMany('App\Outlet','BRANCHID','branch_id');
    }

    public function device(){
      return $this->hasMany('App\Device','STATIONCODE','branch_id');
    }
    // public function branch(){
    //     return $this->hasMany('App\OrderSlipHeader','AMBULANT_BRANCH_ID','branch');
      
    // }
    public static function findByUsername($username){
        return static::where('username', $username)->first();
      }
  
      public static function findByToken($token){
        return static::where('token', $token)->first();
      }
  
      public function isOnDuty($clarionDate){
          
          $result = $this->duties->sortByDesc('date')->first();  
          
          if( is_null($result) ){
              return false;
          }
   
          if( $clarionDate == $result->date){
            return $result;
          }
  
          return false;
      }
  
      public function current_outlet($clarionDate){
        return $this->isOnDuty($clarionDate)->outlet;
      }

      public function duty(){ 
        return $this->duties->sortByDesc('DATE')->first();  
      }

      public function activeOrder(){

        $filtered =  $this->hasMany('App\OrderSlipHeader','ENCODEDBY','username')
                // ->where('status','!=','C')
                ->where('BRANCHID', getUserBranchId() )
                ->where('outlet_id', getUserOutletId() )
                // ->where('device_no', $this->device_no)
                ->where('is_active', 1)
                // ->orderBy('orderslip_header_id','desc')
                ->first();
        // dd( $filtered );
        return $filtered;
      }
      public function userDetail($branch,$outlet){
        return $this->userDetails()
        ->where('_id',$this->_id)
        ->where('branch',$branch)
        ->where('outlet_id',$outlet)
        ->first();
      }
      public function userDevice($device_id){
        return $this->userDevices()
          // ->where('ID', $this->ID)
          // ->where('DEVICEID', $device_id)
          ->where('_id', $this->_id)
          ->where('device_id', $device_id)
          ->where('active_status', 0)
          ->first();
      }
  
      /**
       * Relationship 
       */
      public function duties(){
        return $this->hasMany('App\OnDuty', 'CCENUMBER', 'username');
      }
      
      public function orders(){
        return $this->hasMany('App\OrderSlipHeader','ENCODEDBY','username');
      }

      public function pendingOrders(){
        return $this->hasMany('App\OrderSlipHeader','ENCODEDBY','username')
                ->where('status','!=','C')
                ->where('branch_id','branch')
                // ->where('outlet_id', $this->duty()->storeOutlet->outlet_id)
                // ->where('device_no', $this->duty()->device_no)
                ->orderBy('orderslip_header_id','desc')
                ->get();
      }

      public function userDevices(){
        return $this->hasMany('App\UserDevice', 'ID', 'ID');
      }

      public function users(){
         return static::all();

         return view('/pages/admin/admin',compact('users'));
      }
      public function orderSummary(){
       
        $os = $this->hasMany('App\OrderSlipHeader','ENCODEDBY','username')
            ->where('branch_id', getUserBranchId() )
            ->where('outlet_id', getUserOutletId() )
            ->orderby('encoded_date', 'desc')
            ->whereDate('created_at', Carbon::today()->toDateString())
            // ->where('is_paid',0)
            ->first();

        dd($os);
       
        return $os;
    }
    
 
      
}
