<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable.
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('inviter_id')->default(0)->comment('邀请人ID');
            $table->string('username', 50)->unique()->default('')->comment('用户名');
            $table->string('email')->nullable()->default('')->comment('登录邮箱');
            $table->string('mobile')->nullable()->default('')->comment('绑定手机号');
            $table->string('password', 60)->default('')->comment('登录密码');
            $table->string('pay_password')->nullable()->comment('支付密码');
            $table->string('avatar')->default('')->comment('头像地址');
            $table->unsignedTinyInteger('active')->default(0)->comment('是否启用');
            $table->unsignedTinyInteger('auth')->default(0)->comment('是否实名认证');
            $table->dateTime('auth_verified_at')->nullable()->comment('实名认证时间');
            $table->dateTime('last_login_at')->nullable()->comment('最后登录时间');
            $table->string('last_login_ip')->nullable()->comment('最后登录IP');
            $table->rememberToken()->comment('remember me token');
            $table->string('pay_password_updated_at')->nullable()->comment('支付密码设置时间');
            $table->dateTime('mobile_verified_at')->nullable()->comment('手机号验证时间');
            $table->dateTime('email_verified_at')->nullable()->comment('邮箱验证时间');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
