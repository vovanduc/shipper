<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Customer\ICustomerRepository;

class CustomersController extends Controller
{
    protected $customers;

    public function __construct(ICustomerRepository $customers, Request $request)
    {
        $this->customers = $customers;
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
        $result = $this->customers->all(10);
        return view('admin.customers.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.create');
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
                'username' => 'required|max:255|unique:customers',
                'email' => 'required|email|max:255|unique:customers',
                'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.customers.create')->withErrors($validator)->withInput();
        }

        $password = $this->request->input('password');

        \Input::merge(array('password' => bcrypt($password)));

        $result = $this->customers->add($this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            return \Redirect::route('admin.customers.index')->with('message_success', trans('admin.global.add_success'));
        } else {
            return \Redirect::route('admin.customers.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
        $result = $this->customers->firstOrFail($id);
        return view('admin.customers.show', compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = $this->customers->firstOrFail($id);
        return view('admin.customers.edit', compact('result'));
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
                'email' => 'required|email|max:255',
                'password' => 'confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.customers.edit', $id)->withErrors($validator);
        }

        $data = $this->customers->firstOrFail($id);

        if ($this->request->has('password')) {
            \Input::merge(array('password' => bcrypt($this->request->input('password'))));
        } else {
            \Input::merge(array('password' => $data->password));
        }

        $result = $this->customers->update($id, $this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            return \Redirect::route('admin.customers.index')->with('message_success', trans('admin.global.update_success'));
        } else {
            return \Redirect::route('admin.customers.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
            $result = $this->customers->delete($id);

            if ($result) {
                return \Redirect::route('admin.customers.index')->with('message_success', trans('admin.global.delete_success'));
            } else {
                return \Redirect::route('admin.customers.change_pass')->with('message_danger', trans('admin.global.message_danger'));
            }
        }
    }
}
