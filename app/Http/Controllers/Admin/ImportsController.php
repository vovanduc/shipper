<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImportsController extends Controller
{
    protected $status = 2;

    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function excel()
    {
        print 'Hello';exit;

        require_once base_path('vendor/faisalman/simple-excel-php/src/SimpleExcel/SimpleExcel.php');
        $excel = new \SimpleExcel\SimpleExcel('CSV');

        ############################################################################
        $excel->parser->loadFile(base_path('public/assets/admin/excel/25.03.20161.csv'));
        for ($i=9; $i <= 143 ; $i++) {
            for ($k=1; $k <= 15 ; $k++) {
                $string = $excel->parser->getCell($i,$k);
                if ($k==2) $invoice = $excel->parser->getCell($i,$k);
                if ($k==3) $service_type = $excel->parser->getCell($i,$k);
                if ($k==5) $weight = $excel->parser->getCell($i,$k);
                if ($k==6) $shipper_id = $excel->parser->getCell($i,$k);
                if ($k==7) $shipper_address = $excel->parser->getCell($i,$k);
                if ($k==8) $customer_id = $excel->parser->getCell($i,$k);
                if ($k==9) $customer_address = $excel->parser->getCell($i,$k);
                if ($k==10) $content = $excel->parser->getCell($i,$k);
                if ($k==15) $kgs = $excel->parser->getCell($i,$k);
            }

            if ($shipper_id) {
                $shipper_data = \Customer::where('name', $shipper_id)->first();
                if ($shipper_data) {
                    $shipper_id = $shipper_data->uuid;
                } else {
                    $uuid = \Uuid::generate(4)->string;
                    $input =  array(
                        'uuid' => $uuid,
                        'email' => $uuid.'@gmail.com',
                        'name' => $shipper_id,
                        'address' => $shipper_address,
                    );
                    \Customer::create($input);
                    $shipper_id = '';
                }
            }

            if ($customer_id) {
                $customer_data = \Customer::where('name', $customer_id)->first();
                if ($customer_data) {
                    $customer_id = $customer_data->uuid;
                } else {
                    $uuid = \Uuid::generate(4)->string;
                    $input =  array(
                        'uuid' => $uuid,
                        'email' => $uuid.'@gmail.com',
                        'name' => $customer_id,
                        'address' => $customer_address,
                    );
                    \Customer::create($input);
                    $customer_id = '';
                }
            }

            $uuid = \Uuid::generate(4)->string;
            $input =  array(
                'uuid' => $uuid,
                'parent' => $uuid,
                'customer_from' => $shipper_id,
                'customer_id' => $customer_id,
                'invoice' => $invoice,
                'service_type' => $service_type,
                'weight' => $weight,
                'content' => $content,
                'kgs' => $kgs,
                'address' => $customer_address,
                'label' => 'label-'.$i,
                'status' => $this->status,
            );

            if ($shipper_id && $customer_id) {
                $result = \Package::create($input);
                if ($result) {
                    print 'ok '.$i.'<br/>';
                } else {
                    print 'error A '.$i.'<br/>';
                }
            } else {
                print 'error B '.$i.'<br/>';
            }
        }
        ############################################################################
        print '<br/>############################################################################<br/>';
        ############################################################################
        $excel->parser->loadFile(base_path('public/assets/admin/excel/29.03.20161.csv'));
        for ($i=9; $i <= 232 ; $i++) {
            for ($k=1; $k <= 15 ; $k++) {
                $string = $excel->parser->getCell($i,$k);
                if ($k==2) $invoice = $excel->parser->getCell($i,$k);
                if ($k==3) $service_type = $excel->parser->getCell($i,$k);
                if ($k==5) $weight = $excel->parser->getCell($i,$k);
                if ($k==6) $shipper_id = $excel->parser->getCell($i,$k);
                if ($k==7) $shipper_address = $excel->parser->getCell($i,$k);
                if ($k==8) $customer_id = $excel->parser->getCell($i,$k);
                if ($k==9) $customer_address = $excel->parser->getCell($i,$k);
                if ($k==10) $content = $excel->parser->getCell($i,$k);
                if ($k==15) $kgs = $excel->parser->getCell($i,$k);
            }

            if ($shipper_id) {
                $shipper_data = \Customer::where('name', $shipper_id)->first();
                if ($shipper_data) {
                    $shipper_id = $shipper_data->uuid;
                } else {
                    $uuid = \Uuid::generate(4)->string;
                    $input =  array(
                        'uuid' => $uuid,
                        'email' => $uuid.'@gmail.com',
                        'name' => $shipper_id,
                        'address' => $shipper_address,
                    );
                    \Customer::create($input);
                    $shipper_id = '';
                }
            }

            if ($customer_id) {
                $customer_data = \Customer::where('name', $customer_id)->first();
                if ($customer_data) {
                    $customer_id = $customer_data->uuid;
                } else {
                    $uuid = \Uuid::generate(4)->string;
                    $input =  array(
                        'uuid' => $uuid,
                        'email' => $uuid.'@gmail.com',
                        'name' => $customer_id,
                        'address' => $customer_address,
                    );
                    \Customer::create($input);
                    $customer_id = '';
                }
            }

            $uuid = \Uuid::generate(4)->string;
            $input =  array(
                'uuid' => $uuid,
                'parent' => $uuid,
                'customer_from' => $shipper_id,
                'customer_id' => $customer_id,
                'invoice' => $invoice,
                'service_type' => $service_type,
                'weight' => $weight,
                'content' => $content,
                'kgs' => $kgs,
                'address' => $customer_address,
                'label' => 'label-'.$i,
                'status' => $this->status,
            );

            if ($shipper_id && $customer_id) {
                $result = \Package::create($input);
                if ($result) {
                    print 'ok '.$i.'<br/>';
                } else {
                    print 'error A '.$i.'<br/>';
                }
            } else {
                print 'error B '.$i.'<br/>';
            }
        }
        ############################################################################

        die;
    }


}
