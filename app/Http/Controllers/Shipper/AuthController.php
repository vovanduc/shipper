<?php

namespace App\Http\Controllers\Shipper;

use Illuminate\Http\Request as Request;
use Validator;
use App\Http\Controllers\Controller;
use Auth;
class AuthController extends Controller
{
    protected $username = 'username';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function login() {
        if (Auth::guard('shippers')->check()) {
            return \Redirect::route('shipper.home.index');
        }
        return view('shipper.auth.login');
    }

    public function postLogin(Request $request) {
        //dd(\Hash::make('demo'));
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return \Redirect::route('shipper.auth.login')->withErrors($validator)->withInput();
        }

        $data = ['username' => \Input::get('username'), 'password' => \Input::get('password')];

        if (auth()->guard('shippers')->attempt($data)) {
           return \Redirect::route('shipper.home.index');
        } else {
           return \Redirect::route('shipper.auth.login')->withErrors(['username' => 'Thông tin đăng nhập không tồn tại'])->withInput();
        }
    }

    public function logout() {
        if (!Auth::guard('shippers')->check()) {
            return \Redirect::route('shipper.auth.login');
        }
        auth()->guard('shippers')->logout();
        return \Redirect::to('/');
    }
}
