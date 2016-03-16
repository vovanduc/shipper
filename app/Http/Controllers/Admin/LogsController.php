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
        $result = \Activity::orderBy('id', 'DESC')->paginate(20);
        $users = \User::orderBy('id', 'DESC')->lists('name','uuid');

        $actions = \Config::get('lib.ACTION');
        $modules = \Config::get('lib.MODULE');

        return view('admin.logs.index', compact('result'))
        ->with('users', $users)
        ->with('actions', $actions)
        ->with('modules', $modules)
        ->with('user_id')
        ->with('action_id')
        ->with('module_id')
        ;
    }
}
