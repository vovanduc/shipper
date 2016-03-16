<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repositories\User\IUserRepository;

class UsersController extends Controller
{
    protected $users;

    public function __construct(IUserRepository $users, Request $request)
    {
        $this->users = $users;
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

        // $faker = \Faker\Factory::create();
        // echo $faker->uuid;exit;
        // echo $faker->username;exit;


        $result = $this->users->all(10);
        //\Event::fire(new \App\Events\SomeEvent($result) );
        return view('admin.users.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
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
                'username' => 'required|max:255|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.users.create')->withErrors($validator)->withInput();
        }

        $password = $this->request->input('password');

        \Input::merge(array('password' => bcrypt($password)));

        $result = $this->users->add($this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $mess = \Lang::get('admin.global.add_success').' <b><a target="_blank" href="'.\URL::route('admin.users.show', $result->uuid).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $result->uuid,
                'contentType' => 'user',
                'action'      => 'add',
                'description' => $mess,
                'updated'     => (bool) \Auth::user()->id,
            ]);
            return \Redirect::route('admin.users.index')->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.users.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
        $result = $this->users->firstOrFail($id);
        \Activity::log([
            'contentId'   => $id,
            'contentType' => 'user',
            'action'      => 'view',
            'description' => \Lang::get('admin.global.view').' <b><a target="_blank" href="'.\URL::route('admin.users.show', $id).'">'.$result->name.'</a></b>',
            'updated'     => (bool) \Auth::user()->id,
        ]);
        return view('admin.users.show', compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = $this->users->firstOrFail($id);
        return view('admin.users.edit', compact('result'));
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
                'email' => 'required|email|max:255',
                'password' => 'confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('admin.users.edit', $id)->withErrors($validator);
        }

        $data = $this->users->firstOrFail($id);

        if ($this->request->has('password')) {
            \Input::merge(array('password' => bcrypt($this->request->input('password'))));
        } else {
            \Input::merge(array('password' => $data->password));
        }

        $result = $this->users->update($id, $this->request->except(['_method', '_token', 'password_confirmation']));

        if ($result) {
            $result = $this->users->firstOrFail($id);
            $mess = \Lang::get('admin.global.update_success').' <b><a target="_blank" href="'.\URL::route('admin.users.show', $id).'">'.$result->name.'</a></b>';
            \Activity::log([
                'contentId'   => $id,
                'contentType' => 'user',
                'action'      => 'update',
                'description' => $mess,
                'updated'     => (bool) \Auth::user()->id,
            ]);
            return \Redirect::route('admin.users.index')->with('message_success', $mess);
        } else {
            return \Redirect::route('admin.users.change_pass')->with('message_danger', trans('admin.global.message_danger'));
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
            $result = $this->users->delete($id);

            if ($result) {
                return \Redirect::route('admin.users.index')->with('message_success', trans('admin.global.delete_success'));
            } else {
                return \Redirect::route('admin.users.change_pass')->with('message_danger', trans('admin.global.message_danger'));
            }
        }
    }

    public function change_pass()
    {
        if ($this->request->input('password')) {

            $validator = $this->validator($this->request->all(), [
                'password' => 'confirmed|min:6',
            ]);

            if ($validator->fails()) {
                return \Redirect::route('admin.users.change_pass')->withErrors($validator);
            }

            $password = $this->request->input('password');

            $result = $this->users->update(\Auth::user()->uuid, ['password' => bcrypt($password)]);

            if ($result) {
                \Activity::log([
                    'contentId'   => \Auth::user()->uuid,
                    'contentType' => 'user',
                    'action'      => 'update',
                    'description' => 'Thay đổi mật khẩu',
                    'updated'     => (bool) \Auth::user()->id,
                ]);
                return \Redirect::route('admin.users.index')->with('message_success', trans('admin.global.message_success'));
            } else {
                return \Redirect::route('admin.users.change_pass')->with('message_danger', trans('admin.global.message_danger'));
            }
        }

        return view('admin.users.change_pass');
    }
}
