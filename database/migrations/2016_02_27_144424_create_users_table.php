<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->uuid('uuid');
                $table->string('name');
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('password', 60);
                $table->string('status', 60);
                $table->rememberToken();
                $table->boolean('active')->default(true);
                $table->boolean('deleted')->default(false);
                $table->boolean('is_admin')->default(false);
                $table->boolean('is_root')->default(false);
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
        //Schema::drop('users');
    }
}
