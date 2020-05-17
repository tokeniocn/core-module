<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 100)->default('')->unique()->comment('设置名称');
            $table->string('module', 100)->nullable()->default('*')->comment('专属模块名,默认*表示全局');
            $table->json('value')->comment('设置内容');
            $table->json('schema')->comment('设置值');
            $table->text('description')->comment('描述');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config');
    }
}
