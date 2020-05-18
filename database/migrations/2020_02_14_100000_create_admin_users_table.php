<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('username')->comment('用户名');
            $table->string('avatar')->nullable()->comment('用户头像');
            $table->string('password')->comment('密码');
            $table->dateTimeTz('password_changed_at')->nullable();
            $table->unsignedTinyInteger('active')->default(1)->comment('是否启用');
            $table->dateTimeTz('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();
            $table->dateTimeTz('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
