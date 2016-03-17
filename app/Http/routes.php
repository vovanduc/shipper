<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {

	Route::get('/', function () {
	    return view('welcome');
	});

    Route::auth();

    Route::group(array('prefix' => 'admin', 'middleware' => 'auth'), function()
	{
		Route::get('/', array("as"=>"admin.index","uses"=>"Admin\HomeController@index"));

		// Users
		Route::resource('users', 'Admin\UsersController');
		Route::any('change_pass', array("as"=>"admin.users.change_pass","uses"=>"Admin\UsersController@change_pass"));

		// Customers
		Route::resource('customers', 'Admin\CustomersController');
		Route::any('customers/search', array("as"=>"admin.customers.search","uses"=>"Admin\CustomersController@search"));

		// Shippers
		Route::resource('shippers', 'Admin\ShippersController');
		Route::any('shippers/search', array("as"=>"admin.shippers.search","uses"=>"Admin\ShippersController@search"));

		// Packages
		Route::any('packages/search', array("as"=>"admin.packages.search","uses"=>"Admin\PackagesController@search"));
		Route::get('packages/find', array("as"=>"admin.packages.find","uses"=>"Admin\PackagesController@find"));
		Route::get('packages/barcode', array("as"=>"admin.packages.barcode","uses"=>"Admin\PackagesBarcodeController@index"));
		Route::resource('packages', 'Admin\PackagesController');

		// Logs
		Route::resource('logs', 'Admin\LogsController');
		Route::any('logs/search', array("as"=>"admin.logs.search","uses"=>"Admin\LogsController@search"));
	});
});
