<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('translatable_model', 200)->comment('关联翻译类');
            $table->bigInteger('translatable_id')->comment('关联翻译类ID');
            $table->string('key')->comment('翻译关键字');
            $table->json('params')->nullable()->comment('翻译参数');
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();

            $table->index(['translatable_model', 'translatable_id'], 'translatable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translates');
    }
}
