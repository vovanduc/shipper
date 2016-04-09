<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Customer\ICustomerRepository;

class SystemController extends Controller
{

    public function __construct(ICustomerRepository $customers, Request $request)
    {
        $this->customers = $customers;
        $this->request = $request;
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

    public function add_customer()
    {
        $customer_name = \Input::get('customer_name');
        $customer_email = \Input::get('customer_email') ? \Input::get('customer_email') : time().'@gmail.com';
        $customer_phone = \Input::get('customer_phone') ? \Input::get('customer_phone') : time();
        $customer_address = \Input::get('customer_address') ? \Input::get('customer_address') : time();

        if(!$customer_name) {
            return \Response::Json(array("error"=>'Vui lòng nhập tên khách hàng'));
        } else {
            $check = \Customer::whereName($customer_name)->first();
            if($check) {
                return \Response::Json(array("error"=>'Đã tồn tại tên khách hàng này!'));
            }
        }

        $result = $this->customers->add(array(
            'name' => $customer_name,
            'email' => $customer_email,
            'phone' => $customer_phone,
            'address' => $customer_address,
            'created_by' => \Auth::user()->id
        ));

        if ($result) {
            $mess = \Lang::get('admin.global.add_success').' <b><a target="_blank" href="'.\URL::route('admin.customers.show', $result->uuid).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $result->uuid,
                'contentType' => 'customer',
                'action'      => 'add',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);

            return \Response::Json(array("success"=>true, 'customer'=>array('uuid'=>$result->uuid, 'name'=>$result->name)));
        } else {
            return \Response::Json(array("error"=>trans('admin.global.message_danger')));
        }
    }
}
