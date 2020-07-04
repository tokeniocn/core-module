<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operate_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->comment('关联用户ID');
            $table->string('scene', 20)->default('')->comment('操作场景: admin=后台 ');
            $table->string('category', 100)->default('')->comment('操作分类');
            $table->string('operate', 100)->default('')->comment('操作类型');
            $table->text('log')->comment('操作内容');
            $table->json('data')->nullable()->comment('附加记录数据');
            $table->json('context')->nullable()->comment('操作上下文记录, 用于记录调试信息');
            $table->dateTimeTz('created_at')->nullable();

            $table->index(['category', 'operate'], 'category_operate');
            $table->index(['user_id'], 'user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operate_log');
    }
}
