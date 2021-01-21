<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function ip_details()
    {
        $ip = '103.239.147.187'; //For static IP address get
        //$ip = request()->ip(); //Dynamic IP address get
        $data = \Location::get($ip);                
        return view('details',compact('data'));
    }
}
