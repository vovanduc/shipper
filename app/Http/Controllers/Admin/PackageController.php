<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Package\IPackageRepository;
use App\Http\Repositories\Customer\ICustomerRepository;
use App\Http\Repositories\Shipper\IShipperRepository;

class PackagesController extends Controller
{
    protected $packages;

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

    protected function validator(array $data, array $rules)
    {
        return \Validator::make($data, $rules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $response = \GoogleMaps::load('distancematrix')
        //         ->setParamByKey('origins', '262 Bùi Viện, Hồ Chí Minh, Việt Nam')
        //         ->setParamByKey('destinations', '403 Nguyễn Trãi, Hồ Chí Minh, Việt Nam|90/18 Dương Bá Trạc, Hồ Chí Minh, Việt Nam|101 Nguyễn Thị Minh Khai, Hồ Chí Minh, Việt Nam')
        //         ->get();
        // print_r($response);exit;

        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');

        $result = $this->packages->all(20);
        return view('admin.packages.index', compact('result'))
            ->with('customer_id')
            ->with('customer_phone')
            ->with('customer_from')
            ->with('customer_from_phone')
            ->with('shipper_id')
            ->with('shipper_phone')
            ->with('status')
            ->with('county')
            ->with('label')
            ->with('province_id')
            ->with('district_id')
            ->with('customers', $customers)
            ->with('shippers', $shippers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $customers = array(''=>'Chọn người nhận') + $customers->toArray();

        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $shippers = array(''=>'Chọn người đi giao hàng') + $shippers->toArray();

        $location_id = \Location::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $location_id = array(''=>'Chọn vị trí') + $location_id->toArray();

        $shipments = \Shipment::where('deleted', 0)->orderBy('id', 'DESC')->lists('key','uuid');
        $shipments = array(''=>'Chọn lô hàng') + $shipments->toArray();

        return view('admin.packages.create')
            ->with('customers', $customers)
            ->with('shippers', $shippers)
            ->with('location_id', $location_id)
            ->with('shipments', $shipments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($this->request->all(), [
                'label' => 'required|unique:packages',
                'customer_id' => 'required',
                'customer_from' => 'required',
                //'shipper_id' => 'required',
                //'location_id' => 'required',
                'address' => 'required',
                //'county' => 'required',
                'kgs' => 'required',
                //'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.packages.create')->withErrors($validator)->withInput();
        }

        \Input::merge(array('created_by' => \Auth::user()->id));
        //\Input::merge(array('label' => \Package::create_label()));

        $response = \Package::convert_address_to_lat_long($this->request->address);

        \Input::merge(array('latitude' => $response['latitude']));
        \Input::merge(array('longitude' => $response['longitude']));
        //\Input::merge(array('price' => $response['price']));
        \Input::merge(array('distance' => $response['distance']));
        \Input::merge(array('duration' => $response['duration']));
        //\Input::merge(array('steps' => $response['steps']));

        // Calculate price
        if ($response['distance']) {
            $price = $response['distance'] * \Input::get('kgs') * 0.5;
            \Input::merge(array('price' => $price ));
        } else {
            return \Redirect::route('admin.packages.edit', $id)->with('message_danger', 'Không thể lấy được khoảng cách chính xác, vui lòng nhập đúng địa chỉ theo gợi ý của hệ thống.');
        }

        $result = $this->packages->add($this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {

            // if ($result->quantity == 1) {
                $this->packages->update($result->uuid, ['parent' => $result->uuid]);
            // } else {
            //     for ($i=1; $i <= $result->quantity ; $i++) {
            //         \Input::merge(array('label' => \Package::create_label()));
            //         \Input::merge(array('parent' => $result->uuid));
            //         \Input::merge(array('quantity' => 1));
            //         $temp = $this->packages->add($this->request->except(['_method', '_token', 'password_confirmation']));
            //     }
            // }

            $mess = \Lang::get('admin.global.add_success').' <b><a target="_blank" href="'.\URL::route('admin.packages.show', $result->uuid).'">'.$result->label.'</a></b>';
            \Activity::log([
                'contentId'   => $result->uuid,
                'contentType' => 'package',
                'action'      => 'add',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);
            return \Redirect::route('admin.packages.show', $result->uuid)->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.packages.index')->with('message_danger', trans('admin.global.message_danger'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->packages->firstOrFail($id);

        $result->steps = '';
        // if ($result->steps) {
        //     $steps = unserialize($result->steps);
        //     if (count($steps) > 0) {
        //         $result->steps = $steps;
        //         //dd($result->steps);
        //     }
        // }

        \Activity::log([
            'contentId'   => $id,
            'contentType' => 'package',
            'action'      => 'view',
            'description' => \Lang::get('admin.global.view').' <b><a target="_blank" href="'.\URL::route('admin.packages.show', $id).'">'.$result->label.'</a></b>',
            'userId'     => \Auth::user()->uuid,
        ]);

        return view('admin.packages.show', compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $customers = array(''=>'Chọn người nhận') + $customers->toArray();

        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $shippers = array(''=>'Chọn người đi giao hàng') + $shippers->toArray();

        $location_id = \Location::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $location_id = array(''=>'Chọn vị trí') + $location_id->toArray();

        $shipments = \Shipment::where('deleted', 0)->orderBy('id', 'DESC')->lists('key','uuid');
        $shipments = array(''=>'Chọn lô hàng') + $shipments->toArray();

        $result = $this->packages->edit($id);
        return view('admin.packages.edit', compact('result'))
            ->with('customers', $customers)
            ->with('shippers', $shippers)
            ->with('location_id', $location_id)
            ->with('shipments', $shipments);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $validator = $this->validator($this->request->all(), [
            'label'=>'required|unique:packages,label,'.$id.',uuid',
            //'customer_from' => 'required',
            'customer_id' => 'required',
            //'shipper_id' => 'required',
            //'location_id' => 'required',
            'address' => 'required',
            //'county' => 'required',
            'kgs' => 'required',
            //'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.packages.edit', $id)->withErrors($validator);
        }

        \Input::merge(array('updated_by' => \Auth::user()->id));

        $response = \Package::convert_address_to_lat_long($this->request->address);

        \Input::merge(array('latitude' => $response['latitude']));
        \Input::merge(array('longitude' => $response['longitude']));
        //\Input::merge(array('price' => $response['price']));
        \Input::merge(array('distance' => $response['distance']));
        \Input::merge(array('duration' => $response['duration']));
        //\Input::merge(array('steps' => $response['steps']));

        // Calculate price
        if ($response['distance']) {
            $price = $response['distance'] * \Input::get('kgs') * 0.5;
            \Input::merge(array('price' => $price ));
        } else {
            return \Redirect::route('admin.packages.edit', $id)->with('message_danger', 'Không thể lấy được khoảng cách chính xác, vui lòng nhập đúng địa chỉ theo gợi ý của hệ thống.');
        }

        $result = $this->packages->update($id, $this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $result = $this->packages->firstOrFail($id);
            $mess = \Lang::get('admin.global.update_success').' <b><a target="_blank" href="'.\URL::route('admin.packages.show', $id).'">'.$result->label.'</a></b>';
            \Activity::log([
                'contentId'   => $id,
                'contentType' => 'package',
                'action'      => 'update',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);
            return \Redirect::route('admin.packages.show', $id)->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.packages.index')->with('message_danger', trans('admin.global.message_danger'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id) {
            $data = $this->packages->firstOrFail($id);
            $result = $this->packages->update($id, ['deleted' => 1]);

            if ($result) {
                $mess = \Lang::get('admin.global.delete_success').' <b><a target="_blank" href="'.\URL::route('admin.packages.show', $id).'">'.$data->label.'</a></b>';
                \Activity::log([
                    'contentId'   => $id,
                    'contentType' => 'package',
                    'action'      => 'delete',
                    'description' => $mess,
                    'userId'     => \Auth::user()->uuid,
                ]);

                // Remove package parent -> remove all children
                if ($data->parent == '') {
                    if ($data->packages_parent()->where('deleted',0)->get()->count() > 0) {
                        foreach($data->packages_parent()->where('deleted',0)->get() as $item) {
                            $temp_data = $this->packages->firstOrFail($item->uuid);
                            $temp = $this->packages->update($item->uuid, ['deleted' => 1]);
                            if ($temp) {
                                $temp_mess = \Lang::get('admin.global.delete_success').' <b><a target="_blank" href="'.\URL::route('admin.packages.show', $temp_data->uuid).'">'.$temp_data->label.'</a></b>';
                                \Activity::log([
                                    'contentId'   => $item->uuid,
                                    'contentType' => 'package',
                                    'action'      => 'delete',
                                    'description' => $temp_mess,
                                    'userId'     => \Auth::user()->uuid,
                                ]);
                                $mess .= '<br/>'.$temp_mess;
                            }
                        }
                    }
                } else { // Remove children -> count == 0 => remove parent
                    if($data->package_parent->packages_parent()->where('deleted',0)->get()->count() == 0) {
                        $temp = $this->packages->update($data->package_parent->uuid, ['deleted' => 1]);
                        if ($temp) {
                            $temp_mess = \Lang::get('admin.global.delete_success').' <b><a target="_blank" href="'.\URL::route('admin.packages.show', $data->package_parent->uuid).'">'.$data->package_parent->label.'</a></b>';
                            \Activity::log([
                                'contentId'   => $data->package_parent->uuid,
                                'contentType' => 'package',
                                'action'      => 'delete',
                                'description' => $temp_mess,
                                'userId'     => \Auth::user()->uuid,
                            ]);
                            $mess .= '<br/>'.$temp_mess;
                        }
                    }
                }

                return \Redirect::route('admin.packages.index')->with('message_success', $mess);
            } else {
                return \Redirect::route('admin.packages.index')->with('message_danger', trans('admin.global.message_danger'));
            }
        }
    }

    public function search()
    {
        if (!$this->request->has('customer_id')
            && !$this->request->has('customer_phone')
            && !$this->request->has('customer_from')
            && !$this->request->has('customer_from_phone')
            && !$this->request->has('shipper_id')
            && !$this->request->has('shipper_phone')
            && !$this->request->has('status')
            && !$this->request->has('county')
            && !$this->request->has('label')
            && !$this->request->has('province_id')
            && !$this->request->has('district_id')
        ) {
            return \Redirect::route('admin.packages.index');
        }

        $result = \Package::where('deleted',0);

        if ($this->request->has('customer_id')) {
            $result = $result->where('customer_id', $this->request->customer_id);
        }

        if ($this->request->has('customer_phone')) {
            $search = $this->customers->findBy('phone', $this->request->customer_phone)->first();
            if($search) {
                $result = $result->where('customer_id', $search->uuid);
            } else {
                $result = $result->where('customer_id', 'none');
            }
        }

        if ($this->request->has('customer_from')) {
            $result = $result->where('customer_from', $this->request->customer_from);
        }

        if ($this->request->has('customer_from_phone')) {
            $search = $this->customers->findBy('phone', $this->request->customer_from_phone)->first();
            if($search) {
                $result = $result->where('customer_from', $search->uuid);
            } else {
                $result = $result->where('customer_id', 'none');
            }
        }

        if ($this->request->has('shipper_id')) {
            $result = $result->where('shipper_id', $this->request->shipper_id);
        }

        if ($this->request->has('shipper_phone')) {
            $search = $this->shippers->findBy('phone', $this->request->shipper_phone)->first();
            if($search) {
                $result = $result->where('shipper_id', $search->uuid);
            }
        }

        if ($this->request->has('status')) {
            $result = $result->where('status', $this->request->status);
        }

        if ($this->request->has('county')) {
            $result = $result->where('county', $this->request->county);
        }

        if ($this->request->has('label')) {
            $result = $result->where('label', $this->request->label);
        }

        if ($this->request->has('province_id')) {
            $result = $result->where('province_id', $this->request->province_id);
        }

        if ($this->request->has('district_id')) {
            $result = $result->where('district_id', $this->request->district_id);
        }

        $result = $result->orderBy('id', 'DESC')->get();

        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');

        return view('admin.packages.index', compact('result'))
            ->with('customer_id', $this->request->customer_id)
            ->with('customer_phone', $this->request->customer_phone)
            ->with('customer_from', $this->request->customer_from)
            ->with('customer_from_phone', $this->request->customer_from_phone)
            ->with('shipper_id', $this->request->shipper_id)
            ->with('shipper_phone', $this->request->shipper_phone)
            ->with('status', $this->request->status)
            ->with('county', $this->request->county)
            ->with('label', $this->request->label)
            ->with('province_id', $this->request->province_id)
            ->with('district_id', $this->request->district_id)
            ->with('customers', $customers)
            ->with('shippers', $shippers);
    }

    public function find()
    {
        // $abc = \Package::where('deleted',0)->get();
        // foreach($abc as $item) {
        //     $this->packages->update($item->uuid, ['county' => rand(1,19), 'status' => rand(1,6)]);
        // }
//dd(123);
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $province_id = \Request::query('province_id') ? \Request::query('province_id') : 0;
        $district_id = \Request::query('district_id') ? \Request::query('district_id') : 0;
        $shipper = \Request::query('shipper') ? \Request::query('shipper') : 0;
        $package_id = \Request::query('package_id') ? \Request::query('package_id') : 0;
        $label = \Request::query('label') ? \Request::query('label') : '';
        $mess = \Request::query('mess') ? \Request::query('mess') : '';

        $result = [];
        $random = 0;
        $item = array();

        // if ($shipper && !$this->request->session()->get('county_'.$shipper)) {
        //     for ($i=1; $i <=19 ; $i++) {
        //         $random = rand(1,19);
        //         $data= \Package::where('deleted', 0)->where('county',$random)->where('status', 2);
        //         //print $random.' - '.$data->count().'<br/>';
        //         if ($data->count() > 0) {
        //             $list = $data->get();
        //             $item = $data->orderBy(\DB::raw('RAND()'))->first();
        //             $this->request->session()->put('county_'.$shipper, $random);
        //             break;
        //         }
        //     }
        // }

        $show_label = 'Tìm kiếm';

        if (!$shipper || !$province_id || !$district_id) {
            $show_label = 'Vui lòng chọn người giao hàng và tỉnh thành phố quận huyện';
        }

        // if ($this->request->session()->has('county_'.$shipper)) {
        //     $county = $this->request->session()->get('county_'.$shipper);
        //     $show_label = 'Hệ thống chọn ngẫu nhiên '.\Package::get_county_option($county).'<br/>
        //         Nhấn tiếp tục để chọn ngẫu nhiên các kiện hàng trong '.\Package::get_county_option($county);
        // }

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
                $mess = 'Người đi giao hàng: <b>'.\Shipper::whereUuid($shipper)->first()->name.'</b>. Kiện hàng: <a target="_blank" href="'.\URL::route('admin.packages.show', $package_id).'">'.$data->label.'</a>';

                \Activity::log([
                    'contentId'   => $package_id,
                    'contentType' => 'package',
                    'action'      => 'update',
                    'description' => $mess,
                    'userId'     => \Auth::user()->uuid,
                ]);

                return \Redirect::route('admin.packages.find',['shipper' =>$shipper, 'province_id' =>$province_id, 'district_id' =>$district_id, 'mess' =>$mess]);
            }
        }

        return view('admin.packages.find', compact('result'))
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
}
