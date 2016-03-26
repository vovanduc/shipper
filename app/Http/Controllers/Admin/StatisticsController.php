<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
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
    public function statistics()
    {
        $list_shipper = \Shipper::where('deleted', 0)->limit(20)->get()->sortByDesc(function($shipper)
        {
            return $shipper->packages->count();
        });

        return view('admin.statistics.shippers')->with('list_shipper', $list_shipper);
    }

    public function customers()
    {
        $list_customer = \Customer::where('deleted', 0)->limit(20)->get()->sortByDesc(function($customer)
        {
            return $customer->packages->count();
        });

        return view('admin.statistics.customers')->with('list_customer', $list_customer);
    }
}
