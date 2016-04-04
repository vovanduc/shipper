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

    public function search()
    {
        if (!$this->request->has('user_id')
            && !$this->request->has('action_id')
            && !$this->request->has('module_id')
        ) {
            return \Redirect::route('admin.logs.index');
        }

        $result = \Activity::where('user_id','!=','');

        if ($this->request->has('user_id')) {
            $result = $result->where('user_id', $this->request->user_id);
        }

        if ($this->request->has('action_id')) {
            $result = $result->where('action', $this->request->action_id);
        }

        if ($this->request->has('module_id')) {
            $result = $result->where('content_type', $this->request->module_id);
        }

        $result = $result->orderBy('id', 'DESC')->get();

        $users = \User::orderBy('id', 'DESC')->lists('name','uuid');
        $actions = \Config::get('lib.ACTION');
        $modules = \Config::get('lib.MODULE');

        return view('admin.logs.index', compact('result'))
            ->with('users', $users)
            ->with('actions', $actions)
            ->with('modules', $modules)
            ->with('user_id', $this->request->user_id)
            ->with('action_id', $this->request->action_id)
            ->with('module_id', $this->request->module_id)
            ;
    }
}
