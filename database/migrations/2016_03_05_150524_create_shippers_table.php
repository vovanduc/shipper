<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('shippers')) {
            Schema::create('shippers', function (Blueprint $table) {
              $table->increments('id');
              $table->uuid('uuid');
              $table->string('email')->unique();
              $table->string('phone');
              $table->string('name');
              $table->string('address');
              $table->boolean('active')->default(true);
              $table->boolean('deleted')->default(false);
              $table->integer('created_by')->default(1);
              $table->integer('updated_by')->default(1);
              $table->timestamps();
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
        //Schema::drop('shippers');
    }
}
