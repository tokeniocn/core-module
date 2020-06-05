<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('bank')->default('')->comment('银行类型 wechat-微信 alipay-支付宝 bank-银行卡信息');
            $table->text('value')->comment('存储值,包括解析出来的二维码内容');
            $table->boolean('enable')->default(false)->comment('是否启用');
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
        Schema::dropIfExists('user_banks');
    }
}
