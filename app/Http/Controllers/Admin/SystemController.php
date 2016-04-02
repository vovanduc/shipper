<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{

    public function __construct()
    {

    }

    public function get_district()
    {
        $id = \Input::get('id');
        if ($id)
        {
            $data = \District::where('provinceid',$id)->lists("name","districtid");

            return \Response::Json(array("value"=>$data));
        }
    }
}
