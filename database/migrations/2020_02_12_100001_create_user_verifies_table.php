<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVerifiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_verifies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->comment('关联用户ID');
            $table->string('key')->nullable()->default('')->comment('验证关键字 email地址|手机号');
            $table->string('token', 100)->index()->nullable()->default('')->comment('验证token');
            $table->string('type', 40)->nullable()->default('')->comment('验证类型 mail_password_reset|mobile_password_reset...');
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('created_at')->nullable();

            $table->unique(['key', 'token'], 'key_token');
            $table->index(['user_id'], 'user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_verifies');
    }
}
