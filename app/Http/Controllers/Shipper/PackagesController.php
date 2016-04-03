<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Package\IPackageRepository;
use App\Http\Repositories\Customer\ICustomerRepository;
use App\Http\Repositories\Shipper\IShipperRepository;

class PackagesController extends Controller
{
    protected $packages;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct(
         IPackageRepository $packages,
         Request $request,
         ICustomerRepository $customers,
         IShipperRepository $shippers)
    {
         $this->packages = $packages;
         $this->customers = $customers;
         $this->shippers = $shippers;
         $this->request = $request;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = \Package::where('shipper_id', \Auth::guard('shippers')->user()->uuid)
        ->where('deleted', 0)->orderBy('id', 'DESC')->paginate(10);
        return view('shipper.packages.index', compact('result'))
            ->with('status')
            ->with('label');
    }

    public function search()
    {
        if (!$this->request->has('status') && !$this->request->has('label')) {
            return \Redirect::route('shipper.packages.index');
        }

        $result = \Package::where('shipper_id', \Auth::guard('shippers')->user()->uuid)->where('deleted', 0);

        if ($this->request->has('status')) {
            $result = $result->whereStatus($this->request->status);
        }

        if ($this->request->has('label')) {
            $result = $result->whereLabel($this->request->label);
        }

        $result = $result->orderBy('id', 'DESC')->paginate(10);

        return view('shipper.packages.index', compact('result'))
            ->with('status', $this->request->status)
            ->with('label', $this->request->label);
    }

    public function show($uuid)
    {
        $result = $this->packages->firstOrFail($uuid);
        $result->steps = '';

        \Activity::log([
            'contentId'   => $uuid,
            'contentType' => 'package',
            'action'      => 'view',
            'description' => \Lang::get('admin.global.view').' <b><a target="_blank" href="'.\URL::route('shipper.packages.show', $uuid).'">'.$result->label.'</a></b>',
            'userId'     => \Auth::guard('shippers')->user()->uuid,
        ]);

        return view('shipper.packages.show', compact('result'));
    }

    public function find()
    {
        $shippers = \Shipper::where('deleted', 0)->whereUuid(\Auth::guard('shippers')->user()->uuid)->orderBy('id', 'DESC')->lists('name','uuid');
        $province_id = \Request::query('province_id') ? \Request::query('province_id') : 0;
        $district_id = \Request::query('district_id') ? \Request::query('district_id') : 0;
        $shipper = \Auth::guard('shippers')->user()->uuid;
        $package_id = \Request::query('package_id') ? \Request::query('package_id') : 0;
        $label = \Request::query('label') ? \Request::query('label') : '';
        $mess = \Request::query('mess') ? \Request::query('mess') : '';

        $result = [];
        $random = 0;
        $item = array();

        $show_label = 'Tìm kiếm';

        if (!$shipper || !$province_id || !$district_id) {
            $show_label = 'Vui lòng chọn người giao hàng và tỉnh thành phố quận huyện';
        }

        if ($shipper && $province_id && $district_id) {
            if($label) {
                $result = \Package::where('deleted', 0)->where('province_id',$province_id)->where('district_id',$district_id)
                ->where('status', 3)->where('label', $label)->orderBy('distance')->get();
            } else {
                $result = \Package::where('deleted', 0)->where('province_id',$province_id)->where('district_id',$district_id)
                ->where('status', 3)->orderBy('distance')->get();
            }
        }

        if($shipper && $province_id && $district_id && $package_id) {
            $temp = $this->packages->update($package_id,['status'=>4, 'shipper_id'=>$shipper]);
            if($temp) {
                $data = $this->packages->firstOrFail($package_id);
                $mess = 'Người đi giao hàng: <b>'.\Shipper::whereUuid($shipper)->first()->name.'</b>. Kiện hàng: <a target="_blank" href="'.\URL::route('shipper.packages.show', $package_id).'">'.$data->label.'</a>';

                \Activity::log([
                    'contentId'   => $package_id,
                    'contentType' => 'package',
                    'action'      => 'update',
                    'description' => $mess,
                    'userId'     => \Auth::guard('shippers')->user()->uuid,
                ]);

                return \Redirect::route('shipper.packages.find',['shipper' =>$shipper, 'province_id' =>$province_id, 'district_id' =>$district_id, 'mess' =>$mess]);
            }
        }

        return view('shipper.packages.find', compact('result'))
            ->with('item', $item)
            ->with('show_label', $show_label)
            ->with('shippers', $shippers)
            ->with('shipper', $shipper)
            ->with('mess', $mess)
            ->with('label', $label)
            ->with('province_id', $province_id)
            ->with('district_id', $district_id)
            ;
    }

    public function barcode()
    {
        $status_from = \Request::query('status_from') ? \Request::query('status_from') : 0;
        $status_to = \Request::query('status_to') ? \Request::query('status_to') : 0;
        $label = \Request::query('label') ? \Request::query('label') : '';
        $message = '';

        $result = array();
        if ($status_from && $status_to && $label) {
            $result = $this->packages->findBy('label', $label)->deleted(0)->status($status_from)->first();

            if ($result) {
                $updated = $this->packages->update($result->uuid, array('status' => $status_to));

                if ($updated) {
                    // Refesh data -> show
                    $result = $this->packages->findBy('label', $label)->status($status_to)->first();
                    $result = \Package::convert($result);
                    $message = 'Chuyển trạng thái từ <strong>'.\Package::get_status_option($status_from)
                    .'</strong> đến <strong>'.\Package::get_status_option($status_to).'</strong> <a target="_blank" href="'.\URL::route('shipper.packages.show', $result->uuid).'">'.$result->label.'</a>';

                    \Activity::log([
                        'contentId'   => $result->uuid,
                        'contentType' => 'package',
                        'action'      => 'update',
                        'description' => $message,
                        'userId'     => \Auth::guard('shippers')->user()->uuid,
                    ]);

                } else {
                    print 'Lỗi hệ thống vui lòng liên hệ admin';
                }
            }
        }

        return view('shipper.packages.barcode', compact('result'))
            ->with('status_from', $status_from)
            ->with('status_to', $status_to)
            ->with('message', $message);
    }
}
