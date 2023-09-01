<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Outlet;

class OutletController extends Controller
{
    //
    public function list(){
        $outlets = Outlet::getAllByBranch();
        return view('pages.outlets', compact('outlets'));
    }

    public function select($id){

        $user = auth()->user();
        $user->outlet_id = $id;
        $user->save();

        return redirect()->route('home');
    }
   
}
