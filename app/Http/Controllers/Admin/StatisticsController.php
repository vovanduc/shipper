<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Lavacharts;

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

    public function chart()
    {

        $lava = new Lavacharts; // See note below for Laravel

        $finances = \Lava::DataTable();

        $finances->addDateColumn('Packages')
                    ->addNumberColumn('Packages');

        // Random Data For Example
        for ($a = 1; $a <= 12; $a++) {
            $result = \Package::where('deleted', 0)->whereMonth('created_at', '=',$a)->count();
            $finances->addRow([
              '2015-' . $a, $result
            ]);
        }

        \Lava::ColumnChart('Finances', $finances, [
            'title' => 'Packages',
            'titleTextStyle' => [
                'color'    => '#eb6b2c',
                'fontSize' => 14
            ]
        ]);

        return view('admin.statistics.chart');
    }
}
