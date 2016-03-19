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
                    .'</strong> đến <strong>'.\Package::get_status_option($status_to).'</strong>';

                    \Activity::log([
                        'contentId'   => $result->uuid,
                        'contentType' => 'package',
                        'action'      => 'update',
                        'description' => $message,
                        'userId'     => \Auth::user()->uuid,
                    ]);

                } else {
                    print 'Lỗi hệ thống vui lòng liên hệ admin';
                }
            }
        }

        return view('admin.packages.barcode', compact('result'))
            ->with('status_from', $status_from)
            ->with('status_to', $status_to)
            ->with('message', $message);
    }


}
