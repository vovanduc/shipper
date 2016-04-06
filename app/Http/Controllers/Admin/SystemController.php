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

    public function download_backup($file_name)
    {
        // Check if file exists in app/storage/file folder
        $file_path = storage_path() .'/app/backup/'. $file_name;
        if (file_exists($file_path))
        {
            // Send Download
            return \Response::download($file_path, $file_name, [
                'Content-Length: '. filesize($file_path),
                'Content-Type' => 'application/zip',
            ]);
        }
        else
        {
            exit('Requested file does not exist on our server!');
        }
    }
}
