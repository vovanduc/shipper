<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Package\IPackageRepository;

class PackagesBarcodeController extends Controller
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
        // $response = \GoogleMaps::load('distancematrix')
        //         ->setParamByKey('origins', '262 Bùi Viện, Hồ Chí Minh, Việt Nam')
        //         ->setParamByKey('destinations', '403 Nguyễn Trãi, Hồ Chí Minh, Việt Nam|90/18 Dương Bá Trạc, Hồ Chí Minh, Việt Nam|101 Nguyễn Thị Minh Khai, Hồ Chí Minh, Việt Nam')
        //         ->get();
        // print_r($response);exit;

        $customers = \Customer::where('deleted', 0)->orderBy('id', 'DESC')->lists('name','id');
        $result = $this->packages->all(10);
        return view('admin.packages.index', compact('result'))
            ->with('customer_id_from')
            ->with('customer_id_to')
            ->with('status')
            ->with('county')
            ->with('label')
            ->with('customers', $customers);
    }


}
