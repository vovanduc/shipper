<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Customer\ICustomerRepository;
use App\Http\Models\Admin\Customer as Customer;

class CustomersController extends Controller
{
    protected $customers;

    public function __construct(ICustomerRepository $customers, Request $request)
    {
        $this->customers = $customers;
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
        $result = $this->customers->all(10);
        return view('admin.customers.index', compact('result'))
            ->with('name')
            ->with('email')
            ->with('phone')
            ->with('address');
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
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:customers',
                'phone' => 'required',
                'address' => 'required',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.customers.create')->withErrors($validator)->withInput();
        }

        \Input::merge(array('created_by' => \Auth::user()->id));

        $result = $this->customers->add($this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $mess = \Lang::get('admin.global.add_success').' <b><a target="_blank" href="'.\URL::route('admin.customers.show', $result->uuid).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $result->uuid,
                'contentType' => 'customer',
                'action'      => 'add',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);
            return \Redirect::route('admin.customers.index')->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.customers.index')->with('message_danger', trans('admin.global.message_danger'));
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
        \Activity::log([
            'contentId'   => $id,
            'contentType' => 'customer',
            'action'      => 'view',
            'description' => \Lang::get('admin.global.view').' <b><a target="_blank" href="'.\URL::route('admin.customers.show', $id).'">'.$result->name.'</a></b>',
            'userId'     => \Auth::user()->uuid,
        ]);
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
              'name' => 'required|max:255',
              'email'=>'required|email|unique:customers,email,'.$id.',uuid',
              'phone' => 'required',
              'address' => 'required',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.customers.edit', $id)->withErrors($validator);
        }

        \Input::merge(array('updated_by' => \Auth::user()->id));

        $result = $this->customers->update($id, $this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $result = $this->customers->firstOrFail($id);
            $mess = \Lang::get('admin.global.update_success').' <b><a target="_blank" href="'.\URL::route('admin.customers.show', $id).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $id,
                'contentType' => 'customer',
                'action'      => 'update',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);
            return \Redirect::route('admin.customers.index')->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.customers.index')->with('message_danger', trans('admin.global.message_danger'));
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
            $data = $this->customers->firstOrFail($id);
            $result = $this->customers->update($id, ['deleted' => 1]);
            if ($result) {
                $mess = \Lang::get('admin.global.delete_success').' <b><a target="_blank" href="'.\URL::route('admin.customers.show', $id).'">'.$data->name.'</a></b>';
                \Activity::log([
                    'contentId'   => $id,
                    'contentType' => 'customer',
                    'action'      => 'delete',
                    'description' => $mess,
                    'userId'     => \Auth::user()->uuid,
                ]);
                return \Redirect::route('admin.customers.index')->with('message_success', $mess);
            } else {
                return \Redirect::route('admin.customers.index')->with('message_danger', trans('admin.global.message_danger'));
            }
        }
    }

    public function search()
    {
        if (!$this->request->has('name') && !$this->request->has('email')
        && !$this->request->has('phone') && !$this->request->has('address')) {
            return \Redirect::route('admin.customers.index');
        }

        $result = Customer::where('deleted',0);

        if ($this->request->has('name')) {
            $result = $result->where('name', 'LIKE', '%'.$this->request->name.'%');
        }

        if ($this->request->has('email')) {
            $result = $result->where('email', 'LIKE', '%'.$this->request->email.'%');
        }

        if ($this->request->has('phone')) {
            $result = $result->where('phone', 'LIKE', '%'.$this->request->phone.'%');
        }

        if ($this->request->has('address')) {
            $result = $result->where('address', 'LIKE', '%'.$this->request->address.'%');
        }

        $result = $result->orderBy('id', 'DESC')->get();

        return view('admin.customers.index', compact('result'))
            ->with('name', $this->request->name)
            ->with('email', $this->request->email)
            ->with('phone', $this->request->phone)
            ->with('address', $this->request->address);
    }
}
