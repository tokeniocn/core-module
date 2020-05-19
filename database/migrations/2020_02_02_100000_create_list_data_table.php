<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 100)->default('')->unique()->comment('设置名称');
            $table->json('value')->comment('设置内容');
            $table->string('type', 100)->default('')->comment('类型');
            $table->string('module', 100)->default('*')->comment('专属模块名,默认*表示全局');
            $table->string('remark')->nullable()->default('')->comment('描述');
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_data');
    }
}
