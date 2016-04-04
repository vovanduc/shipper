<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Shipper\IShipperRepository;
use App\Http\Models\Admin\Shipper as Shipper;

class ShippersController extends Controller
{
    protected $shippers;

    public function __construct(IShipperRepository $shippers, Request $request)
    {
        $this->shippers = $shippers;
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
        $result = $this->shippers->all(10);
        return view('admin.shippers.index', compact('result'))
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
        return view('admin.shippers.create');
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
                'email' => 'required|email|max:255|unique:shippers',
                'username' => 'unique:shippers',
                'phone' => 'required',
                'address' => 'required',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.shippers.create')->withErrors($validator)->withInput();
        }

        $password = $this->request->input('password');

        \Input::merge(array('password' => bcrypt($password)));

        \Input::merge(array('created_by' => \Auth::user()->id));

        $result = $this->shippers->add($this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $mess = \Lang::get('admin.global.add_success').' <b><a target="_blank" href="'.\URL::route('admin.shippers.show', $result->uuid).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $result->uuid,
                'contentType' => 'shipper',
                'action'      => 'add',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);
            return \Redirect::route('admin.shippers.index')->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.shippers.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
        $result = $this->shippers->firstOrFail($id);

        $money = array();
        $money['day'] = \Shipper::money($id, 1);
        $money['week'] = \Shipper::money($id, 2);
        $money['month'] = \Shipper::money($id, 3);
        $money['year'] = \Shipper::money($id, 4);

        \Activity::log([
            'contentId'   => $id,
            'contentType' => 'shipper',
            'action'      => 'view',
            'description' => \Lang::get('admin.global.view').' <b><a target="_blank" href="'.\URL::route('admin.shippers.show', $id).'">'.$result->name.'</a></b>',
            'userId'     => \Auth::user()->uuid,
        ]);
        return view('admin.shippers.show', compact('result'))->with('money', $money);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = $this->shippers->firstOrFail($id);
        return view('admin.shippers.edit', compact('result'));
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
              'email'=>'required|email|unique:shippers,email,'.$id.',uuid',
              'username'=>'unique:shippers,username,'.$id.',uuid',
              'phone' => 'required',
              'address' => 'required',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.shippers.edit', $id)->withErrors($validator);
        }

        $data = $this->shippers->firstOrFail($id);

        if ($this->request->has('password')) {
            \Input::merge(array('password' => bcrypt($this->request->input('password'))));
        } else {
            \Input::merge(array('password' => $data->password));
        }

        \Input::merge(array('updated_by' => \Auth::user()->id));

        $result = $this->shippers->update($id, $this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $result = $this->shippers->firstOrFail($id);
            $mess = \Lang::get('admin.global.update_success').' <b><a target="_blank" href="'.\URL::route('admin.shippers.show', $id).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $id,
                'contentType' => 'shipper',
                'action'      => 'update',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);
            return \Redirect::route('admin.shippers.index')->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.shippers.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
            $data = $this->shippers->firstOrFail($id);
            $result = $this->shippers->update($id, ['deleted' => 1]);
            if ($result) {
                $mess = \Lang::get('admin.global.delete_success').' <b><a target="_blank" href="'.\URL::route('admin.shippers.show', $id).'">'.$data->name.'</a></b>';
                \Activity::log([
                    'contentId'   => $id,
                    'contentType' => 'shipper',
                    'action'      => 'delete',
                    'description' => $mess,
                    'userId'     => \Auth::user()->uuid,
                ]);
                return \Redirect::route('admin.shippers.index')->with('message_success', $mess);
            } else {
                return \Redirect::route('admin.shippers.change_pass')->with('message_danger', trans('admin.global.message_danger'));
            }
        }
    }

    public function search()
    {
        if (!$this->request->has('name') && !$this->request->has('email')
        && !$this->request->has('phone') && !$this->request->has('address')) {
            return \Redirect::route('admin.customers.index');
        }

        $result = Shipper::where('deleted',0);

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

        return view('admin.shippers.index', compact('result'))
            ->with('name', $this->request->name)
            ->with('email', $this->request->email)
            ->with('phone', $this->request->phone)
            ->with('address', $this->request->address);
    }
}
