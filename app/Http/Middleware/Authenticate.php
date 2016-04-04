<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        //dd(\Route::getFacadeRoot()->current());
        //dd($request->route());

        $request_permissions = explode('.',\Request::route()->getName());

        $permissions = \Auth::user()->permissions;
        $permissions = unserialize($permissions);

        if ($request_permissions[2] == 'edit') {
            $request_permissions[2] = 'update';
        }

        if ($request_permissions[2] == 'create' || $request_permissions[2] == 'store') {
            $request_permissions[2] = 'add';
        }

        if ($request_permissions[2] == 'destroy') {
            $request_permissions[2] = 'delete';
        }

        view()->share('permission_accept_'.$request_permissions[2], true);

        foreach(\Config::get('lib.PERMISSIONS') as $key => $value) {
            foreach($value as $key_temp => $value_temp) {
                if($request_permissions[1] == $key) {

                        view()->share('permission_accept_'.$key_temp, $permissions[$key][$key_temp]);

                        // Module users
                        if ($key == 'users') {
                            if (Auth::user()->is_root) {
                                view()->share('permission_accept_'.$key_temp, true);
                            }

                            if ($key_temp == 'show' || $key_temp == 'update') {
                                if($request->route('users') == \Auth::user()->uuid) {
                                    view()->share('permission_accept_'.$key_temp, true);
                                }
                            }
                        }

                }
            }
        }

        return $next($request);
    }
}
