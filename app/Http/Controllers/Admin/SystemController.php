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
        $count_packages = \Input::get('count_packages');

        if ($id)
        {
            if ($count_packages) {
                $data = array();
                $district = \District::where('provinceid',$id)->get();
                foreach($district as $item) {
                    if($item->packages()->whereStatus(3)->count()) {
                        $data[$item->districtid] = $item->name.' ('.$item->packages()->whereStatus(3)->count().')';
                    }
                }
            } else {
                $data = \District::where('provinceid',$id)->lists("name","districtid");
            }

            return \Response::Json(array("value"=>$data));
        }
    }

    public function get_backup()
    {
        $files = \Storage::files('backup');

        //dd(\Storage::get('backup/CARGO MANIFEST_ 03082016.xls - Sheet1.csv.zip'));


        return view('admin.system.backup', compact('files'));
    }
}
