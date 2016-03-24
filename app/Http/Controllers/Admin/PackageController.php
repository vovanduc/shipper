<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Package\IPackageRepository;
use App\Http\Repositories\Customer\ICustomerRepository;

class PackagesController extends Controller
{
    protected $packages;

    public function __construct(IPackageRepository $packages, Request $request, ICustomerRepository $customers)
    {
        $this->packages = $packages;
        $this->customers = $customers;
        $this->request = $request;

        if(\Auth::user()->is_admin == false) {
            return \Redirect::route('admin.index')->with('message_danger', trans('admin.global.no_permission'));
        }

        require_once base_path('vendor/faisalman/simple-excel-php/src/SimpleExcel/SimpleExcel.php');
        $excel = new \SimpleExcel\SimpleExcel('CSV');
        $excel->parser->loadFile(base_path('CARGO MANIFEST_ 03082016.xls - Sheet1.csv'));
        //$excel->parser->loadFile(base_path('CARGO MANIFEST_ 03152016.xls - Sheet1.csv'));

        // Save shippers - customers
        // $shipp_name = $excel->parser->getColumn(6);
        // $shipp_address = $excel->parser->getColumn(7);
        //
        // for ($i=8; $i <= 248 ; $i++) {
        //     if ($shipp_name[$i]) {
        //         $email = time().'@gmail.com'.$i;
        //         $name = $shipp_name[$i];
        //         $address= $shipp_address[$i];
        //         $input =  array(
        //             'uuid' => \Uuid::generate(4)->string,
        //             'email' => $email,
        //             'name' => $name,
        //             'address' => $address,
        //         );
        //         \Shipper::create($input);
        //     }
        // }




        exit;
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

        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $result = $this->packages->all(10);
        return view('admin.packages.index', compact('result'))
            ->with('customer_id_from')
            ->with('customer_phone_from')
            ->with('customer_id_to')
            ->with('customer_phone_to')
            ->with('status')
            ->with('county')
            ->with('label')
            ->with('customers', $customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer_id_from = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $customer_id_from = array(''=>'Chọn người gửi') + $customer_id_from->toArray();

        $customer_id_to = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $customer_id_to = array(''=>'Chọn người nhận') + $customer_id_to->toArray();

        $location_id = \Location::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $location_id = array(''=>'Chọn vị trí') + $location_id->toArray();

        return view('admin.packages.create')
            ->with('customer_id_from', $customer_id_from)
            ->with('customer_id_to', $customer_id_to)
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
                'customer_id_from' => 'required',
                'customer_id_to' => 'required',
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
        $customer_id_from = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $customer_id_from = array(''=>'Chọn người gửi') + $customer_id_from->toArray();

        $customer_id_to = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $customer_id_to = array(''=>'Chọn người nhận') + $customer_id_to->toArray();

        $location_id = \Location::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','uuid');
        $location_id = array(''=>'Chọn vị trí') + $location_id->toArray();

        $result = $this->packages->edit($id);
        return view('admin.packages.edit', compact('result'))
            ->with('customer_id_from', $customer_id_from)
            ->with('customer_id_to', $customer_id_to)
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
            'customer_id_from' => 'required',
            'customer_id_to' => 'required',
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
        if (!$this->request->has('customer_id_from')
            && !$this->request->has('customer_phone_from')
            && !$this->request->has('customer_id_to')
            && !$this->request->has('customer_phone_to')
            && !$this->request->has('status')
            && !$this->request->has('county')
            && !$this->request->has('label')
        ) {
            return \Redirect::route('admin.packages.index');
        }

        $result = \Package::where('deleted',0);

        if ($this->request->has('customer_id_from')) {
            $result = $result->where('customer_id_from', $this->request->customer_id_from);
        }

        if ($this->request->has('customer_phone_from')) {
            $search = $this->customers->findBy('phone', $this->request->customer_phone_from)->first();
            if($search->id) {
                $result = $result->where('customer_id_from', $search->id);
            }
        }

        if ($this->request->has('customer_id_to')) {
            $result = $result->where('customer_id_to', $this->request->customer_id_to);
        }

        if ($this->request->has('customer_phone_to')) {
            $search = $this->customers->findBy('phone', $this->request->customer_phone_to)->first();
            if($search->id) {
                $result = $result->where('customer_id_to', $search->id);
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

        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');

        return view('admin.packages.index', compact('result'))
            ->with('customer_id_from', $this->request->customer_id_from)
            ->with('customer_phone_from', $this->request->customer_phone_from)
            ->with('customer_id_to', $this->request->customer_id_to)
            ->with('customer_phone_to', $this->request->customer_phone_to)
            ->with('status', $this->request->status)
            ->with('county', $this->request->county)
            ->with('label', $this->request->label)
            ->with('customers', $customers);
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
