<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',64)->unique();
            $table->string('email',64)->unique();
            $table->string('phone',64)->unique();
            $table->string('nickname',64)->nullable();
            $table->string('avatar')->nullable();
            $table->integer('create_user')->comment('Creator id');
            $table->tinyInteger('status')->default(1)->comment('State 1: Normal 0: Prohibited');
            $table->string('password',64);
            $table->string('api_token', 64)->unique()->nullable();
            $table->integer('token_expire_time')->default(0);
            $table->rememberToken();
            $table->timestamp('last_login_time')->comment('Last Login Time')->nullable();
            $table->string('last_login_ip')->nullable();
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
        Schema::dropIfExists('sys_admins');
    }
}
