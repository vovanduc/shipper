<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        if(\Auth::user()->is_admin == false) {
            return \Redirect::route('admin.index')->with('message_danger', trans('admin.global.no_permission'));
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = \Activity::orderBy('id', 'DESC')->paginate(10);
        return view('admin.logs.index', compact('result'));
    }
}
