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
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('customer_id');
            $table->integer('shipper_id');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('county')->default(1);
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('price');
            $table->integer('distance');
            $table->integer('duration');
            $table->text('steps');
            $table->string('place_id');
            $table->text('note');
            $table->string('label');
            $table->timestamp('delivery_at');
            $table->boolean('active')->default(true);
            $table->boolean('deleted')->default(false);
            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages');
    }
}
