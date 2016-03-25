<?php

namespace App\Http\Controllers\Admin;

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
        // $temp = ['fe35b4e3-c8ca-49f1-a3ee-e396584c1461','a4aa0059-1253-48e6-b42f-fdcfa926a688','1da4ab16-6dc4-4cb9-b509-38b0c8e1685d'];
        //
        // $result = \Package::where('deleted', 0)->get();
        // foreach($result as $item) {
        //     \Package::where('uuid', $item->uuid)->update(['status' => rand(1,6), 'location_id' => $temp[rand(0,2)]]);
        // }

        $sum = array();
        for ($i=1; $i<=6 ; $i++) {
            $result = \Package::where('deleted', 0)->where('status', $i)->count();
            $sum[$i] = $result;
        }

        $list_location = \Location::where('deleted', 0)->get();

        return view('admin.home.index')->with('sum', $sum)->with('list_location', $list_location);
    }
}
