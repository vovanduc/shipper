<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->increments('id');
                $table->uuid('uuid');
                $table->string('name')->unique();
                $table->integer('quantity')->default(1);
                $table->text('note');
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
        //Schema::drop('locations');
    }
}
