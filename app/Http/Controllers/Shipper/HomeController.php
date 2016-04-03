<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $money = array();
        $money['day'] = \Shipper::money(\Auth::guard('shippers')->user()->uuid, 1);
        $money['week'] = \Shipper::money(\Auth::guard('shippers')->user()->uuid, 2);
        $money['month'] = \Shipper::money(\Auth::guard('shippers')->user()->uuid, 3);
        $money['year'] = \Shipper::money(\Auth::guard('shippers')->user()->uuid, 4);

        $money['day_packages'] = \Shipper::countPackages(\Auth::guard('shippers')->user()->uuid, 1);
        $money['week_packages'] = \Shipper::countPackages(\Auth::guard('shippers')->user()->uuid, 2);
        $money['month_packages'] = \Shipper::countPackages(\Auth::guard('shippers')->user()->uuid, 3);
        $money['year_packages'] = \Shipper::countPackages(\Auth::guard('shippers')->user()->uuid, 4);

        return view('shipper.home.index', compact('money'));
    }

}
