<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Lavacharts;

class ReportsController extends Controller
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

     public function shippers()
     {
        $month = \Request::query('month') ? \Request::query('month') : 0;

        $result = \Shipper::where('deleted', 0);

        $result = $result->get()->sortByDesc(function($shipper)
        {
            return $shipper->packages->count();
        });

        if($month == 12) {
            $temp = \Package::get();
            foreach($temp as $item) {
                if ($item->distance <= 2000) {
                    print $item->distance.' - '.$item->kgs.' - '.$item->price.'<br/>';
                    $item->price = $item->distance * $item->kgs * 1;
                    $item->save();
                }
            }
        }



        return view('admin.report.shippers', compact('result'))
         ->with('month',$month);
    }

    // public function shippers()
    // {
    //     $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
    //     $shipper = \Request::query('shipper') ? \Request::query('shipper') : 0;
    //     $status = \Request::query('status') ? \Request::query('status') : 0;
    //     $month = \Request::query('month') ? \Request::query('month') : 0;
    //
    //     $times = array(
    //         1=>'Trong ngày',
    //         2=>'Trong tuần',
    //         3=>'Trong tháng',
    //         4=>'Trong năm',
    //     );
    //     $time = \Request::query('time') ? \Request::query('time') : 0;
    //
    //     if ($shipper) {
    //         $result = \Shipper::where('deleted', 0)->whereUuid($shipper);
    //     } else {
    //         $result = \Shipper::where('deleted', 0);
    //     }
    //
    //     $date = array();
    //     if ($time == 1) { // Day
    //         $date = array(\Carbon::now()->startOfDay(), \Carbon::now()->endOfDay());
    //     } else if ($time == 2) { // Week
    //         $date = array(\Carbon::now()->startOfWeek(), \Carbon::now()->endOfWeek());
    //     } else if ($time == 3) { // Month
    //         $date = array(\Carbon::now()->startOfMonth(), \Carbon::now()->endOfMonth());
    //     } else if ($time == 4) { // Year
    //         $date = array(\Carbon::now()->startOfYear(), \Carbon::now()->endOfYear());
    //     }
    //
    //
    //     $result = $result->get()->sortByDesc(function($shipper)
    //     {
    //         return $shipper->packages->count();
    //     });
    //
    //     return view('admin.report.shippers', compact('result'))
    //     ->with('shippers', $shippers)
    //     ->with('shipper', $shipper)
    //     ->with('times', $times)
    //     ->with('time', $time)
    //     ->with('date',$date)
    //     ->with('status',$status)
    //     ->with('month',$month);
    // }




}
