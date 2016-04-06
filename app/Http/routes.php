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
		Route::get('/', array("as"=>"admin.home.index","uses"=>"Admin\HomeController@index"));

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

		// Locations
		Route::resource('locations', 'Admin\LocationsController');
		Route::any('locations/search', array("as"=>"admin.locations.search","uses"=>"Admin\LocationsController@search"));

		// Statistics
        Route::get('/statistics/shippers', array("as"=>"admin.statistics.shippers","uses"=>"Admin\StatisticsController@statistics"));
		Route::get('/statistics/customers', array("as"=>"admin.statistics.customers","uses"=>"Admin\StatisticsController@customers"));
		Route::get('/statistics/chart', array("as"=>"admin.statistics.chart","uses"=>"Admin\StatisticsController@chart"));

		// Report
		Route::get('/reports/shippers', array("as"=>"admin.reports.shippers","uses"=>"Admin\ReportsController@shippers"));

		// Logs
		Route::resource('logs', 'Admin\LogsController');
		Route::any('logs/search', array("as"=>"admin.logs.search","uses"=>"Admin\LogsController@search"));

		// Import excel
		Route::get('import/excel', array("as"=>"admin.import.excel","uses"=>"Admin\ImportsController@excel"));

		Route::get('errors/permission', array("as"=>"admin.errors.permission","uses"=>"Admin\ErrorsController@permission"));

		Route::get('get_backup', array("as"=>"admin.system.get_backup","uses"=>"Admin\SystemController@get_backup"));
		Route::get('get_district', array("as"=>"admin.system.get_district","uses"=>"Admin\SystemController@get_district"));
		// Download Route
		Route::get('download_backup/{file_name}', array("as"=>"admin.system.download_backup","uses"=>"Admin\SystemController@download_backup"));
	});
});

Route::group(array('prefix' => 'shipper', 'middleware' => 'shippers'), function () {

	Route::group(['middleware' => 'auth.shippers'], function () {
		Route::get('/', array("as"=>"shipper.home.index","uses"=>"Shipper\HomeController@index"));
		Route::get('/packages', array("as"=>"shipper.packages.index","uses"=>"Shipper\PackagesController@index"));
		Route::get('/packages/search', array("as"=>"shipper.packages.search","uses"=>"Shipper\PackagesController@search"));
		Route::get('/packages/{uuid}/show', array("as"=>"shipper.packages.show","uses"=>"Shipper\PackagesController@show"));
		Route::get('/packages/find', array("as"=>"shipper.packages.find","uses"=>"Shipper\PackagesController@find"));
		Route::get('/packages/barcode', array("as"=>"shipper.packages.barcode","uses"=>"Shipper\PackagesController@barcode"));
	});

	Route::get('/login', array("as"=>"shipper.auth.login","uses"=>"Shipper\AuthController@login"));
	Route::post('/login', array("as"=>"shipper.auth.login","uses"=>"Shipper\AuthController@postLogin"));
	Route::get('/logout', array("as"=>"shipper.auth.logout","uses"=>"Shipper\AuthController@logout"));
});
