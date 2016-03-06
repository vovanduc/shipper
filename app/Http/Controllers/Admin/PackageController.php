<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Package\IPackageRepository;

class PackagesController extends Controller
{
    protected $packages;

    public function __construct(IPackageRepository $packages, Request $request)
    {
        $this->packages = $packages;
        $this->request = $request;

        if(\Auth::user()->is_admin == false) {
            return \Redirect::route('admin.index')->with('message_danger', trans('admin.global.no_permission'));
        }
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
        $response = \GoogleMaps::load('geocoding')
        ->setParam (['address' =>'santa cruz'])
        ->get();

        print_r($response);exit;


        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $result = $this->packages->all(10);
        return view('admin.packages.index', compact('result'))
            ->with('customer_id')
            ->with('shipper_id')
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
        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        return view('admin.packages.create')
            ->with('customers', $customers)
            ->with('shippers', $shippers);
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
                'address' => 'required',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.packages.create')->withErrors($validator)->withInput();
        }

        \Input::merge(array('created_by' => \Auth::user()->id));
        \Input::merge(array('label' => \Package::create_label()));

        $result = $this->packages->add($this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            return \Redirect::route('admin.packages.index')->with('message_success', trans('admin.global.add_success'));
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
        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $result = $this->packages->edit($id);
        return view('admin.packages.edit', compact('result'))
            ->with('customers', $customers)
            ->with('shippers', $shippers);
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
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.packages.edit', $id)->withErrors($validator);
        }

        \Input::merge(array('updated_by' => \Auth::user()->id));

        $result = $this->packages->update($id, $this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            return \Redirect::route('admin.packages.index')->with('message_success', trans('admin.global.update_success'));
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
            $result = $this->packages->update($id, ['deleted' => 1]);

            if ($result) {
                return \Redirect::route('admin.packages.index')->with('message_success', trans('admin.global.delete_success'));
            } else {
                return \Redirect::route('admin.packages.change_pass')->with('message_danger', trans('admin.global.message_danger'));
            }
        }
    }

    public function search()
    {
        if (!$this->request->has('customer_id')
            && !$this->request->has('shipper_id')
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

        if ($this->request->has('shipper_id')) {
            $result = $result->where('shipper_id', $this->request->shipper_id);
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
        $shippers = \Shipper::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');

        return view('admin.packages.index', compact('result'))
            ->with('customer_id', $this->request->customer_id)
            ->with('shipper_id', $this->request->shipper_id)
            ->with('status', $this->request->status)
            ->with('county', $this->request->county)
            ->with('label', $this->request->label)
            ->with('customers', $customers)
            ->with('shippers', $shippers);
    }
}
