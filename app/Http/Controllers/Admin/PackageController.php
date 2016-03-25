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

        if(\Auth::user()->is_admin == false) {
            return \Redirect::route('admin.index')->with('message_danger', trans('admin.global.no_permission'));
        }

        // require_once base_path('vendor/faisalman/simple-excel-php/src/SimpleExcel/SimpleExcel.php');
        // $excel = new \SimpleExcel\SimpleExcel('CSV');
        // //$excel->parser->loadFile(base_path('CARGO MANIFEST_ 03082016.xls - Sheet1.csv'));
        // $excel->parser->loadFile(base_path('CARGO MANIFEST_ 03152016.xls - Sheet1.csv'));

        // // Save packages
        // //for ($i=9; $i <= 255 ; $i++) {
        // for ($i=9; $i <= 277 ; $i++) {

        //     $status = 5;
        //     for ($k=1; $k <= 15 ; $k++) {
        //         $string = $excel->parser->getCell($i,$k);
        //         if ($k==1) $delivery_at = $excel->parser->getCell($i,$k);
        //         if ($k==2) $invoice = $excel->parser->getCell($i,$k);
        //         if ($k==3) $service_type = $excel->parser->getCell($i,$k);
        //         if ($k==5) $weight = $excel->parser->getCell($i,$k);
        //         if ($k==6) $shipper_id = $excel->parser->getCell($i,$k);
        //         if ($k==7) $shipper_address = $excel->parser->getCell($i,$k);
        //         if ($k==8) $customer_id = $excel->parser->getCell($i,$k);
        //         if ($k==9) $customer_address = $excel->parser->getCell($i,$k);
        //         if ($k==10) $content = $excel->parser->getCell($i,$k);
        //         if ($k==15) $kgs = $excel->parser->getCell($i,$k);
        //     }

        //     $check = \Package::where('invoice',$invoice)->first();
        //     if ($check) continue;

        //     if ($shipper_id) {
        //         $shipper_data = \Shipper::where('name', $shipper_id)->first();
        //         if ($shipper_data) {
        //             $shipper_id = $shipper_data->uuid;
        //         } else {
        //             $input =  array(
        //                 'uuid' => \Uuid::generate(4)->string,
        //                 'email' => time().'@gmail.com'.$i,
        //                 'name' => $shipper_id,
        //                 'address' => $shipper_address,
        //             );
        //             \Shipper::create($input);
        //             $shipper_id = '';
        //         }
        //     }
        //     if ($customer_id) {
        //         $customer_data = \Customer::where('name', $customer_id)->first();
        //         if ($customer_data) {
        //             $customer_id = $customer_data->uuid;
        //         } else {
        //             $input =  array(
        //                 'uuid' => \Uuid::generate(4)->string,
        //                 'email' => time().'@gmail.com'.$i,
        //                 'name' => $customer_id,
        //                 'address' => $customer_address,
        //             );
        //             \Customer::create($input);
        //             $customer_id = '';
        //         }
        //     }

        //     // Convert delivery
        //     $date = '';
        //     if($delivery_at) {
        //         $date = \Carbon\Carbon::createFromFormat('m/d/y H:i', $delivery_at)
        //         ->format('Y-m-d H:i');
        //     }

        //     $uuid = \Uuid::generate(4)->string;
        //     $input =  array(
        //         'uuid' => $uuid,
        //         'parent' => $uuid,
        //         'shipper_id' => $shipper_id,
        //         'customer_id' => $customer_id,
        //         'delivery_at' => $date,
        //         'invoice' => $invoice,
        //         'service_type' => $service_type,
        //         'weight' => $weight,
        //         'content' => $content,
        //         'kgs' => $kgs,
        //         'address' => $customer_address,
        //         'label' => 'label-'.$i,
        //         'status' => $status,
        //     );
        //     //dd($input);
        //     if ($shipper_id && $customer_id) {
        //         $result = \Package::create($input);
        //         if ($result) {
        //             print 'ok '.$i.'<br/>';
        //         } else {
        //             print 'error '.$i.'<br/>';
        //         }
        //     }

        //     print '############################# <br/>';
        // }

        // Save shippers - customers
        // $shipp_name = $excel->parser->getColumn(6);
        // $shipp_address = $excel->parser->getColumn(7);
        //
        // //for ($i=8; $i <= 255 ; $i++) {
        // for ($i=8; $i <= 277 ; $i++) {
        //     if ($shipp_name[$i]) {
        //         $email = time().'@gmail.com'.$i;
        //         $name = $shipp_name[$i];
        //         $address= $shipp_address[$i];
        //         //print $name.'<br/>';continue;
        //         $input =  array(
        //             'uuid' => \Uuid::generate(4)->string,
        //             'email' => $email,
        //             'name' => $name,
        //             'address' => $address,
        //         );
        //         \Customer::create($input);
        //     }
        // }

        //dd($excel->parser->getCell(9,9));
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

        $result = $this->packages->all(10);
        return view('admin.packages.index', compact('result'))
            ->with('customer_id')
            ->with('customer_phone')
            ->with('shipper_id')
            ->with('shipper_phone')
            ->with('status')
            ->with('county')
            ->with('label')
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
        $shippers = array(''=>'Chọn người vận chuyển') + $shippers->toArray();

        $location_id = \Location::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $location_id = array(''=>'Chọn vị trí') + $location_id->toArray();

        return view('admin.packages.create')
            ->with('customers', $customers)
            ->with('shippers', $shippers)
            ->with('location_id', $location_id);
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
                'customer_id' => 'required',
                'shipper_id' => 'required',
                'location_id' => 'required',
                'address' => 'required',
                'county' => 'required',
                'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.packages.create')->withErrors($validator)->withInput();
        }

        \Input::merge(array('created_by' => \Auth::user()->id));
        \Input::merge(array('label' => \Package::create_label()));

        $response = \Package::convert_address_to_lat_long($this->request->address);

        \Input::merge(array('latitude' => $response['latitude']));
        \Input::merge(array('longitude' => $response['longitude']));
        \Input::merge(array('price' => $response['price']));
        \Input::merge(array('distance' => $response['distance']));
        \Input::merge(array('duration' => $response['duration']));
        \Input::merge(array('steps' => $response['steps']));

        $result = $this->packages->add($this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {

            if ($result->quantity == 1) {
                $this->packages->update($result->uuid, ['parent' => $result->uuid]);
            } else {
                for ($i=1; $i <= $result->quantity ; $i++) {
                    \Input::merge(array('label' => \Package::create_label()));
                    \Input::merge(array('parent' => $result->uuid));
                    \Input::merge(array('quantity' => 1));
                    $temp = $this->packages->add($this->request->except(['_method', '_token', 'password_confirmation']));
                }
            }

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
            return \Redirect::route('admin.packages.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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

        if ($result->steps) {
            $steps = unserialize($result->steps);
            if (count($steps) > 0) {
                $result->steps = $steps;
                //dd($result->steps);
            }
        }

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
        $shippers = array(''=>'Chọn người vận chuyển') + $shippers->toArray();

        $location_id = \Location::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $location_id = array(''=>'Chọn vị trí') + $location_id->toArray();

        $result = $this->packages->edit($id);
        return view('admin.packages.edit', compact('result'))
            ->with('customers', $customers)
            ->with('shippers', $shippers)
            ->with('location_id', $location_id);
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
            'customer_id' => 'required',
            'shipper_id' => 'required',
            'location_id' => 'required',
            'address' => 'required',
            'county' => 'required',
            //'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.packages.edit', $id)->withErrors($validator);
        }

        \Input::merge(array('updated_by' => \Auth::user()->id));

        $response = \Package::convert_address_to_lat_long($this->request->address);

        \Input::merge(array('latitude' => $response['latitude']));
        \Input::merge(array('longitude' => $response['longitude']));
        \Input::merge(array('price' => $response['price']));
        \Input::merge(array('distance' => $response['distance']));
        \Input::merge(array('duration' => $response['duration']));
        \Input::merge(array('steps' => $response['steps']));

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
            return \Redirect::route('admin.packages.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
                return \Redirect::route('admin.packages.change_pass')->with('message_danger', trans('admin.global.message_danger'));
            }
        }
    }

    public function search()
    {
        if (!$this->request->has('customer_id')
            && !$this->request->has('customer_phone')
            && !$this->request->has('shipper_id')
            && !$this->request->has('shipper_phone')
            && !$this->request->has('status')
            && !$this->request->has('county')
            && !$this->request->has('label')
        ) {
            return \Redirect::route('admin.packages.index');
        }

        $result = \Package::where('deleted',0);

        if ($this->request->has('customer_id')) {
            $result = $result->where('customer_id', $this->request->customer_id);
        }

        if ($this->request->has('customer_phone')) {
            $search = $this->customers->findBy('phone', $this->request->customer_phone)->first();
            if($search->uuid) {
                $result = $result->where('customer_id', $search->uuid);
            }
        }

        if ($this->request->has('shipper_id')) {
            $result = $result->where('shipper_id', $this->request->shipper_id);
        }

        if ($this->request->has('shipper_phone')) {
            $search = $this->shippers->findBy('phone', $this->request->shipper_phone)->first();
            if($search->uuid) {
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

        $result = $result->orderBy('id', 'DESC')->get();

        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');

        return view('admin.packages.index', compact('result'))
            ->with('customer_id', $this->request->customer_id)
            ->with('customer_phone', $this->request->customer_phone)
            ->with('shipper_id', $this->request->shipper_id)
            ->with('shipper_phone', $this->request->shipper_phone)
            ->with('status', $this->request->status)
            ->with('county', $this->request->county)
            ->with('label', $this->request->label)
            ->with('customers', $customers)
            ->with('shippers', $shippers);
    }

    public function find()
    {
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $county = \Request::query('county') ? \Request::query('county') : 0;
        $shipper = \Request::query('shipper') ? \Request::query('shipper') : 0;

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

        if (!$shipper || !$county) {
            $show_label = 'Vui lòng chọn shipper và quận';
        }

        // if ($this->request->session()->has('county_'.$shipper)) {
        //     $county = $this->request->session()->get('county_'.$shipper);
        //     $show_label = 'Hệ thống chọn ngẫu nhiên '.\Package::get_county_option($county).'<br/>
        //         Nhấn tiếp tục để chọn ngẫu nhiên các kiện hàng trong '.\Package::get_county_option($county);
        // }

        if ($shipper && $county) {
            $result = \Package::where('deleted', 0)->where('county',$county)->where('status', 2)->orderBy('distance')->get();
        }

        return view('admin.packages.find', compact('result'))
            ->with('item', $item)
            ->with('show_label', $show_label)
            ->with('shippers', $shippers)
            ->with('county', $county)
            ->with('shipper', $shipper)
            ;
    }
}
