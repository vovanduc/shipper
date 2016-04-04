<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Location\ILocationRepository;
use App\Http\Models\Admin\Location as Location;

class LocationsController extends Controller
{
    protected $locations;

    public function __construct(ILocationRepository $locations, Request $request)
    {
        $this->locations = $locations;
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
        $result = $this->locations->all(10);
        return view('admin.locations.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.locations.create');
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
                'name' => 'required|max:255|unique:locations',
                'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.locations.create')->withErrors($validator)->withInput();
        }

        \Input::merge(array('created_by' => \Auth::user()->id));

        $result = $this->locations->add($this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $mess = \Lang::get('admin.global.add_success').' <b><a target="_blank" href="'.\URL::route('admin.locations.show', $result->uuid).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $result->uuid,
                'contentType' => 'location',
                'action'      => 'add',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);
            return \Redirect::route('admin.locations.index')->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.locations.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
        $result = $this->locations->firstOrFail($id);
        \Activity::log([
            'contentId'   => $id,
            'contentType' => 'location',
            'action'      => 'view',
            'description' => \Lang::get('admin.global.view').' <b><a target="_blank" href="'.\URL::route('admin.locations.show', $id).'">'.$result->name.'</a></b>',
            'userId'     => \Auth::user()->uuid,
        ]);
        return view('admin.locations.show', compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = $this->locations->firstOrFail($id);
        return view('admin.locations.edit', compact('result'));
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
            'name' => 'required|max:255|unique:locations,name,'.$id.',uuid',
            'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.locations.edit', $id)->withErrors($validator);
        }

        \Input::merge(array('updated_by' => \Auth::user()->id));

        $result = $this->locations->update($id, $this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $result = $this->locations->firstOrFail($id);
            $mess = \Lang::get('admin.global.update_success').' <b><a target="_blank" href="'.\URL::route('admin.locations.show', $id).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $id,
                'contentType' => 'location',
                'action'      => 'update',
                'description' => $mess,
                'userId'     => \Auth::user()->uuid,
            ]);
            return \Redirect::route('admin.locations.index')->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.locations.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
            $data = $this->locations->firstOrFail($id);
            $result = $this->locations->update($id, ['deleted' => 1]);
            if ($result) {
                $mess = \Lang::get('admin.global.delete_success').' <b><a target="_blank" href="'.\URL::route('admin.locations.show', $id).'">'.$data->name.'</a></b>';
                \Activity::log([
                    'contentId'   => $id,
                    'contentType' => 'location',
                    'action'      => 'delete',
                    'description' => $mess,
                    'userId'     => \Auth::user()->uuid,
                ]);
                return \Redirect::route('admin.locations.index')->with('message_success', $mess);
            } else {
                return \Redirect::route('admin.locations.change_pass')->with('message_danger', trans('admin.global.message_danger'));
            }
        }
    }

    public function search()
    {
        if (!$this->request->has('name') && !$this->request->has('email')
        && !$this->request->has('phone') && !$this->request->has('address')) {
            return \Redirect::route('admin.locations.index');
        }

        $result = Location::where('deleted',0);

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

        return view('admin.locations.index', compact('result'))
            ->with('name', $this->request->name)
            ->with('email', $this->request->email)
            ->with('phone', $this->request->phone)
            ->with('address', $this->request->address);
    }
}
