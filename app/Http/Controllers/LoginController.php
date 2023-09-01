<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use App\Helpers\Helper;
use App\Device;
use App\Branches;
use App\Outlet;
use App\UserDevice;

use App\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    //
    public function showLogin(Request $request){

        return view('pages.login');
    }

    public function login(Request $request){
        // dd($request->cookie('device_id'));
        try{
            DB::beginTransaction();

            $helper = new Helper;
            $uname  = $request->username;
            $upass  = $request->password;

            $user = User::findByUsername($uname);

            if( is_null($user) ){
                DB::rollback();
                // return response()->json([
                //     'success'   => false,
                //     'status'    => 401,
                //     'message'   => 'Invalid Username'
                // ]);
                return back()->with('error','Invalid Username');
            }

            if ( $user->password != $upass ) {
                DB::rollback();
                // return response()->json([
                //     'success'   => false,
                //     'status'    => 401,
                //     'message'   => 'Invalid Password'
                // ]);
                return back()->with('error','Invalid Password');
            }

            // check if it is on duty
            // $isOnDuty = $user->isOnDuty($helper->getClarionDate(now()));
            // if( !$isOnDuty ){
            //     return back()->with('error','Not on duty!');
            // }

            // check if the device_id is match from the user device id
            // dd( $user->userDevice( $request->cookie('device_id') ));
            // dd($request->cookie('device_id'));
            if( !$user->userDevice( $request->cookie('device_id') ) ){
                return back()->with('error','User is not allowed to login in this device');
            }



            Auth::login($user);

            DB::commit();

            $request->session()->flash('loggedin', true);
            return redirect('/');

        }catch( \Exception $e){
            DB::rollback();
            \Log::error( $e->getMessage() );
            return back()->with('error','SERVER ERROR');
        }
    }

    public function logout(){
        if(Auth::check()){
            Auth::logout();
        }
        return redirect('/login');
    }

    public function details(){

        // $details = Device::where('_id', getDeviceId() )
        //         ->first();

        // $name = UserDevice::where('device_id',getDeviceId())
        //                 ->get(['BRANCHID','OUTLETID'])
        //                 ->first();


        // branch
        // outlet
        // terminal id

        $device = Device::find( getDeviceId() );

        // dd(
        //     $device->branch_id,
        //     $device->getOutletByBranchId($device->branch_id)->description,
        //     $device->outlets
        // );

        return view('pages.login', compact('device'));
    }
}

