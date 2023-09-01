<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserDevice;
use App\MealStub;
use App\Device;

class BrowserController extends Controller
{
    //
    public function userDevices(){ 
        $items = UserDevice::with('device')
                    ->get(); 

        return view('pages.browser.user-devices', compact('items'));
    }

    public function mealstub(){
        $items = MealStub::where('TYPE','MS')
                    ->orderBy('DATECREATED','DESC')
                    ->take(100)
                    ->get(); 

        return view('pages.browser.mealstub', compact('items'));
    }

    public function terminals(){
        $items = Device::all(); 
        
        return view('pages.browser.terminals', compact('items'));
    }
}
