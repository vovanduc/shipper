<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserTableSeeder::class);
      factory(App\Http\Models\Admin\User::class)->create();
    	factory(App\Http\Models\Admin\Customer::class, 100)->create();
      factory(App\Http\Models\Admin\Shipper::class, 10)->create();
      factory(App\Http\Models\Admin\Package::class, 100)->create();
    }
}
