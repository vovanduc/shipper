<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImportsController extends Controller
{
    protected $status = 2;

    protected $shipment_id = '180-95308485';
    protected $date = '2016-06-14';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function excel()
    {
        //print 'Hello';exit;

        require_once base_path('vendor/faisalman/simple-excel-php/src/SimpleExcel/SimpleExcel.php');
        $excel = new \SimpleExcel\SimpleExcel('CSV');

        ############################################################################

        $excel->parser->loadFile(base_path('public/assets/admin/excel/14.06.20161.csv'));
        $count = 1;
        for ($i=9; $i <= 221 ; $i++) {
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

            $shipment_id = $this->shipment_id;

            // Check invoice
            if(\Package::whereInvoice($invoice)->first()) {
                continue;
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
                    $shipper_id = $uuid;
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
                    $customer_id = $uuid;
                }
            }

            if ($shipment_id) {
                $shipment_data = \Shipment::where('key', $shipment_id)->first();
                if ($shipment_data) {
                    $shipment_id = $shipment_data->uuid;
                } else {
                    $uuid = \Uuid::generate(4)->string;
                    $input =  array(
                        'uuid' => $uuid,
                        'key' => $shipment_id,
                    );
                    \Shipment::create($input);
                    $shipment_id = $uuid;
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
                'label' => \Package::create_label(),
                'status' => $this->status,
                'shipment_id' => $shipment_id,
                'date' => new \DateTime($this->date),
            );

            if ($shipper_id && $customer_id && $shipment_id) {
                $result = \Package::create($input);
                if ($result) {
                    print 'ok '.$count.'<br/>';
                } else {
                    print 'error A '.$i.'<br/>';
                }
            } else {
                print 'error B '.$i.'<br/>';
            }

            $count++;
        }
        ############################################################################


        die;
    }


}
