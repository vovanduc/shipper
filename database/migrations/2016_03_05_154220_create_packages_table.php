<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('packages')) {
            Schema::create('packages', function (Blueprint $table) {
                $table->increments('id');
                $table->uuid('uuid');
                $table->uuid('parent');
                $table->uuid('customer_id');
                $table->uuid('shipper_id');
                $table->uuid('location_id');
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('county')->default(1);
                $table->tinyInteger('quantity')->default(1);
                $table->string('address');
                $table->string('latitude');
                $table->string('longitude');
                $table->integer('price');
                $table->integer('distance');
                $table->integer('duration');
                $table->text('steps');
                $table->string('place_id');
                $table->text('content');
                $table->text('note');
                $table->text('invoice');
                $table->string('service_type');
                $table->string('weight');
                $table->string('kgs');
                $table->string('label');
                $table->dateTime('delivery_at');
                $table->boolean('active')->default(true);
                $table->boolean('deleted')->default(false);
                $table->integer('created_by')->default(1);
                $table->integer('updated_by')->default(1);
                $table->timestamps();
            });
        } else {
            Schema::table('packages', function (Blueprint $table) {
                if (!Schema::hasColumn('packages', 'phone')) $table->string('phone');
                if (!Schema::hasColumn('packages', 'customer_from')) $table->uuid('customer_from');
                if (!Schema::hasColumn('packages', 'province_id')) $table->string('province_id');
                if (!Schema::hasColumn('packages', 'district_id')) $table->string('district_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('packages');
    }
}
